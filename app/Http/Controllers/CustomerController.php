<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('package');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(15);
        $packages = Package::where('is_active', true)->get();

        return view('customers.index', compact('customers', 'packages'));
    }

    public function create()
    {
        $packages = Package::where('is_active', true)->get();
        return view('customers.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'installation_date' => 'nullable|date'
        ]);

        $validated['registration_date'] = Carbon::now();

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function show(Customer $customer)
    {
        $customer->load('package', 'invoices.payments');
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $packages = Package::where('is_active', true)->get();
        return view('customers.edit', compact('customer', 'packages'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'status' => 'required|in:active,inactive,suspended',
            'installation_date' => 'nullable|date'
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Data pelanggan berhasil diupdate!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }
}