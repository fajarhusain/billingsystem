<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;


class InvoiceController extends Controller
{
    public function index(Request $request)
{
    $query = Invoice::with('customer.package');

// Filter bulan & tahun
if ($request->filled('month') || $request->filled('year')) {
    $year = $request->year ?? date('Y');
    $month = $request->month;

    if ($month) {
        $query->where('period', 'like', "$year-$month%");
    } else {
        $query->where('period', 'like', "$year-%");
    }
}

// Filter dusun
if ($request->filled('dusun') && $request->dusun !== 'all') {
    $query->whereHas('customer', function($q) use ($request) {
        $q->where('dusun_id', $request->dusun);
    });
}

// Filter status
if ($request->filled('status') && $request->status !== '') {
    if ($request->status === 'overdue') {
        $query->where('status', 'unpaid')
              ->whereDate('due_date', '<', now());
    } else {
        $query->where('status', $request->status);
    }
}

$invoices = $query->orderBy('period', 'desc')->paginate(10);


    // Data Dusun untuk select
    // $dusuns = ['all' => 'Semua Dusun', 'rumasan'=> 'Rumasan', 'rimalang'=> 'Rimalang', 'semangeng'=> 'Semangeng', 'mangonan'=> 'Mangonan', 'pedoyo'=> 'Pedoyo'];
    
    // Data Dusun untuk select
$dusuns = ['all' => 'Semua Dusun',
    'rumasan'=> 'Rumasan',
    'rimalang'=> 'Rimalang',
    'semangeng'=> 'Semangeng',
    'mangonan'=> 'Mangonan',
    'pedoyo'=> 'Pedoyo',
];



// Filter dusun
if ($request->filled('dusun') && $request->dusun !== 'all') {
    $query->whereHas('customer', function($q) use ($request) {
        $q->where('dusun_id', $request->dusun); // gunakan dusun_id
    });
    // Filter dusun
if ($request->filled('dusun') && $request->dusun !== 'all') {
    $query->whereHas('customer', function($q) use ($request) {
        $q->where('dusun_id', $request->dusun); // gunakan dusun_id
    });
}
}
    
    return view('invoices.index', compact('invoices', 'dusuns'));
}





    public function create()
    {
        $customers = Customer::where('status', 'active')->with('package')->get();
        return view('invoices.create', compact('customers'));
        
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'period' => 'required|string',
            'due_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $customer = Customer::with('package')->findOrFail($validated['customer_id']);
        
        // Check if invoice already exists for this period
        $existingInvoice = Invoice::where('customer_id', $customer->id)
            ->where('period', $validated['period'])
            ->first();

        if ($existingInvoice) {
            return redirect()->back()
                ->with('error', 'Tagihan untuk periode ini sudah ada!');
        }

        $validated['invoice_number'] = Invoice::generateInvoiceNumber($customer->id, $validated['period']);
        $validated['amount'] = $customer->package->price;

        Invoice::create($validated);

        return redirect()->route('invoices.index')
            ->with('success', 'Tagihan berhasil dibuat!');
    }

