@extends('layouts.app')

@section('content')
<h1>Invoice #{{ $invoice->id }}</h1>
<p>Customer: {{ $invoice->customer->name }}</p>
<p>Paket: {{ $invoice->customer->package->name ?? '-' }}</p>
<p>Total: Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
@endsection

{{-- Tambahkan ini di bawah tombol Edit & Kembali --}}
<hr class="my-4">

<div class="card shadow-sm">
    <div class="card-header text-center">
        <h4 class="mb-0">TAGIHAN INTERNET</h4>
        <small>JRC MEDIA ID</small>
    </div>
    <div class="card-body">

        {{-- Form Cari --}}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">CARI ID / NAMA PELANGGAN</label>
            <div class="col-sm-6">
                <input type="text" class="form-control">
            </div>
            <div class="col-sm-3 d-flex">
                <button class="btn btn-primary mr-2">CARI</button>
                <button class="btn btn-secondary">BARCODE</button>
            </div>
        </div>

        {{-- Informasi Pelanggan --}}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">NAMA PELANGGAN</label>
            <div class="col-sm-3">
                <input type="text" class="form-control">
            </div>
            <label class="col-sm-3 col-form-label">ID PELANGGAN</label>
            <div class="col-sm-3">
                <input type="text" class="form-control">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">ALAMAT</label>
            <div class="col-sm-3">
                <input type="text" class="form-control">
            </div>
            <label class="col-sm-3 col-form-label">INSTALASI</label>
            <div class="col-sm-3">
                <input type="text" class="form-control">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">HARGA PAKET</label>
            <div class="col-sm-3">
                <input type="text" class="form-control">
            </div>
        </div>

        {{-- Tahun --}}
        <h5 class="text-center my-4">TAHUN 2025</h5>

        {{-- Grid Bulan --}}
        <div class="row text-center">
            @foreach
            (['JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DESEMBER']
            as $bulan)
            <div class="col-3 mb-3">
                <div class="p-3 bg-danger text-white font-weight-bold rounded">
                    {{ $bulan }}
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>