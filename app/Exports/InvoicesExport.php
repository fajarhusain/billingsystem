<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $status;
    protected $period;

    public function __construct($status = null, $period = null)
    {
        $this->status = $status;
        $this->period = $period;
    }

    public function collection()
    {
        $query = Invoice::with(['customer','customer.package']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->period) {
            $query->where('period', $this->period);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No Invoice',
            'Pelanggan',
            'Paket',
            'Periode',
            'Jumlah',
            'Jatuh Tempo',
            'Status'
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            $invoice->customer->name,
            $invoice->customer->package->name ?? '-',
            $invoice->period,
            $invoice->formatted_amount,
            $invoice->due_date->format('d/m/Y'),
            ucfirst($invoice->status),
        ];
    }
}