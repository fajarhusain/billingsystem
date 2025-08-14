@extends('layouts.app')

@section('title', 'Daftar Paket Internet')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-box mr-2"></i>
                Daftar Paket Internet
            </h3>
            <a href="{{ route('packages.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i>
                Tambah Paket Baru
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Paket</th>
                            <th>Tipe</th>
                            <th>Kecepatan</th>
                            <th>Kuota</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $package->name }}</td>
                            <td>
                                <span class="text-dark">{{ ucfirst($package->type) }}</span>
                            </td>
                            <td>{{ $package->speed_mbps }} Mbps</td>
                            <td>{{ $package->quota }}</td>
                            <td>{{ $package->formatted_price }}</td>
                            <td>
                                <span class="text-dark badge-{{ $package->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($package->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus paket ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data paket.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection