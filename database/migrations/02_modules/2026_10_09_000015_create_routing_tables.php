<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mfg_work_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('hourly_cost', 15, 2)->default(0);
            $table->decimal('capacity_per_hour', 15, 4)->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('mfg_routings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mfg_bom_id')->constrained('mfg_boms')->onDelete('cascade');
            $table->string('routing_name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('mfg_routing_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mfg_routing_id')->constrained('mfg_routings')->onDelete('cascade');
            $table->foreignId('mfg_work_center_id')->constrained('mfg_work_centers');
            $table->integer('step_sequence')->default(1);
            $table->decimal('setup_time', 15, 4)->default(0); // Hours
            $table->decimal('run_time_per_unit', 15, 4)->default(0); // Hours per unit
            $table->timestamps();

            $table->unique(['mfg_routing_id', 'step_sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mfg_routing_steps');
        Schema::dropIfExists('mfg_routings');
        Schema::dropIfExists('mfg_work_centers');
    }
};
