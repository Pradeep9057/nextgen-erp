<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_module_id')->constrained()->onDelete('cascade');
            $table->string('field_key')->index(); // Stable identifier
            $table->string('label');
            $table->string('type'); // text, number, date, select, etc.
            $table->string('validation_rules')->nullable(); // Comma-separated Laravel rules
            $table->boolean('is_required')->default(false);
            $table->boolean('is_searchable')->default(false);
            $table->json('options')->nullable(); // For dropdowns
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['custom_module_id', 'field_key']);
        });

        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('entity_id'); // ID of the record in the module's main table
            $table->text('value');
            $table->timestamps();

            $table->index(['custom_field_id', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_field_values');
        Schema::dropIfExists('custom_fields');
        Schema::dropIfExists('custom_modules');
    }
};
