@extends('layouts.app')

@section('title', 'Tagihan - Sistem Penagihan Internet')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-invoice-dollar me-2"></i>Tagihan</h1>
    <div>
        <a href="{{ route('invoices.export') }}" class="btn btn-success me-2">
            <i class="fas fa-download me-2"></i>Export CSV
        </a>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Buat Tagihan
        </a>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('invoices.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari invoice atau pelanggan...">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="status">
                        <option value="">Semua Status</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="period" 
                           value="{{ request('period') }}" 
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
                                    <a href="{{ route('invoices.show', $invoice) }}" 
                                       class="text-decoration-none fw-bold">
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
                                        <a href="{{ route('invoices.show', $invoice) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($invoice->status !== 'paid')
                                            <button type="button" class="btn btn-outline-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#paymentModal{{ $invoice->id }}">
                                                <i class="fas fa-credit-card"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Payment Modal for each invoice -->
                            @if($invoice->status !== 'paid')
                                <div class="modal fade" id="paymentModal{{ $invoice->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('invoices.mark-as-paid', $invoice) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Invoice:</strong> {{ $invoice->invoice_number }}</p>
                                                    <p><strong>Pelanggan:</strong> {{ $invoice->customer->name }}</p>
                                                    <p><strong>Jumlah:</strong> {{ $invoice->formatted_amount }}</p>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Tanggal Pembayaran</label>
                                                        <input type="date" class="form-control" name="payment_date" 
                                                               value="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Metode Pembayaran</label>
                                                        <select class="form-control" name="payment_method" required>
                                                            <option value="cash">Tunai</option>
                                                            <option value="transfer">Transfer Bank</option>
                                                            <option value="e-wallet">E-Wallet</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">No. Referensi</label>
                                                        <input type="text" class="form-control" name="reference_number" 
                                                               placeholder="Opsional">
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Catatan</label>
                                                        <textarea class="form-control" name="notes" rows="2" 
                                                                  placeholder="Catatan tambahan (opsional)"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check me-2"></i>Konfirmasi Pembayaran
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
@endsection