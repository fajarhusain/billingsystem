@extends('layouts.app')

@section('title', 'Detail Tagihan - ' . $invoice->customer->name)

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header text-center">
            <h4 class="mb-0">TAGIHAN INTERNET</h4>
            <small>JRC MEDIA ID</small>
        </div>
        <div class="card-body">

            {{-- Informasi Pelanggan --}}
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nama Pelanggan</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" value="{{ $invoice->customer->name }}" readonly>
                </div>
                <label class="col-sm-3 col-form-label">ID Pelanggan</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" value="{{ $invoice->customer->id }}" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Alamat</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" value="{{ $invoice->customer->address ?? '-' }}" readonly>
                </div>
                <label class="col-sm-3 col-form-label">Instalasi</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" value="{{ $invoice->customer->installation ?? '-' }}"
                        readonly>
                </div>
            </div>

            <div class="row mb-4">
                <label class="col-sm-3 col-form-label">Harga Paket</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control"
                        value="Rp {{ number_format($invoice->customer->package->price ?? 0, 0, ',', '.') }}" readonly>
                </div>
            </div>

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
                return \Carbon\Carbon::parse($inv->period . '-01')->format('m');
                })
                : collect();
                @endphp

                @foreach($months as $num => $namaBulan)
                @php
                $inv = $customerInvoices[$num] ?? null;
                if (!$inv) {
                $bg = 'bg-white border'; // Tagihan belum dibuat
                $textColor = 'text-dark';
                } elseif ($inv->status === 'paid') {
                $bg = 'bg-success'; // Lunas
                $textColor = 'text-white';
                } else {
                $bg = 'bg-danger'; // Belum bayar
                $textColor = 'text-white';
                }
                @endphp

                <div class="col-6 col-sm-4 col-md-3 mb-3">
                    @if($inv && $inv->status === 'paid')
                    <button type="button" class="btn w-100 p-3 rounded {{ $bg }} {{ $textColor }}"
                        onclick="confirmPrint('{{ $inv->id }}')">
                        {{ $namaBulan }}
                    </button>
                    {{-- Tombol Belum Bayar --}}
                    @elseif($inv && $inv->status !== 'paid')
                    <button type="button" class="btn w-100 p-3 rounded {{ $bg }} {{ $textColor }}"
                        onclick="confirmPayment('{{ $inv->id }}', '{{ $inv->invoice_number }}', '{{ $inv->customer->name }}', '{{ $inv->amount }}')">
                        {{ $namaBulan }}
                    </button>

                    @else
                    <button type="button" class="btn w-100 p-3 rounded {{ $bg }} {{ $textColor }}"
                        onclick="alert('Tagihan untuk bulan {{ $namaBulan }} belum dibuat!')">
                        {{ $namaBulan }}
                    </button>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Legend --}}
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
                        <td>Belum dibuat</td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>

@include('payment_modal')



{{-- SweetAlert --}}
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
<script>
function confirmPayment(invoiceId, invoiceNumber, customerName, amount) {
    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        html: `
            <p>Tagihan <b>${invoiceNumber}</b> milik <b>${customerName}</b></p>
            <p>Jumlah: <b>Rp ${Number(amount).toLocaleString('id-ID')}</b></p>
            <p>Apakah Anda ingin melakukan pembayaran sekarang?</p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Bayar',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Munculkan modal pembayaran dengan data dari invoice
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            const paymentModal = document.getElementById('paymentModal');
            paymentModal.querySelector('[name="invoice_id"]').value = invoiceId;
            paymentModal.querySelector('.invoice-number').textContent = invoiceNumber;
            paymentModal.querySelector('.customer-name').textContent = customerName;
            paymentModal.querySelector('.invoice-amount').textContent =
                'Rp ' + Number(amount).toLocaleString('id-ID');
            modal.show();
        }
    });
}
</script>



@endsection