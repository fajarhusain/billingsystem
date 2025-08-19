<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_method' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);

// app/Http/Controllers/PaymentController.php

Payment::create([
    'invoice_id'     => $invoice->id,
    'customer_id'    => $invoice->customer_id, // <-- tambahkan ini
    'amount'         => $invoice->amount,
    'payment_method' => $request->payment_method,
    'note'           => $request->note,
    'payment_date'   => now(),
]);



        // Update status invoice
        $invoice->update(['status' => 'paid']);

        return redirect()->back()->with('success', 'Pembayaran berhasil disimpan dan tagihan sudah ditandai lunas.');
    }
}