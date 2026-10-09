<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('crm_account_id')->constrained('crm_accounts')->onDelete('cascade');
            $table->string('quotation_number')->unique();
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->string('status')->default('Draft'); // Draft, Sent, Accepted, Rejected, Converted
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('crm_account_id')->constrained('crm_accounts')->onDelete('cascade');
            $table->foreignId('sales_quotation_id')->nullable()->constrained('sales_quotations');
            $table->string('order_number')->unique();
            $table->date('order_date');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->string('status')->default('Pending'); // Pending, Approved, Shipped, Cancelled
            $table->text('shipping_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            $table->morphs('orderable'); // Can be linked to sales_quotations or sales_orders
            $table->string('product_sku');
            $table->string('description');
            $table->decimal('quantity', 15, 4)->default(1);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
        Schema::dropIfExists('sales_orders');
        Schema::dropIfExists('sales_quotations');
    }
};
