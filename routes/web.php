<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RepairServiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('suppliers', SupplierController::class);
    Route::resource('items', ItemController::class);
    Route::post('/stock-entries/quick-items', [StockEntryController::class, 'quickStoreItem'])->name('stock-entries.quick-items.store');
    Route::post('/stock-entries/quick-suppliers', [StockEntryController::class, 'quickStoreSupplier'])->name('stock-entries.quick-suppliers.store');
    Route::resource('stock-entries', StockEntryController::class)->parameters(['stock-entries' => 'stockEntry']);
    Route::get('/receipts/search', [SaleController::class, 'searchReceipt'])->name('receipts.search');
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    Route::resource('sales', SaleController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::get('/service-orders/{serviceOrder}/complete', [ServiceOrderController::class, 'complete'])->name('service-orders.complete');
    Route::post('/service-orders/{serviceOrder}/complete', [ServiceOrderController::class, 'storeCompletion'])->name('service-orders.complete.store');
    Route::resource('service-orders', ServiceOrderController::class)->parameters(['service-orders' => 'serviceOrder']);
    Route::resource('repair-services', RepairServiceController::class)->parameters(['repair-services' => 'repairService']);
    Route::get('/company-settings', [CompanySettingController::class, 'edit'])->name('company-settings.edit');
    Route::put('/company-settings', [CompanySettingController::class, 'update'])->name('company-settings.update');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/sales/export', [ReportController::class, 'exportSales'])->name('reports.sales.export');
    Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/stock/export', [ReportController::class, 'exportStock'])->name('reports.stock.export');
    Route::get('/reports/suppliers', [ReportController::class, 'suppliers'])->name('reports.suppliers');
    Route::get('/reports/suppliers/export', [ReportController::class, 'exportSuppliers'])->name('reports.suppliers.export');
    Route::get('/reports/items', [ReportController::class, 'items'])->name('reports.items');
    Route::get('/reports/items/export', [ReportController::class, 'exportItems'])->name('reports.items.export');
    Route::get('/reports/repair-services', [ReportController::class, 'repairServices'])->name('reports.repair_services');
    Route::get('/reports/repair-services/export', [ReportController::class, 'exportRepairServices'])->name('reports.repair_services.export');
    Route::get('/reports/profit', [ReportController::class, 'profit'])->name('reports.profit');
    Route::get('/reports/profit/export', [ReportController::class, 'exportProfit'])->name('reports.profit.export');
    Route::get('/reports/high-selling', [ReportController::class, 'highSelling'])->name('reports.high_selling');
    Route::get('/reports/high-selling/export', [ReportController::class, 'exportHighSelling'])->name('reports.high_selling.export');
    Route::get('/reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low_stock');
    Route::get('/reports/low-stock/export', [ReportController::class, 'exportLowStock'])->name('reports.low_stock.export');
});
