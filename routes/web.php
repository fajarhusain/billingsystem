<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('packages', PackageController::class);
Route::resource('customers', CustomerController::class);
Route::resource('invoices', InvoiceController::class);

Route::post('invoices/generate-monthly', [InvoiceController::class, 'generateMonthly'])
    ->name('invoices.generate-monthly');
Route::patch('invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])
    ->name('invoices.mark-as-paid');
Route::get('invoices-export', [InvoiceController::class, 'export'])
    ->name('invoices.export');

    