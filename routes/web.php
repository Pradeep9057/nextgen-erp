<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CRM\CRMController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Manufacturing\ManufacturingController;
use App\Http\Controllers\Customization\CustomizationStudioController;
use App\Http\Controllers\FinancialReportController;

Route::get('/', [DashboardController::class, 'index']);

Route::prefix('crm')->group(function () {
    Route::get('/', [CRMController::class, 'index'])->name('crm.index');
    Route::get('/lead/{id}', [CRMController::class, 'showLead'])->name('crm.lead');
    Route::get('/account/{id}', [CRMController::class, 'showAccount'])->name('crm.account');
});

Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/item/{id}/ledger', [InventoryController::class, 'ledger'])->name('inventory.ledger');
    Route::get('/warehouse/{id}', [InventoryController::class, 'warehouse'])->name('inventory.warehouse');
});

Route::prefix('manufacturing')->group(function () {
    Route::get('/', [ManufacturingController::class, 'index'])->name('manufacturing.index');
    Route::get('/bom/{id}', [ManufacturingController::class, 'showBom'])->name('manufacturing.bom');
    Route::get('/orders', [ManufacturingController::class, 'productionOrders'])->name('manufacturing.orders');
});

Route::prefix('customization')->group(function () {
    Route::get('/', [CustomizationStudioController::class, 'index'])->name('customization.index');
    Route::post('/field', [CustomizationStudioController::class, 'createField'])->name('customization.field.store');
    Route::put('/rule/{id}', [CustomizationStudioController::class, 'updateRule'])->name('customization.rule.update');
});

Route::prefix('reports')->group(function () {
    Route::get('/trial-balance', [FinancialReportController::class, 'trialBalance'])->name('reports.trial-balance');
    Route::get('/profit-loss', [FinancialReportController::class, 'profitAndLoss'])->name('reports.profit-loss');
    Route::get('/balance-sheet', [FinancialReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
});

