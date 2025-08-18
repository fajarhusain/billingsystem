@extends('layouts.app')

@section('title', 'Daftar Pelanggan')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-list mr-2"></i> Data Pelanggan
            </h6>
            <a href="{{ route('customers.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Baru
            </a>
        </div>

        <div class="card-body">
            {{-- Notifikasi sukses --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- Filter dan Pencarian Nama --}}
            <form method="GET" action="{{ route('customers.index') }}" class="form-inline mb-3">
                <div class="form-group mr-2">
                    <label class="mr-2">Nama:</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari Nama..."
                        value="{{ request('search') }}">
                </div>

                <div class="form-group mr-2">
                    <label class="mr-2">Dusun:</label>
                    <select name="dusun" class="form-control form-control-sm">
                        <option value="">Semua</option>
                        @foreach(['Rumasan','Rimalang','Semangeng','Manganan','Pedoyo'] as $dusun)
                        <option value="{{ $dusun }}" {{ request('dusun')==$dusun?'selected':'' }}>{{ $dusun }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mr-2">
                    <label class="mr-2">Status:</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua</option>
                        <option value="active" {{ request('status')=='active'?'selected':'' }}>Aktif</option>
                        <option value="suspended" {{ request('status')=='suspended'?'selected':'' }}>Ditangguhkan
                        </option>
                        <option value="terminated" {{ request('status')=='terminated'?'selected':'' }}>Berhenti
                        </option>
                    </select>

                </div>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-filter mr-1"></i> Terapkan
                </button>
            </form>

            {{-- Pilihan Per Halaman --}}
            <div class="mb-2 d-flex justify-content-end">
                <form method="GET" action="{{ route('customers.index') }}" class="form-inline">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="dusun" value="{{ request('dusun') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <label class="mr-2">Per Halaman:</label>
                    <select name="perPage" class="form-control form-control-sm" onchange="this.form.submit()">
                        @foreach([10,50,100,500] as $size)
                        <option value="{{ $size }}" {{ $perPage==$size?'selected':'' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Tabel Pelanggan --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Paket</th>
                            <th>Dusun</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr onclick="window.location='{{ route('customers.show', $customer->id) }}'"
                            style="cursor:pointer">
                            <td>{{ $loop->iteration + ($customers->currentPage()-1)*$customers->perPage() }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->package->name }} ({{ $customer->package->speed_mbps }} Mbps)</td>
                            <td>{{ $customer->dusun }}</td>
                            <td>
                                {{ $customer->phone }}<br>
                                <small class="text-muted">{{ $customer->email }}</small>
                            </td>
                            <td>{!! $customer->status_badge !!}</td>
                            <td class="text-center">
                                <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-info btn-sm"><i
                                        class="fas fa-eye"></i></a>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm"><i
                                        class="fas fa-edit"></i></a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus pelanggan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data pelanggan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }} dari
                    {{ $customers->total() }} pelanggan
                </div>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection