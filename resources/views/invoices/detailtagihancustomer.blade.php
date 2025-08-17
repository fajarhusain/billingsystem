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
                <!-- Info Pelanggan -->
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
                                {!! QrCode::size(150)->generate(route('invoices.detailtagihancustomer', ['customer' =>
                                $customer->id])) !!}
                            </td>
                        </tr>


                    </table>
                </div>
                <!-- Info Paket -->
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

            <div class="card mt-4">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">HISTORY TAGIHAN TAHUN {{ now()->format('Y') }}</h4>
                </div>
                <div class="card-body">
                    @php
                    $months = [
                    '01'=>'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL',
                    '05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS',
                    '09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER'
                    ];

                    // Ambil invoice untuk tahun ini
                    $yearInvoices = $customer->invoices->filter(fn($inv) => str_starts_with($inv->period,
                    now()->format('Y-')))
                    ->keyBy(fn($inv) => \Carbon\Carbon::parse($inv->period.'-01')->format('m'));

                    // Ambil log pembayaran terakhir per bulan
                    $history = collect();
                    foreach($yearInvoices as $month => $inv) {
                    $lastPayment = $inv->payments->sortByDesc('created_at')->first();
                    $history[$month] = $lastPayment ?? $inv;
                    }
                    @endphp

                    <div class="row text-center">
                        @foreach($months as $num => $namaBulan)
                        @php
                        $entry = $history[$num] ?? null;
                        if(!$entry) { $bg='bg-white border'; $textColor='text-dark'; $btnType='alert'; }
                        elseif(isset($entry->status) && $entry->status==='paid') { $bg='bg-success';
                        $textColor='text-white'; $btnType='print'; }
                        elseif(isset($entry->amount)) { $bg='bg-danger'; $textColor='text-white'; $btnType='modal'; }
                        @endphp

                        <div class="col-6 col-sm-4 col-md-3 mb-3">
                            @if($btnType==='print')
                            <button type="button"
                                class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }} btn w-100"
                                onclick="confirmPrint('{{ $entry->id }}')">
                                {{ $namaBulan }}
                            </button>
                            @elseif($btnType==='modal')
                            <button type="button"
                                class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }} btn w-100"
                                data-bs-toggle="modal" data-bs-target="#paymentModal" data-invoice-id="{{ $entry->id }}"
                                data-invoice-number="{{ $entry->invoice_number ?? '' }}"
                                data-customer-name="{{ $customer->name }}"
                                data-invoice-amount="{{ $entry->amount ?? 0 }}">
                                {{ $namaBulan }}
                            </button>
                            @else
                            <button type="button"
                                class="p-3 font-weight-bold rounded {{ $bg }} {{ $textColor }} btn w-100"
                                onclick="alert('Tagihan untuk bulan {{ $namaBulan }} belum ada!')">
                                {{ $namaBulan }}
                            </button>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- Legend --}}
                    <div class="mt-4 text-center">
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
            </div>

        </div>
    </div>

    {{-- Modal Payment --}}
    @include('invoices.payment_modal')

    @endsection

    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal payment
        var paymentModal = document.getElementById('paymentModal');
        paymentModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var invoiceId = button.getAttribute('data-invoice-id');
            var invoiceNumber = button.getAttribute('data-invoice-number');
            var customerName = button.getAttribute('data-customer-name');
            var amount = button.getAttribute('data-invoice-amount');

            paymentModal.querySelector('#modalInvoiceId').textContent = invoiceNumber;
            paymentModal.querySelector('#modalCustomerName').textContent = customerName;
            paymentModal.querySelector('#modalInvoiceAmount').textContent = 'Rp ' + Number(amount)
                .toLocaleString('id-ID');
            paymentModal.querySelector('#paymentForm').action = '/invoices/' + invoiceId +
                '/mark-as-paid';
        });
    });

    // Cetak struk
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
    @endsection