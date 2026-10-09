<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Organization\Models\Organization;
use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Models\Account;
use App\Modules\Sales\Models\SalesQuotation;
use App\Modules\Sales\Models\SalesOrderItem;
use App\Modules\Sales\Models\SalesOrder;

class LeadToCashTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_lead_to_cash_workflow()
    {
        // 1. Setup Organization
        $org = Organization::create([
            'name' => 'Test Corp',
            'currency' => 'USD'
        ]);

        // 2. Create Lead
        $lead = Lead::create([
            'organization_id' => $org->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'company_name' => 'Example Ltd',
            'status' => 'New'
        ]);

        // 3. Convert Lead to Account
        $crmService = app(\App\Modules\CRM\Services\CRMService::class);
        $conversion = $crmService->convertLeadToAccount($lead->id);

        $this->assertInstanceOf(Account::class, $conversion['account']);
        $this->assertEquals('Converted', $lead->fresh()->status);

        // 4. Create Quotation
        $quotation = SalesQuotation::create([
            'organization_id' => $org->id,
            'crm_account_id' => $conversion['account']->id,
            'quotation_number' => 'QT-123',
            'issue_date' => now(),
            'total_amount' => 1000,
            'status' => 'Accepted'
        ]);

        SalesOrderItem::create([
            'orderable_id' => $quotation->id,
            'orderable_type' => SalesQuotation::class,
            'product_sku' => 'PROD-001',
            'description' => 'Enterprise License',
            'quantity' => 1,
            'unit_price' => 1000,
            'total_price' => 1000,
        ]);

        // 5. Convert Quotation to Order
        $salesService = app(\App\Modules\Sales\Services\SalesService::class);
        $order = $salesService->convertToOrder($quotation->id, ['shipping_address' => '123 Tech Lane']);

        $this->assertInstanceOf(SalesOrder::class, $order);
        $this->assertEquals('Converted', $quotation->fresh()->status);
        $this->assertEquals(1, $order->items()->count());
        $this->assertEquals('PROD-001', $order->items()->first()->product_sku);
    }
}
