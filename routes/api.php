<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomFieldController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('customization')->group(function () {
        Route::post('/fields', [CustomFieldController::class, 'store']);
        Route::get('/values/{entityType}/{entityId}', [CustomFieldController::class, 'getValues']);
        Route::post('/values/{entityType}/{entityId}', [CustomFieldController::class, 'updateValue']);
    });
});
use App\Core\Http\Controllers\HealthController;
use App\Modules\Organization\Http\Controllers\OrganizationController;
use App\Modules\CRM\Http\Controllers\LeadController;
use App\Modules\CRM\Http\Controllers\AccountController;
use App\Modules\CRM\Http\Controllers\OpportunityController;
use App\Modules\Sales\Http\Controllers\QuotationController;
use App\Modules\Sales\Http\Controllers\ApprovalController;
use App\Modules\Inventory\Http\Controllers\StockController;
use App\Modules\Manufacturing\Http\Controllers\ProductionController;
use App\Modules\Manufacturing\Http\Controllers\VarianceController;
use App\Core\Http\Controllers\VerificationController;

// System Health
Route::get('/health', [HealthController::class, 'check']);

// Organization
Route::prefix('org')->group(function () {
    Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::post('/organizations', [OrganizationController::class, 'store']);
    Route::get('/organizations/{id}', [OrganizationController::class, 'show']);
});

// CRM
Route::prefix('crm')->group(function () {
    Route::get('/leads', [LeadController::class, 'index']);
    Route::post('/leads', [LeadController::class, 'store']);
    Route::post('/leads/{id}/convert', [LeadController::class, 'convert']);

    Route::get('/accounts', [AccountController::class, 'index']);
    Route::post('/accounts', [AccountController::class, 'store']);
    Route::get('/accounts/{id}', [AccountController::class, 'show']);

    Route::get('/opportunities', [OpportunityController::class, 'index']);
    Route::post('/opportunities', [OpportunityController::class, 'store']);
    Route::patch('/opportunities/{id}/stage', [OpportunityController::class, 'updateStage']);
    Route::get('/opportunities/pipeline', [OpportunityController::class, 'pipelineSummary']);
});

// Sales
Route::prefix('sales')->group(function () {
    Route::post('/quotations', [QuotationController::class, 'store']);
    Route::post('/quotations/{id}/convert', [QuotationController::class, 'convertToOrder']);

    Route::post('/approvals/request/{id}', [ApprovalController::class, 'request']);
    Route::post('/approvals/resolve/{id}', [ApprovalController::class, 'resolve']);
});

// Inventory
Route::prefix('inventory')->group(function () {
    Route::post('/stock/adjust', [StockController::class, 'adjust']);
    Route::get('/stock/balance/{item}/{location}', [StockController::class, 'balance']);
    Route::post('/stock/transfer', [StockController::class, 'transfer']);
});

// Manufacturing
Route::prefix('mfg')->group(function () {
    Route::post('/production', [ProductionController::class, 'store']);
    Route::post('/production/{id}/consume', [ProductionController::class, 'consume']);
    Route::post('/production/{id}/complete', [ProductionController::class, 'complete']);
    Route::get('/production/{id}/variance', [VarianceController::class, 'show']);
});

// Enterprise Integrity
Route::get('/verify', [VerificationController::class, 'verify']);
