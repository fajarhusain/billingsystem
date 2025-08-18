<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Exports\PaidInvoicesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScanController;




Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('packages', PackageController::class);
Route::resource('customers', CustomerController::class);
Route::resource('invoices', InvoiceController::class);


Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
Route::put('/packages/{package}', [PackageController::class, 'update'])->name('packages.update');


Route::post('/invoices/generate-monthly', [InvoiceController::class, 'generateMonthly'])
    ->name('invoices.generate-monthly');
    
Route::get('/invoices/export', [InvoiceController::class, 'export'])
    ->name('invoices.export');

    Route::patch('/invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])
    ->name('invoices.mark-as-paid');
// Route::post('/invoices/generate-monthly', [InvoiceController::class, 'generateMonthly'])->name('invoices.generateMonthly');


// routes/web.php
Route::get('/invoices/export', [InvoiceController::class, 'export'])->name('invoices.export');


Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
Route::get('/pindai-qr', [ScanController::class, 'index'])->name('pindaiqr.index');