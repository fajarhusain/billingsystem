@extends('layouts.app')

@section('title', 'Laporan Tagihan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-file-alt me-2"></i>Laporan Tagihan</h1>
    <a href="{{ route('reports.export', request()->only('status','month','year')) }}" class="btn btn-success">
        <i class="fas fa-file-excel me-2"></i>Export Excel
    </a>
</div>

<!-- Filter Form -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="unpaid" {{ request('status')=='unpaid'?'selected':'' }}>Belum Bayar</option>
                        <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Lunas</option>
                        <option value="overdue" {{ request('status')=='overdue'?'selected':'' }}>Terlambat</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="month" class="form-select">
                        <option value="">-- Pilih Bulan --</option>
                        @foreach ([
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ] as $num => $name)
                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="year" class="form-select">
                        <option value="">-- Tahun --</option>
                        @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table Data -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light text-center">
                    <tr>
                        <th>No Invoice</th>
                        <th>Pelanggan</th>
                        <th>Paket</th>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr class="text-center">
                        <td>{{ $invoice->invoice_number }}</td>
                        <td class="text-start">{{ $invoice->customer->name }}</td>
                        <td>{{ $invoice->customer->package->name ?? '-' }}</td>
                        <td>{{ $invoice->period }}</td>
                        <td>Rp {{ number_format($invoice->amount,0,',','.') }}</td>
                        <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                        <td>{!! $invoice->status_badge !!}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection