

@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h3 class="card-title">
                <i class="fas fa-user mr-2"></i>
                Detail Pelanggan: {{ $customer->name }}
            </h3>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Nama</th>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $customer->phone }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{!! $customer->status_badge !!}</td>
                        </tr>
                        <tr>
                            <th>QR Code Pelanggan</th>
                            <td class="text-center">
                                <h5>QR Code Pelanggan</h5>
    {!! QrCode::size(200)->generate($customer->id) !!}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Paket</th>
                            <td>
                                {{ $customer->package->name }}<br>
                                <small>{{ $customer->package->speed_mbps }} Mbps -
                                    {{ $customer->package->formatted_price }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Daftar</th>
                            <td>{{ $customer->formatted_registration_date }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $customer->address }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-sticky-note mr-2"></i> Catatan</th>
                            <td>{{ $customer->notes }}</td>
                        </tr>


                          
                    </table>
                </div>
            </div>


            <!-- @if($customer->notes)
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-sticky-note mr-2"></i>
                        Catatan
                    </h4>
                </div>
                <div class="card-body">
                    {{ $customer->notes }}
                </div>
            </div>
            @endif -->

            {{-- History Pembayaran --}}
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-history mr-2"></i>
                        History TAGIHAN
                    </h4>
                </div>
                <div class="card-body">
                    @php
                    $months = [
                    '01'=>'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL',
                    '05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS',
                    '09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER'
                    ];

                    $customerInvoices = $customer->invoices
                    ? $customer->invoices->filter(function($inv) {
                    return str_starts_with($inv->period, now()->format('Y-')); // tahun sekarang
                    })->keyBy(function($inv) {
                    return \Carbon\Carbon::parse($inv->period.'-01')->format('m');
                    })
                    : collect();
                    @endphp

                    <div class="row text-center">
                        @foreach($months as $num => $namaBulan)
                        @php
                        $inv = $customerInvoices[$num] ?? null;
                        if(!$inv) {
                        $bg = 'bg-white border';
                        $textColor = 'text-dark';
                        } elseif($inv->status === 'paid') {
                        $bg = 'bg-success';
                        $textColor = 'text-white';
                        } else {
                        $bg = 'bg-danger';
                        $textColor = 'text-white';
                        }
                        @endphp
                        <div class="col-3 mb-3">
                            @if($inv && $inv->status !== 'paid')
                            <button type="button"
                                class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }} btn w-100"
                                data-bs-toggle="modal" data-bs-target="#paymentModal" data-invoice-id="{{ $inv->id }}"
                                data-invoice-number="{{ $inv->invoice_number }}"
                                data-customer-name="{{ $inv->customer->name }}"
                                data-invoice-amount="{{ $inv->amount }}">
                                {{ $namaBulan }}
                            </button>
                            @elseif(!$inv)
                            <div class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }}">
                                {{ $namaBulan }}
                            </div>
                            @else
                            <div class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }}">
                                {{ $namaBulan }}
                            </div>
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

            <div class="mt-4">
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

        </div>
    </div>
</div>

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