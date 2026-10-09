<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('crm_account_id')->constrained('crm_accounts')->onDelete('cascade');
            $table->foreignId('crm_contact_id')->nullable()->constrained('crm_contacts')->onDelete('set null');
            $table->string('title');
            $table->decimal('estimated_value', 15, 2)->default(0);
            $table->string('stage')->default('Qualification'); // Qualification, Proposal, Negotiation, Closed Won, Closed Lost
            $table->date('expected_close_date')->nullable();
            $table->string('probability')->default('10%'); // 10%, 25%, 50%, 75%, 90%
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('crm_opportunity_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_opportunity_id')->constrained('crm_opportunities')->onDelete('cascade');
            $table->string('from_stage');
            $table->string('to_stage');
            $table->timestamp('changed_at');
            $table->text('notes')->nullable();
            $table->foreignId('changed_by')->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_opportunity_history');
        Schema::dropIfExists('crm_opportunities');
    }
};
