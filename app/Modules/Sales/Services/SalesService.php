<?php

namespace App\Modules\Sales\Services;

use App\Modules\Sales\Models\SalesQuotation;
use App\Modules\Sales\Models\SalesOrder;
use App\Modules\Sales\Models\SalesOrderItem;
use App\Core\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesService extends BaseService
{
    public function __construct(\App\Modules\Sales\Models\SalesQuotation $quotationModel)
    {
        parent::__construct($quotationModel);
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

            foreach ($items as $item) {
                $lineTotal = ($item['quantity'] * $item['unit_price']) - ($item['discount'] ?? 0);
                $itemTax = $lineTotal * (($item['tax_rate'] ?? 0) / 100);
                $finalLineTotal = $lineTotal + $itemTax;

                $quotation->items()->create([
                    'product_sku' => $item['product_sku'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'tax_rate' => $item['tax_rate'] ?? 0,
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

            // Copy items from quotation to order
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
            }

            $quotation->update(['status' => 'Converted']);

            return $order;
        });
    }
}
