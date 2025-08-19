<?php

namespace App\Exports;

use App\Models\Invoice;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithColumnFormatting
{
    protected $period;
    protected $status;
    protected $invoices;
    protected $totalAmount = 0;
    protected $rowNumber = 0;

    public function __construct($period, $status)
    {
        $this->period = $period; // format: YYYY-MM (contoh: 2025-08)
        $this->status = $status;
    }

    public function collection()
    {
        $query = Invoice::with(['customer', 'customer.package']);

        if ($this->period) {
            // period disimpan di kolom `period` dengan format YYYY-MM
            $query->where('period', $this->period);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $this->invoices = $query->orderBy('created_at', 'asc')->get();

        $this->totalAmount = $this->invoices->sum('amount');

        return $this->invoices;
    }

    public function headings(): array
    {
        return [];
    }

    public function map($invoice): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $invoice->customer->name ?? '-',
            $invoice->customer->package->name ?? '-',
            $invoice->amount,
            ucfirst($invoice->status),
            $invoice->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function columnFormats(): array
    {
        return [
            'D' => '"Rp "#,##0',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $titleStyle = [
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ];
                $headerStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ];
                $totalStyle = [
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DCE6F1']],
                    'borders' => ['top' => ['borderStyle' => Border::BORDER_THICK]],
                ];

                // 🔹 Ubah period jadi teks
                if ($this->period) {
                    $date = Carbon::createFromFormat('Y-m', $this->period);
                    $periodText = $date->locale('id')->translatedFormat('F Y');
                } else {
                    $periodText = 'Semua Periode';
                }

                $statusText = $this->status ? ucfirst($this->status) : 'Semua Status';

                $sheet->insertNewRowBefore(1, 4);

                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'LAPORAN INVOICE');
                $sheet->getStyle('A1')->applyFromArray($titleStyle);

                $sheet->setCellValue('A3', 'Periode');
                $sheet->setCellValue('B3', ': ' . $periodText);
                $sheet->setCellValue('A4', 'Status');
                $sheet->setCellValue('B4', ': ' . $statusText);
                $sheet->getStyle('A3:A4')->getFont()->setBold(true);

                $headerRow = 6;
                $headings = ['No', 'Nama Pelanggan', 'Paket', 'Nominal', 'Status', 'Tanggal'];
                $sheet->fromArray($headings, NULL, 'A' . $headerRow);
                $sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->applyFromArray($headerStyle);
                $sheet->getRowDimension($headerRow)->setRowHeight(20);

                $firstDataRow = $headerRow + 1;
                $lastRow = $firstDataRow + $this->invoices->count() - 1;
                $lastRowWithTotal = $lastRow + 1;

                $sheet->setCellValue("C{$lastRowWithTotal}", "TOTAL");
                $sheet->setCellValue("D{$lastRowWithTotal}", $this->totalAmount);
                $sheet->getStyle("C{$lastRowWithTotal}:D{$lastRowWithTotal}")->applyFromArray($totalStyle);
                $sheet->getStyle("D{$lastRowWithTotal}")->getNumberFormat()->setFormatCode('"Rp "#,##0');

                if ($this->invoices->count() > 0) {
                    $dataRange = 'A' . $firstDataRow . ':F' . $lastRow;
                    $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                    $sheet->getStyle('A' . $firstDataRow . ':A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('B' . $firstDataRow . ':C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle('D' . $firstDataRow . ':D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle('E' . $firstDataRow . ':F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                $sheet->getColumnDimension('B')->setWidth(30);
            },
        ];
    }
}