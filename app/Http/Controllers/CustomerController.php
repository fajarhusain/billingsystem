<?php

// app/Http/Controllers/CustomerController.php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('package')->latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $packages = Package::where('status', 'active')->get();
        return view('customers.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'registration_date' => 'required|date',
            'status' => 'required|in:active,suspended,terminated',
            'notes' => 'nullable|string'
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan!');
    }

  public function show($id)
{
    $customer = Customer::with('package', 'payments')->findOrFail($id);

    // Ambil status pembayaran per bulan (tahun sekarang atau 2025)
    $tahun = 2025;
    $paymentStatus = [];
    foreach (range(1, 12) as $bulan) {
        $paymentStatus[$bulan] = $customer->payments()
            ->whereYear('period', $tahun)
            ->whereMonth('period', $bulan)
            ->where('status', 'paid')
            ->exists();
    }

    return view('customers.show', compact('customer', 'paymentStatus', 'tahun'));
}


    public function edit(Customer $customer)
    {
        $packages = Package::where('status', 'active')->get();
        return view('customers.edit', compact('customer', 'packages'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,'.$customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'registration_date' => 'required|date',
            'status' => 'required|in:active,suspended,terminated',
            'notes' => 'nullable|string'
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }
}