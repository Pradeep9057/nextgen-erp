<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('inventory_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_warehouse_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., A-1-1 (Aisle-Rack-Shelf)
            $table->string('code')->nullable();
            $table->timestamps();

            $table->unique(['inventory_warehouse_id', 'name']);
        });

        Schema::create('inventory_stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_location_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 15, 4); // Positive for IN, Negative for OUT
            $table->string('transaction_type'); // RECEIPT, ISSUE, TRANSFER, ADJUSTMENT, RETURN
            $table->string('reference_type')->nullable(); // SalesOrder, PurchaseOrder, ManufacturingOrder
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamp('transaction_date');
            $table->foreignId('user_id')->constrained();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['inventory_item_id', 'inventory_location_id']);
            $table->index(['transaction_type', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_stock_ledgers');
        Schema::dropIfExists('inventory_locations');
        Schema::dropIfExists('inventory_warehouses');
    }
};
