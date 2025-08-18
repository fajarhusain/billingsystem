@extends('layouts.app')

@section('title', 'Tagihan - Sistem Penagihan Internet')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-file-invoice-dollar me-2"></i> Tagihan
    </h1> -->
    <div>
        <a href="{{ route('invoices.create') }}" class="btn btn-sm btn-primary shadow-sm me-1">
            <i class="fas fa-plus fa-sm text-white-50"></i> Buat Tagihan
        </a>



        <a href="{{ route('pindaiqr.index') }}" class="btn btn-sm btn-success shadow-sm">
            <i class="fas fa-qrcode fa-sm text-white-50"></i> Pindai QR
        </a>
    </div>
</div>

<!-- Filter & Search -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-filter me-2"></i> Filter Data
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('invoices.index') }}">
            <div class="row g-3 align-items-end">

                <!-- Pencarian -->
                <div class="col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                        placeholder="Cari pelanggan atau invoice ...">
                </div>

                <!-- Filter Dusun -->
                <div class="col-md-2">
                    <label class="form-label">Dusun</label>
                    <select class="form-control" name="dusun">
                        @foreach($dusuns as $key => $name)
                        <option value="{{ $key }}" {{ request('dusun') == $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tahun -->
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <select class="form-control" name="year">
                        @php $currentYear = date('Y'); @endphp
                        @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                        @endfor
                    </select>
                </div>

                <!-- Filter Bulan -->
                <div class="col-md-2">
                    <label class="form-label">Bulan</label>
                    <select class="form-control" name="month">
                        <option value="">Semua Bulan</option>
                        @php
                        $months = [
                        '01'=>'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL',
                        '05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS',
                        '09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER'
                        ];
                        @endphp
                        @foreach($months as $num => $namaBulan)
                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                            {{ $namaBulan }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-control" name="status">
                        <option value="">Semua</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar
                        </option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Lewat Tempo
                        </option>
                    </select>
                </div>

                <!-- Tombol -->
                <div class="col-md-3 d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>









<!-- Invoices Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-table me-2"></i> Data Tagihan
        </h6>
    </div>
    <div class="card-body">
        @if($invoices->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No. Invoice</th>
                        <th>Pelanggan</th>
                        <th>Dusun</th>
                        <th>Paket</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr class="{{ $invoice->is_overdue ? 'table-warning' : '' }}">
                        <td>
                            <a href="{{ route('invoices.show', $invoice) }}"
                                class="fw-bold text-primary text-decoration-none">
                                {{ $invoice->invoice_number }}
                            </a>
                        </td>
                        <td>{{ $invoice->customer->name }}</td>
                        <td>{{ $dusuns[$invoice->customer->dusun] ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $invoice->customer->package->name }}</span>
                        </td>
                        <td>{{ $invoice->period }}</td>
                        <td>{{ $invoice->formatted_amount }}</td>
                        <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                        <td>{!! $invoice->status_badge !!}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <!-- Detail Invoice -->
                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary"
                                    title="Lihat Invoice">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Tombol Bayar -->
                                @if($invoice->status !== 'paid')
                                <button type="button" class="btn btn-sm btn-outline-success payment-btn"
                                    data-invoice-id="{{ $invoice->id }}" data-bs-toggle="modal"
                                    data-bs-target="#paymentModal" title="Konfirmasi Pembayaran">
                                    <i class="fas fa-credit-card"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $invoices->links() }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
            <h5>Tidak ada data tagihan</h5>
            <p>Mulai dengan membuat tagihan baru</p>
            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Buat Tagihan
            </a>
        </div>
        @endif
    </div>
</div>




<!-- Single Payment Modal (Reusable) -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form id="paymentForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-success text-white rounded-top">
                    <h5 class="modal-title fw-bold" id="paymentModalLabel">
                        <i class="fas fa-receipt me-2"></i>Konfirmasi Pembayaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <p class="mb-1"><strong>Invoice:</strong> <span class="text-primary"
                                id="modalInvoiceNumber"></span></p>
                        <p class="mb-1"><strong>Pelanggan:</strong> <span id="modalCustomerName"></span></p>
                        <p class="mb-3"><strong>Jumlah:</strong> <span class="badge bg-success fs-6"
                                id="modalInvoiceAmount"></span></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fas fa-calendar-day me-1"></i> Tanggal
                            Pembayaran</label>
                        <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fas fa-money-check-alt me-1"></i> Metode
                            Pembayaran</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fas fa-hashtag me-1"></i> No. Referensi</label>
                        <input type="text" class="form-control" name="reference_number" placeholder="Opsional">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fas fa-sticky-note me-1"></i> Catatan</label>
                        <textarea class="form-control" name="notes" rows="2"
                            placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-check-circle me-1"></i> Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle payment button clicks
    document.querySelectorAll('.payment-btn').forEach(button => {
        button.addEventListener('click', function() {
            const invoiceId = this.getAttribute('data-invoice-id');
            const invoiceRow = this.closest('tr');

            // Get invoice details from the table row
            const invoiceNumber = invoiceRow.querySelector('td:first-child a').textContent;
            const customerName = invoiceRow.querySelector('td:nth-child(2)').textContent;
            const invoiceAmount = invoiceRow.querySelector('td:nth-child(5)').textContent;

            // Set modal content and form action
            document.getElementById('modalInvoiceNumber').textContent = invoiceNumber;
            document.getElementById('modalCustomerName').textContent = customerName;
            document.getElementById('modalInvoiceAmount').textContent = invoiceAmount;

            // Set the form action URL
            document.getElementById('paymentForm').action =
                `/invoices/${invoiceId}/mark-as-paid`;
        });
    });
});
</script>
@endpush

@endsection