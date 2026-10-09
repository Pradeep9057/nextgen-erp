<?php

namespace App\Modules\Sales\Services;

use App\Modules\Sales\Models\SalesQuotation;
use App\Modules\Sales\Models\SalesOrder;
use App\Modules\Sales\Models\SalesOrderItem;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Inventory\Services\StockService;
use App\Core\Services\BaseService;
use App\Core\Services\IntegrityManager;
use App\Invoice;
use App\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesService extends BaseService
{
    public function __construct(
        \App\Modules\Sales\Models\SalesQuotation $quotationModel,
        \App\Core\Services\CacheService $cacheService,
        protected StockService $stockService,
        protected IntegrityManager $integrityManager
    ) {
        parent::__construct($quotationModel, $cacheService);
    }

    /**
     * Create a quotation with its line items and calculate totals.
     */
    public function createQuotation(array $data, array $items): SalesQuotation
    {
        return DB::transaction(function () use ($data, $items) {
            $quotation = SalesQuotation::create([
                'organization_id' => $data['organization_id'],
                'crm_account_id' => $data['crm_account_id'],
                'quotation_number' => 'QT-' . strtoupper(Str::random(8)),
                'issue_date' => $data['issue_date'] ?? now(),
                'expiry_date' => $data['expiry_date'] ?? now()->addDays(30),
                'status' => 'Draft',
                'notes' => $data['notes'] ?? null,
            ]);

            $totalAmount = 0;
            $taxAmount = 0;

            foreach ($items as $itemData) {
                // Validate SKU exists
                $inventoryItem = InventoryItem::where('sku', $itemData['product_sku'])->firstOrFail();

                $lineTotal = ($itemData['quantity'] * $itemData['unit_price']) - ($itemData['discount'] ?? 0);
                $itemTax = $lineTotal * (($itemData['tax_rate'] ?? 0) / 100);
                $finalLineTotal = $lineTotal + $itemTax;

                $quotation->items()->create([
                    'product_sku' => $itemData['product_sku'],
                    'description' => $itemData['description'] ?? $inventoryItem->name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'discount' => $itemData['discount'] ?? 0,
                    'tax_rate' => $itemData['tax_rate'] ?? 0,
                    'total_price' => $finalLineTotal,
                ]);

                $totalAmount += $lineTotal;
                $taxAmount += $itemTax;
            }

            $quotation->update([
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
            ]);

            return $quotation;
        });
    }

    /**
     * Convert an accepted quotation into a formal Sales Order.
     */
    public function convertToOrder(int $quotationId, array $orderData): SalesOrder
    {
        return DB::transaction(function () use ($quotationId, $orderData) {
            $quotation = SalesQuotation::findOrFail($quotationId);

            if ($quotation->status !== 'Accepted') {
                throw new \Exception("Only accepted quotations can be converted to orders.");
            }

            $order = SalesOrder::create([
                'organization_id' => $quotation->organization_id,
                'crm_account_id' => $quotation->crm_account_id,
                'sales_quotation_id' => $quotation->id,
                'order_number' => 'SO-' . strtoupper(Str::random(8)),
                'order_date' => now(),
                'total_amount' => $quotation->total_amount,
                'tax_amount' => $quotation->tax_amount,
                'status' => 'Pending',
                'shipping_address' => $orderData['shipping_address'] ?? null,
            ]);

            // Copy items and reserve stock
            foreach ($quotation->items as $item) {
                $order->items()->create([
                    'product_sku' => $item->product_sku,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount,
                    'tax_rate' => $item->tax_rate,
                    'total_price' => $item->total_price,
                ]);

                // Reserve stock if the item is trackable
                $inventoryItem = InventoryItem::where('sku', $item->product_sku)->first();
                if ($inventoryItem && $inventoryItem->is_trackable) {
                    // For reservation, we typically use a specific transaction type or separate reserve table
                    // Here we use a negative movement to represent reservation/allocation
                    // In a real system, we'd use a 'RESERVED' status in the ledger
                    $this->stockService->moveStock(
                        $inventoryItem->id,
                        1, // Default warehouse/location for reservation
                        -$item->quantity,
                        'RESERVATION',
                        'SalesOrder',
                        $order->id,
                        "Reservation for Order {$order->order_number}"
                    );
                }
            }

            $quotation->update(['status' => 'Converted']);

            return $order;
        });
    }

    /**
     * Fulfill a sales order, moving status to 'Shipped' and finalizing stock deduction.
     */
    public function fulfillOrder(int $orderId): SalesOrder
    {
        return DB::transaction(function () use ($orderId) {
            $order = SalesOrder::findOrFail($orderId);

            if ($order->status !== 'Pending') {
                throw new \Exception("Only pending orders can be fulfilled.");
            }

            // 1. Process each item for final shipment
            foreach ($order->items as $item) {
                $inventoryItem = InventoryItem::where('sku', $item->product_sku)->firstOrFail();

                if ($inventoryItem->is_trackable) {
                    // Resolve the reservation by creating an 'ISSUE' movement.
                    // In our current immutable ledger, the 'RESERVATION' already decreased
                    // the available stock. The 'SHIPMENT' record serves as the final
                    // legal movement from the warehouse.
                    $this->stockService->moveStock(
                        $inventoryItem->id,
                        1, // Default location
                        0, // Quantity 0 because the stock was already decreased during reservation
                        'SHIPMENT',
                        'SalesOrder',
                        $order->id,
                        "Final shipment for Order {$order->order_number}"
                    );
                }
            }

            // 2. Update order status
            $order->update(['status' => 'Shipped']);

            return $order;
        });
    }

    /**
     * Generate an invoice from a shipped sales order and anchor it to the trust-chain.
     */
    public function generateInvoice(int $orderId): Invoice
    {
        return DB::transaction(function () use ($orderId) {
            $order = SalesOrder::findOrFail($orderId);

            if ($order->status !== 'Shipped') {
                throw new \Exception("Only shipped orders can be invoiced.");
            }

            // 1. Create the Invoice
            $invoice = Invoice::create([
                'organization_id' => $order->organization_id,
                'crm_account_id' => $order->crm_account_id,
                'sales_order_id' => $order->id,
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'total_amount' => $order->total_amount,
                'tax_amount' => $order->tax_amount,
                'status' => 'Unpaid',
            ]);

            // 2. Create Invoice Line Items
            foreach ($order->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_sku' => $item->product_sku,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount,
                    'tax_rate' => $item->tax_rate,
                    'total_price' => $item->total_price,
                ]);
            }

            // 3. Anchor the Invoice to the TrustPath (Blockchain Integrity)
            // We seal the invoice record to ensure it cannot be tampered with after issuance
            $invoiceData = [
                'invoice_number' => $invoice->invoice_number,
                'total_amount' => $invoice->total_amount,
                'status' => $invoice->status,
                'order_id' => $order->id
            ];

            $this->integrityManager->sealRecord('Invoice', $invoice->id, $invoiceData);

            return $invoice;
        });
    }
}
