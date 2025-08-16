@extends('layouts.app')

@section('title', 'Tagihan - Sistem Penagihan Internet')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-invoice-dollar me-2"></i>Tagihan</h1>
    <div>

        <a href="{{ route('invoices.create') }}" class="btn btn-primary me-2">
            <i class="fas fa-plus me-2"></i>Buat Tagihan
        </a>

    </div>

</div>

h

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('invoices.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                        placeholder="Cari invoice atau pelanggan...">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="status">
                        <option value="">Semua Status</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar
                        </option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="period" value="{{ request('period') }}"
                        placeholder="MM/YYYY">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-refresh me-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#generateInvoiceModal">
        <i class="fas fa-plus me-2"></i>Generate Tagihan Bulanan
    </button>
</div>






<!-- Invoices Table -->
<div class="card">
    <div class="card-body">
        @if($invoices->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Pelanggan</th>
                        <th>Paket</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr class="{{ $invoice->is_overdue ? 'table-warning' : '' }}">
                        <td>
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-decoration-none fw-bold">
                                {{ $invoice->invoice_number }}
                            </a>
                        </td>
                        <td>{{ $invoice->customer->name }}</td>
                        <td>
                            <span class="badge bg-info">{{ $invoice->customer->package->name }}</span>
                        </td>
                        <td>{{ $invoice->period }}</td>
                        <td>{{ $invoice->formatted_amount }}</td>
                        <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                        <td>{!! $invoice->status_badge !!}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($invoice->status !== 'paid')
                                <button type="button" class="btn btn-outline-success payment-btn"
                                    data-invoice-id="{{ $invoice->id }}" data-bs-toggle="modal"
                                    data-bs-target="#paymentModal">
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
        <div class="d-flex justify-content-center">
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

<div class="modal fade" id="generateInvoiceModal" tabindex="-1" aria-labelledby="generateInvoiceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form action="{{ route('invoices.generateMonthly') }}" method="POST">
                @csrf
                <div class="modal-header bg-warning text-white rounded-top">
                    <h5 class="modal-title fw-bold" id="generateInvoiceModalLabel">
                        <i class="fas fa-plus me-2"></i>Generate Tagihan Bulanan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Periode Tagihan</label>
                        <input type="month" class="form-control" name="period" value="{{ date('Y-m') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jatuh Tempo</label>
                        <input type="date" class="form-control" name="due_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning shadow-sm">
                        <i class="fas fa-check-circle me-1"></i>Generate
                    </button>
                </div>
            </form>
        </div>
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