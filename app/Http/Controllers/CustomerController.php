<?php

// app/Http/Controllers/CustomerController.php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class CustomerController extends Controller
{
    public function index(Request $request)
{
    $perPage = $request->perPage ?? 10; // default 10
    $query = Customer::query();

    if ($request->dusun) {
        $query->where('dusun', $request->dusun);
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->search) {
        $query->where('name', 'like', '%'.$request->search.'%');
    }

    $customers = $query->with('package')->orderBy('name')->paginate($perPage);
    
    // Menjaga filter/search/perPage tetap aktif di pagination
    $customers->appends($request->all());

    return view('customers.index', compact('customers', 'perPage'));
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
        'dusun' => 'required|string', // tambahkan validasi dusun
        'package_id' => 'required|exists:packages,id',
        'registration_date' => 'required|date',
        'status' => 'required|in:active,suspended,terminated',
        'notes' => 'nullable|string'
    ]);

    // Tentukan kode dusun
    $dusunCode = match ($request->dusun) {
        'rumasan' => '1',
        'rimalang' => '2',
        'semangeng' => '3',
        'mangonan' => '4',
        'pedoyo' => '5',
        default => '0',
    };

    // Hitung jumlah customer per dusun
    $lastCustomer = Customer::where('dusun', $request->dusun)->count() + 1;

    // Bentuk unique code
    $validated['unique_code'] = $dusunCode . str_pad($lastCustomer, 3, '0', STR_PAD_LEFT);

    // Simpan
    $customer = Customer::create($validated);

    return redirect()->route('customers.show', $customer->id)
        ->with('success', 'Pelanggan berhasil ditambahkan dengan kode ' . $validated['unique_code']);
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
        'email' => 'required|email|unique:customers,email,' . $customer->id,
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
        'dusun' => 'required|string',
        'package_id' => 'required|exists:packages,id',
        'registration_date' => 'required|date',
        'status' => 'required|in:active,suspended,terminated',
        'notes' => 'nullable|string'
    ]);

    // Jangan ubah unique_code
    $validated['unique_code'] = $customer->unique_code;

    $customer->update($validated);

    return redirect()->route('customers.show', $customer->id)
        ->with('success', 'Data pelanggan berhasil diperbarui (kode tetap: ' . $customer->unique_code . ')');
}


    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }

    
}