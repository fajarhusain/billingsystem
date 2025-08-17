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
            if(!$inv) {
            $bg = 'bg-white border'; // Tagihan belum dibuat
            $textColor = 'text-dark';
            } elseif($inv->status === 'paid') {
            $bg = 'bg-success'; // Lunas
            $textColor = 'text-white';
            } else {
            $bg = 'bg-danger'; // Belum bayar
            $textColor = 'text-white';
            }
            @endphp

            <div class="col-6 col-sm-4 col-md-3 mb-3">
                @if($inv && $inv->status === 'paid')
                <button type="button" class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }} btn w-100"
                    onclick="confirmPrint('{{ $inv->id }}')">
                    {{ $namaBulan }}
                </button>
                @elseif($inv && $inv->status !== 'paid')
                <button type="button" class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }} btn w-100"
                    data-bs-toggle="modal" data-bs-target="#paymentModal" data-invoice-id="{{ $inv->id }}"
                    data-invoice-number="{{ $inv->invoice_number }}" data-customer-name="{{ $inv->customer->name }}"
                    data-invoice-amount="{{ $inv->amount }}">
                    {{ $namaBulan }}
                </button>
                @else
                <button type="button" class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }} btn w-100"
                    onclick="alert('Tagihan untuk bulan {{ $namaBulan }} belum dibuat, hubungi admin!')">
                    {{ $namaBulan }}
                </button>
                @endif
            </div>
            @endforeach

        </div>


        {{-- Legend Warna --}}
        <div class="mt-4">
            <h5>Keterangan:</h5>
            <table class="table table-bordered w-50">
                <tr>
                    <td class="bg-success text-white text-center font-weight-bold">LUNAS</td>
                    <td>Dibayar</td>
                </tr>
                <tr>
                    <td class="bg-danger text-white text-center font-weight-bold">BELUM</td>
                    <td>Belum dibayar</td>
                </tr>
                <tr>
                    <td class="bg-white text-center font-weight-bold">BELUM ADA</td>
                    <td>Tagihan belum dibuat</td>
                </tr>
            </table>
        </div>

    </div>
</div>





<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmPrint(invoiceId) {
    Swal.fire({
        title: 'Cetak Struk',
        text: 'Apakah Anda ingin mencetak struk untuk tagihan ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Cetak',
        cancelButtonText: 'Tidak',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/invoices/' + invoiceId + '/print';
        }
    });
}
</script>


{{-- Sertakan modal payment --}}
@include('invoices.payment_modal')

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var paymentModal = document.getElementById('paymentModal')
    paymentModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget
        var invoiceId = button.getAttribute('data-invoice-id')
        var invoiceNumber = button.getAttribute('data-invoice-number')
        var customerName = button.getAttribute('data-customer-name')
        var amount = button.getAttribute('data-invoice-amount')

        // Update modal content
        paymentModal.querySelector('#modalInvoiceNumber').textContent = invoiceNumber
        paymentModal.querySelector('#modalCustomerName').textContent = customerName
        paymentModal.querySelector('#modalInvoiceAmount').textContent = 'Rp ' + amount

        // Set form action untuk patch markAsPaid
        paymentModal.querySelector('#paymentForm').action = '/invoices/' + invoiceId + '/mark-as-paid'
    })
})
</script>
@endsection