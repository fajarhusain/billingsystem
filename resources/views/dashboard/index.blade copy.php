@extends('layouts.app')

@section('title', 'Dashboard - Sistem Penagihan Internet')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
    <small class="text-muted">{{ now()->format('d F Y') }}</small>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_customers'] }}</div>
            <div>Total Pelanggan</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['active_customers'] }}</div>
            <div>Pelanggan Aktif</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</div>
            <div>Pendapatan Bulan Ini</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['unpaid_invoices'] }}</div>
            <div>Tagihan Belum Bayar</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Invoices -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Tagihan Terbaru</h5>
                <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($recentInvoices->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Pelanggan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Jatuh Tempo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentInvoices as $invoice)
                            <tr>
                                <td>
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-decoration-none">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td>{{ $invoice->customer->name }}</td>
                                <td>{{ $invoice->formatted_amount }}</td>
                                <td>{!! $invoice->status_badge !!}</td>
                                <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-file-invoice fa-3x mb-3"></i>
                    <p>Belum ada tagihan</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('customers.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Tambah Pelanggan
                    </a>
                    <a href="{{ route('packages.create') }}" class="btn btn-secondary">
                        <i class="fas fa-box me-2"></i>Tambah Paket
                    </a>
                    <a href="{{ route('invoices.create') }}" class="btn btn-success">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Buat Tagihan
                    </a>

                    <!-- Generate Monthly Invoices -->
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#generateModal">
                        <i class="fas fa-calendar-alt me-2"></i>Generate Tagihan Bulanan
                    </button>
                </div>

                <!-- Alert untuk overdue -->
                @if($stats['overdue_invoices'] > 0)
                <div class="alert alert-danger mt-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>{{ $stats['overdue_invoices'] }}</strong> tagihan sudah jatuh tempo!
                    <a href="{{ route('invoices.index', ['status' => 'overdue']) }}" class="alert-link">Lihat detail</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Generate Monthly Invoices Modal -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('invoices.generateMonthly') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Generate Tagihan Bulanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="period" class="form-label">Periode</label>
                        <input type="month" class="form-control" id="period" name="period"
                            value="{{ now()->format('Y-m') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Jatuh Tempo</label>
                        <input type="date" class="form-control" id="due_date" name="due_date"
                            value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection