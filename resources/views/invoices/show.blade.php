@extends('layouts.app')

@section('content')
<h1>Invoice #{{ $invoice->id }}</h1>
<p>Customer: {{ $invoice->customer->name }}</p>
<p>Paket: {{ $invoice->customer->package->name ?? '-' }}</p>
<p>Total: Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>

<hr class="my-4">

<div class="card shadow-sm">
    <div class="card-header text-center">
        <h4 class="mb-0">TAGIHAN INTERNET</h4>
        <small>JRC MEDIA ID</small>
    </div>
    <div class="card-body">

        {{-- Informasi Pelanggan --}}
        <div class="form-group row mb-3">
            <label class="col-sm-3 col-form-label">Nama Pelanggan</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" value="{{ $invoice->customer->name }}" readonly>
            </div>
            <label class="col-sm-3 col-form-label">ID Pelanggan</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" value="{{ $invoice->customer->id }}" readonly>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-sm-3 col-form-label">Alamat</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" value="{{ $invoice->customer->address ?? '-' }}" readonly>
            </div>
            <label class="col-sm-3 col-form-label">Instalasi</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" value="{{ $invoice->customer->installation ?? '-' }}" readonly>
            </div>
        </div>

        <div class="form-group row mb-4">
            <label class="col-sm-3 col-form-label">Harga Paket</label>
            <div class="col-sm-3">
                <input type="text" class="form-control"
                    value="Rp {{ number_format($invoice->customer->package->price ?? 0,0,',','.') }}" readonly>
            </div>
        </div>

        {{-- Tahun --}}
        <h5 class="text-center my-4">TAHUN 2025</h5>

        {{-- Grid Bulan --}}
        <div class="row text-center">
            @php
            $months = [
            '01'=>'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL',
            '05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS',
            '09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER'
            ];

            // Pastikan relasi invoices sudah dimuat
            $customerInvoices = $invoice->customer->invoices
            ? $invoice->customer->invoices->filter(function($inv) {
            return str_starts_with($inv->period, '2025-');
            })->keyBy(function($inv) {
            return \Carbon\Carbon::parse($inv->period.'-01')->format('m');
            })
            : collect();
            @endphp

            @foreach($months as $num => $namaBulan)
            @php
            $inv = $customerInvoices[$num] ?? null;
            $bg = ($inv && $inv->status === 'paid') ? 'bg-success' : 'bg-danger';
            @endphp
            <div class="col-3 mb-3">
                <div class="p-3 {{ $bg }} text-white font-weight-bold rounded">
                    {{ $namaBulan }}
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
@endsection