@extends('layouts.app')

@section('title', 'Dashboard - Sistem Penagihan Internet')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold text-success"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
    <small class="text-muted">{{ now()->translatedFormat('d F Y') }}</small>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 bg-success text-white rounded-3">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-users fa-2x me-3"></i>
                <div>
                    <h4 class="mb-0">{{ $stats['total_customers'] }}</h4>
                    <small>Total Pelanggan</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 bg-info-subtle rounded-3 text-dark">

            <div class="card-body d-flex align-items-center">
                <i class="fas fa-user-check fa-2x me-3 text-success"></i>
                <div>
                    <h4 class="mb-0 fw-bold">{{ $stats['active_customers'] }}</h4>
                    <small class="text-muted">Pelanggan Aktif</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 bg-primary text-white rounded-3">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-wallet fa-2x me-3"></i>
                <div>
                    <h4 class="mb-0">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</h4>
                    <small>Pendapatan Bulan Ini</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 bg-danger text-white rounded-3">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                <div>
                    <h4 class="mb-0">{{ $stats['unpaid_invoices'] }}</h4>
                    <small>Tagihan Belum Bayar</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Invoices -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-file-invoice me-2 text-success"></i>Tagihan Terbaru</h5>
                <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($recentInvoices->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-success">
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
                                    <a href="{{ route('invoices.show', $invoice) }}" class="fw-semibold text-success">
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
                    <i class="fas fa-file-invoice fa-3x mb-3 text-success"></i>
                    <p>Belum ada tagihan</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('customers.create') }}" class="btn btn-success w-100 py-3">
                            <i class="fas fa-user-plus fa-lg d-block mb-2"></i>Tambah<br>Pelanggan
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('packages.create') }}" class="btn btn-info w-100 py-3">
                            <i class="fas fa-box fa-lg d-block mb-2"></i>Tambah<br>Paket
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('invoices.create') }}" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-file-invoice-dollar fa-lg d-block mb-2"></i>Buat<br>Tagihan
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-warning w-100 py-3" data-bs-toggle="modal"
                            data-bs-target="#generateModal">
                            <i class="fas fa-calendar-alt fa-lg d-block mb-2"></i>Generate<br>Bulanan
                        </button>
                    </div>
                </div>

                @if($stats['overdue_invoices'] > 0)
                <div class="alert alert-danger mt-3 mb-0" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>{{ $stats['overdue_invoices'] }}</strong> tagihan sudah jatuh tempo!
                    <a href="{{ route('invoices.index', ['status' => 'overdue']) }}" class="alert-link">Lihat detail</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>



@endsection