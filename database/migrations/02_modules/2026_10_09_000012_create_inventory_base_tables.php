<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_uoms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Pieces, Kilograms, Liters
            $table->string('abbreviation'); // e.g., pcs, kg, l
            $table->decimal('conversion_factor', 15, 8)->default(1);
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_category_id')->constrained()->onDelete('set null');
            $table->foreignId('inventory_uom_id')->constrained();
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('min_stock_level', 15, 4)->default(0);
            $table->decimal('max_stock_level', 15, 4)->nullable();
            $table->boolean('is_trackable')->default(true); // For items not requiring stock tracking
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventory_uoms');
        Schema::dropIfExists('inventory_categories');
    }
};
