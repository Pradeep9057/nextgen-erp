<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gl_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique(); // e.g., 1000 for Assets, 4000 for Revenue
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('gl_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('gl_account_id')->constrained()->onDelete('cascade');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('reference_type'); // e.g., Invoice, StockMovement
            $table->unsignedBigInteger('reference_id');
            $table->timestamp('entry_date');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gl_accounts');
    }
};
