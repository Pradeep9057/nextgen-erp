<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mfg_boms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('finished_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->string('bom_number')->unique();
            $table->string('version')->default('1.0');
            $table->decimal('quantity_to_produce', 15, 4)->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('mfg_bom_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mfg_bom_id')->constrained('mfg_boms')->onDelete('cascade');
            $table->foreignId('component_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->decimal('quantity_required', 15, 4);
            $table->string('uom_override')->nullable(); // In case component uses different UOM in BOM
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('mfg_production_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('mfg_bom_id')->constrained('mfg_boms');
            $table->foreignId('finished_item_id')->constrained('inventory_items');
            $table->string('production_number')->unique();
            $table->decimal('quantity_planned', 15, 4);
            $table->decimal('quantity_produced', 15, 4)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('Planned'); // Planned, In Progress, Completed, Cancelled
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mfg_production_consumption', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mfg_production_order_id')->constrained('mfg_production_orders')->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained('inventory_items');
            $table->foreignId('inventory_location_id')->constrained('inventory_locations');
            $table->decimal('quantity_consumed', 15, 4);
            $table->timestamp('consumed_at');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mfg_production_consumption');
        Schema::dropIfExists('mfg_production_orders');
        Schema::dropIfExists('mfg_bom_items');
        Schema::dropIfExists('mfg_boms');
    }
};
