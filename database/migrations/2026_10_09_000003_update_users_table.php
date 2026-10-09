<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organization_id')->after('id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->after('organization_id')->nullable()->constrained()->onDelete('set null');
            $table->string('employee_id')->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['organization_id', 'branch_id', 'employee_id', 'is_active']);
        });
    }
};
