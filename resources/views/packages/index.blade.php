@extends('layouts.app')

@section('title', 'Daftar Paket Internet')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-box me-2"></i> Paket Internet</span>
            <a href="{{ route('packages.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Nama Paket</th>
                            <th>Tipe</th>
                            <th>Kecepatan</th>
                            <th>Kuota</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $package->name }}</td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">{{ ucfirst($package->type) }}</span>
                            </td>
                            <td class="text-center">{{ $package->speed_mbps }} Mbps</td>
                            <td class="text-center">{{ $package->quota }}</td>
                            <td>{{ $package->formatted_price }}</td>
                            <td class="text-center">
                                <span class="badge {{ $package->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($package->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('packages.edit', $package->id) }}"
                                    class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('packages.destroy', $package->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus paket ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data paket.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection