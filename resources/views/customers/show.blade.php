@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header text-center bg-info text-white">
            <h4 class="mb-0">DETAIL PELANGGAN</h4>
            <small>{{ $customer->name }}</small>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                {{-- Info Pelanggan --}}
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
                            <th>QR Code</th>
                            <td class="text-center">
                                {!! QrCode::size(150)->generate($customer->unique_code) !!}

                            </td>
                        </tr>

                        </tr>
                    </table>
                </div>

                {{-- Info Paket --}}
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Paket</th>
                            <td>{{ $customer->package->name }}<br>
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
                            <th>Catatan</th>
                            <td>{{ $customer->notes }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- {{-- History Tagihan --}}
            <div class="card mt-4">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">HISTORY TAGIHAN TAHUN {{ now()->format('Y') }}</h4>
                </div>

                {{-- Grid Bulan --}}
                <div class="card-body">
                    <div class="row text-center">
                        @php
                        $months = [
                        '01'=>'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL',
                        '05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS',
                        '09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER'
                        ];

                        $year = now()->format('Y');
                        $customerInvoices = $customer->invoices
                        ->filter(fn($inv) => str_starts_with($inv->period, $year.'-'))
                        ->keyBy(fn($inv) => \Carbon\Carbon::parse($inv->period.'-01')->format('m'));
                        @endphp

                        @foreach($months as $num => $namaBulan)
                        @php
                        $inv = $customerInvoices[$num] ?? null;
                        if(!$inv) {
                        $bg = 'bg-white border';
                        $textColor = 'text-dark';
                        $btnType = 'alert';
                        } elseif($inv->status === 'paid') {
                        $bg = 'bg-success';
                        $textColor = 'text-white';
                        $btnType = 'print';
                        } else {
                        $bg = 'bg-danger';
                        $textColor = 'text-white';
                        $btnType = 'modal';
                        }
                        @endphp

                        <div class="col-6 col-sm-4 col-md-3 mb-3">
                            @if($btnType === 'print')
                            <button type="button"
                                class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }} btn w-100"
                                onclick="confirmPrint('{{ $inv->id }}')">{{ $namaBulan }}</button>
                            @elseif($btnType === 'modal')
                            <button type="button"
                                class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }} btn w-100"
                                data-bs-toggle="modal" data-bs-target="#paymentModal" data-invoice-id="{{ $inv->id }}"
                                data-invoice-number="{{ $inv->invoice_number }}"
                                data-customer-name="{{ $customer->name }}" data-invoice-amount="{{ $inv->amount }}">
                                {{ $namaBulan }}
                            </button>
                            @else
                            <button type="button"
                                class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }} btn w-100"
                                onclick="alert('Tagihan untuk bulan {{ $namaBulan }} belum dibuat, hubungi admin!')">
                                {{ $namaBulan }}
                            </button>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- Legend --}}
                    <div class="mt-4">
                        <h5>Keterangan:</h5>
                        <table class="table table-bordered w-50 mx-auto">
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
            </div> -->

            {{-- History Tagihan --}}
            <div class="card mt-4">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">HISTORY TAGIHAN TAHUN {{ now()->format('Y') }}</h4>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="text-dark text-center bg-light">

                            <tr>
                                <th>Bulan</th>
                                <th>Periode</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Jatuh Tempo</th>
                                <th>Tanggal Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $months = [
                            '01'=>'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL',
                            '05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS',
                            '09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER'
                            ];

                            $year = now()->format('Y');
                            $customerInvoices = $customer->invoices
                            ->filter(fn($inv) => str_starts_with($inv->period, $year.'-'))
                            ->keyBy(fn($inv) => \Carbon\Carbon::parse($inv->period.'-01')->format('m'));
                            @endphp

                            @foreach($months as $num => $namaBulan)
                            @php
                            $inv = $customerInvoices[$num] ?? null;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $namaBulan }}</td>
                                <td class="text-center">{{ $inv?->period ?? '-' }}</td>
                                <td class="text-end">
                                    {{ $inv ? 'Rp '.number_format($inv->amount,0,',','.') : '-' }}
                                </td>
                                <td class="text-center">
                                    @if(!$inv)
                                    <span class="badge bg-secondary">BELUM ADA</span>
                                    @elseif($inv->status === 'paid')
                                    <span class="badge bg-success">LUNAS</span>
                                    @else
                                    <span class="badge bg-danger">BELUM BAYAR</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $inv?->due_date ?? '-' }}</td>
                                <td class="text-center">{{ $inv?->paid_at ?? '-' }}</td>
                                <td class="text-center">
                                    @if($inv && $inv->status === 'paid')
                                    <a href="{{ route('invoices.print', $inv->id) }}" target="_blank"
                                        class="btn btn-sm btn-success">
                                        Print
                                    </a>
                                    @endif

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            {{-- Tombol aksi --}}
            <div class="mt-4 text-center">
                <!-- <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i> Edit
                </a> -->
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

        </div>
    </div>
</div>

{{-- Modal Payment --}}
@include('invoices.payment_modal')


@endsection

@section('scripts')
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

document.addEventListener('DOMContentLoaded', function() {
    var paymentModal = document.getElementById('paymentModal');
    paymentModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var invoiceId = button.getAttribute('data-invoice-id');
        var invoiceNumber = button.getAttribute('data-invoice-number');
        var customerName = button.getAttribute('data-customer-name');
        var amount = button.getAttribute('data-invoice-amount');

        // Update modal content
        paymentModal.querySelector('#modalInvoiceNumber').textContent = invoiceNumber;
        paymentModal.querySelector('#modalCustomerName').textContent = customerName;
        paymentModal.querySelector('#modalInvoiceAmount').textContent = 'Rp ' + Number(amount)
            .toLocaleString('id-ID');

        // Set form action untuk patch markAsPaid
        paymentModal.querySelector('#paymentForm').action = '/invoices/' + invoiceId + '/mark-as-paid';
    });
});
</script>
@endsection