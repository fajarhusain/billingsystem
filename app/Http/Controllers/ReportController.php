<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;


class ReportController extends Controller
{
    // app/Http/Controllers/ReportController.php
public function index(Request $request)
{
    $query = \App\Models\Invoice::with(['customer','customer.package']);

    // Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter bulan & tahun => gabung ke format YYYY-MM untuk kolom `period`
    $month = $request->input('month'); // '01' .. '12'
    $year  = $request->input('year');  // '2025', dst

    if ($month && $year) {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $period = "{$year}-{$month}";
        $query->where('period', $period);
    }

    $invoices = $query->latest()->paginate(10)->withQueryString();

    return view('reports.index', compact('invoices'));
}

  public function export(Request $request)
{
    $status = $request->get('status');
    $month  = $request->get('month');
    $year   = $request->get('year');

    $period = null;
    $fileName = "LAPORAN TAGIHAN JRC WIFI.xlsx"; // default

    if ($month && $year) {
        // format YYYY-MM untuk filter
        $period = "{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT);

        // Ambil nama bulan Indonesia
        $bulan = \Carbon\Carbon::createFromFormat('m', $month)->locale('id')->translatedFormat('F');

        $fileName = "LAPORAN TAGIHAN JRC WIFI PERIODE {$bulan} {$year}.xlsx";
    } elseif ($year) {
        // hanya tahun
        $fileName = "LAPORAN TAGIHAN JRC WIFI PERIODE TAHUN {$year}.xlsx";
    } else {
        // fallback -> bulan sekarang
        $bulan = now()->locale('id')->translatedFormat('F');
        $tahun = now()->year;
        $fileName = "LAPORAN TAGIHAN JRC WIFI PERIODE {$bulan} {$tahun}.xlsx";
    }

    return Excel::download(
        new InvoicesExport($period, $status),
        $fileName
    );
}}