<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\PaymentController;


// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Master Data
Route::resource('packages', PackageController::class);
Route::resource('customers', CustomerController::class);

// Invoices
Route::resource('invoices', InvoiceController::class);

Route::get('/invoices/export', [InvoiceController::class, 'export'])->name('invoices.export');
Route::patch('/invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.markAsPaid');
Route::get('/invoices/{id}/print', [InvoiceController::class, 'print'])->name('invoices.print');
Route::get('/invoices/detailtagihancustomer/{id}', [InvoiceController::class, 'detailTagihanCustomer'])->name('invoices.detailTagihanCustomer');
Route::post('/invoices/generate', [InvoiceController::class, 'generateMonthly'])->name('invoices.generate');


// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

// QR Scanner
Route::get('/pindai-qr', [ScanController::class, 'index'])->name('pindaiqr.index');
Route::get('/invoices/pindaiqr', [InvoiceController::class, 'pindaiqr'])->name('invoices.pindaiqr');


Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');