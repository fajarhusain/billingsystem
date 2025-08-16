<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaidInvoicesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil data tagihan yang sudah terbayar
        return Invoice::where('status', 'lunas')
                      ->with('customer')
                      ->get()
                      ->map(function($invoice) {
                          return [
                              'ID' => $invoice->id,
                              'Nama Customer' => $invoice->customer->name ?? '-',
                              'Nomor Tagihan' => $invoice->invoice_number,
                              'Jumlah' => $invoice->amount,
                              'Tanggal Bayar' => $invoice->paid_at?->format('Y-m-d') ?? '-',
                          ];
                      });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Customer',
            'Nomor Tagihan',
            'Jumlah',
            'Tanggal Bayar',
        ];
    }
}