    public function generateMonthly(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|date_format:Y-m',
            'due_date' => 'required|date'
        ]);

        $activeCustomers = Customer::where('status', 'active')->with('package')->get();
        $generatedCount = 0;

        foreach ($activeCustomers as $customer) {
            // Check if invoice already exists
            $existingInvoice = Invoice::where('customer_id', $customer->id)
                ->where('period', $validated['period'])
                ->first();

            if (!$existingInvoice) {
                Invoice::create([
                    'invoice_number' => Invoice::generateInvoiceNumber($customer->id, $validated['period']),
                    'customer_id' => $customer->id,
                    'period' => $validated['period'],
                    'amount' => $customer->package->price,
                    'due_date' => $validated['due_date']
                ]);
                $generatedCount++;
            }
        }

        return redirect()->route('invoices.index')
            ->with('success', "{$generatedCount} tagihan berhasil di-generate untuk periode {$validated['period']}!");
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('customer', 'customer.package', 'customer.invoices', 'payments');
    return view('invoices.show', compact('invoice'));
    }

  


    public function markAsPaid(Request $request, Invoice $invoice)
{
    $validated = $request->validate([
        'payment_date' => 'required|date',
        'payment_method' => 'required|string',
        'reference_number' => 'nullable|string',
        'notes' => 'nullable|string'
    ]);

    // Update status invoice
    $invoice->update([
        'status' => 'paid',
        'payment_date' => $validated['payment_date'],
        'payment_method' => $validated['payment_method']
    ]);

    // Catat pembayaran
    Payment::create([
        'invoice_id' => $invoice->id,
        'customer_id' => $invoice->customer_id, // penting!
        'amount' => $invoice->amount,
        'payment_date' => $validated['payment_date'],
        'payment_method' => $validated['payment_method'],
        'reference_number' => $validated['reference_number'] ?? null,
        'notes' => $validated['notes'] ?? null
        
    ]);

    return redirect()->route('invoices.show', $invoice)
        ->with('success', 'Pembayaran berhasil dicatat!');
}



    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.index')
                ->with('error', 'Tidak dapat menghapus tagihan yang sudah dibayar!');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Tagihan berhasil dihapus!');
    }

    public function export(Request $request)
{
    // Filter data sesuai request
    $query = Invoice::with('customer')
        ->where('status', 'paid');

if ($request->filled('month')) {
    $month = $request->month;
    // ambil juga tahun, default tahun sekarang
    $year = $request->year ?? date('Y');
    
    // jika period bertipe date
    $query->whereYear('period', $year)
          ->whereMonth('period', $month);
}

    $invoices = $query->get();

    return Excel::download(new InvoicesExport($invoices), 'tagihan_terbayar.xlsx');
}

public function scanqr()
{
    return view('invoices.scanqr');
}

public function print($id)
{
    $invoice = Invoice::with('customer.package')->findOrFail($id);

    return view('invoices.print', compact('invoice'));
}
public function detailTagihanCustomer($customerId)
{
    $customer = Customer::with('invoices.payments', 'package')->findOrFail($customerId);
    return view('invoices.detailtagihancustomer', compact('customer'));
}

public function showCustomer($customerId)
{
    $customer = Customer::with('invoices.payments', 'package')->findOrFail($customerId);
    return view('invoices.show', compact('customer'));
}







    // public function export(Request $request)
    // {
    //     $query = Invoice::with('customer', 'customer.package');

    //     if ($request->has('status') && !empty($request->status)) {
    //         $query->where('status', $request->status);
    //     }

    //     if ($request->has('period') && !empty($request->period)) {
    //         $query->where('period', $request->period);
    //     }

    //     $invoices = $query->get();

    //     $filename = 'tagihan_' . date('Y-m-d_H-i-s') . '.csv';
        
    //     $headers = [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => "attachment; filename=\"$filename\"",
    //     ];

    //     $callback = function() use ($invoices) {
    //         $file = fopen('php://output', 'w');
            
    //         // CSV Header
    //         fputcsv($file, [
    //             'No Invoice',
    //             'Pelanggan',
    //             'Paket',
    //             'Periode',
    //             'Jumlah',
    //             'Jatuh Tempo',
    //             'Status',
    //             'Tanggal Bayar'
    //         ]);

    //         // CSV Data
    //         foreach ($invoices as $invoice) {
    //             fputcsv($file, [
    //                 $invoice->invoice_number,
    //                 $invoice->customer->name,
    //                 $invoice->customer->package->name,
    //                 $invoice->period,
    //                 $invoice->amount,
    //                 $invoice->due_date->format('d/m/Y'),
    //                 ucfirst($invoice->status),
    //                 $invoice->payment_date ? $invoice->payment_date->format('d/m/Y') : ''
    //             ]);
    //         }

    //         fclose($file);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }




    

    
}