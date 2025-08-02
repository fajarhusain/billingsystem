<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::latest()->get();
        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:home,business,corporate',
        'speed' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
        'status' => 'required|in:active,inactive',
        'description' => 'nullable|string'
    ]);
    
    Package::create($validated);
    
    return redirect()->route('packages.index')
                     ->with('success', 'Paket berhasil ditambahkan!');
}

        

    public function show(Package $package)
    {
        $package->load('customers');
        return view('packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_mbps' => 'required|integer|min:1',
            'quota' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $package->update($validated);

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil diupdate!');
    }

    public function destroy(Package $package)
    {
        if ($package->customers()->count() > 0) {
            return redirect()->route('packages.index')
                ->with('error', 'Tidak dapat menghapus paket yang masih digunakan pelanggan!');
        }

        $package->delete();

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil dihapus!');
    }



}