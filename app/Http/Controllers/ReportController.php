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
    $period = $request->get('period');
    $status = $request->get('status');

    $bulanTahun = $period 
        ? \Carbon\Carbon::createFromFormat('m/Y', $period)->locale('id')->isoFormat('MMMM YYYY')
        : now()->locale('id')->isoFormat('MMMM YYYY');

    $fileName = "Laporan Tagihan JRC Wifi Bulan $bulanTahun.xlsx";

    return Excel::download(new InvoicesExport($period, $status), $fileName);
}
}