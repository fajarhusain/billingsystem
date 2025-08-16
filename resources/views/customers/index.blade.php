@extends('layouts.app')

@section('title', 'Daftar Pelanggan')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">
                <i class="fas fa-users mr-2"></i>
                Daftar Pelanggan
            </h3>
            <div class="card-tools">
                <a href="{{ route('customers.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Baru
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
            @endif

            {{-- Filter dan Pencarian Nama --}}
            <div class="mb-3">
                <form method="GET" action="{{ route('customers.index') }}" class="form-inline">
                    <label class="mr-2">Nama:</label>
                    <input type="text" name="search" class="form-control mr-2" placeholder="Cari Nama..."
                        value="{{ request('search') }}">

                    <label class="mr-2">Dusun:</label>
                    <select name="dusun" class="form-control mr-2">
                        <option value="">Semua</option>
                        @foreach(['Rumasan','Rimalang','Semangeng','Manganan','Pedoyo'] as $dusun)
                        <option value="{{ $dusun }}" {{ request('dusun')==$dusun?'selected':'' }}>{{ $dusun }}</option>
                        @endforeach
                    </select>

                    <label class="mr-2">Status:</label>
                    <select name="status" class="form-control mr-2">
                        <option value="">Semua</option>
                        <option value="active" {{ request('status')=='active'?'selected':'' }}>Aktif</option>
                        <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Nonaktif</option>
                    </select>

                    <button type="submit" class="btn btn-primary btn-sm">Terapkan</button>
                </form>
            </div>

            {{-- Pilihan Per Halaman --}}
            <div class="mb-2 d-flex justify-content-end">
                <form method="GET" action="{{ route('customers.index') }}" class="form-inline">
                    {{-- Pertahankan filter & pencarian --}}
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
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Paket</th>
                            <th>Dusun</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr style="cursor:pointer"
                            onclick="window.location='{{ route('customers.show', $customer->id) }}'">
                            <td>{{ $loop->iteration + ($customers->currentPage()-1)*$customers->perPage() }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->package->name }} ({{ $customer->package->speed_mbps }} Mbps)</td>
                            <td>{{ $customer->dusun }}</td>
                            <td>{{ $customer->phone }}<br><small>{{ $customer->email }}</small></td>
                            <td>
                                <span style="color: black;">
                                    {!! strip_tags($customer->status_badge) !!}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus pelanggan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data pelanggan</td>
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