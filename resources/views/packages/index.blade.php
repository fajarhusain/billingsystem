@extends('layouts.app')

@section('title', 'Paket Layanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-box mr-2"></i>
                        Daftar Paket Layanan
                    </h3>
                    <a href="{{ route('packages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Paket Baru
                    </a>
                </div>

                <div class="card-body">
                    <!-- Filter dan Search -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="searchPackage" placeholder="Cari paket...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="filterType">
                                <option value="">Semua Tipe</option>
                                <option value="home">Home</option>
                                <option value="business">Business</option>
                                <option value="corporate">Corporate</option>
                            </select>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Packages Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="packagesTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Paket</th>
                                    <th>Tipe</th>
                                    <th>Kecepatan</th>
                                    <th>Harga</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Total Pelanggan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($packages as $index => $package)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $package->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ ucfirst($package->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-tachometer-alt text-primary mr-1"></i>
                                            {{ $package->speed }} Mbps
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                Rp {{ number_format($package->price, 0, ',', '.') }}
                                            </strong>
                                            <small class="text-muted d-block">per bulan</small>
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($package->description, 50) }}</small>
                                        </td>
                                        <td>
                                            @if($package->status === 'active')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i>Aktif
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-times mr-1"></i>Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $package->customers_count ?? 0 }} pelanggan
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('packages.show', $package->id) }}" 
                                                   class="btn btn-info btn-sm" 
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('packages.edit', $package->id) }}" 
                                                   class="btn btn-warning btn-sm" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Hapus"
                                                        onclick="confirmDelete({{ $package->id }}, '{{ $package->name }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-box fa-3x mb-3"></i>
                                                <h5>Belum Ada Paket</h5>
                                                <p>Silakan tambah paket layanan baru</p>
                                                <a href="{{ route('packages.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus mr-1"></i>
                                                    Tambah Paket Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($packages, 'hasPages') && $packages->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $packages->firstItem() }} - {{ $packages->lastItem() }} 
                                dari {{ $packages->total() }} paket
                            </div>
                            <div>
                                {{ $packages->links() }}
                            </div>
                        </div>
                    @elseif(count($packages) > 0)
                        <div class="text-muted mt-3">
                            Total: {{ count($packages) }} paket
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus paket <strong id="packageName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Batal
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border: none;
    }
    
    .card-header {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        border-bottom: none;
    }
    
    .badge {
        font-size: 0.8em;
    }
    
    .btn-group .btn {
        margin: 0 1px;
    }
    
    .table th {
        vertical-align: middle;
        border-top: none;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .alert {
        border: none;
        border-radius: 5px;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchPackage').on('keyup', function() {
        filterTable();
    });
    
    // Filter functionality
    $('#filterStatus, #filterType').on('change', function() {
        filterTable();
    });
    
    function filterTable() {
        const searchTerm = $('#searchPackage').val().toLowerCase();
        const statusFilter = $('#filterStatus').val();
        const typeFilter = $('#filterType').val();
        
        $('#packagesTable tbody tr').each(function() {
            const row = $(this);
            const name = row.find('td:nth-child(2)').text().toLowerCase();
            const type = row.find('td:nth-child(3)').text().toLowerCase();
            const status = row.find('td:nth-child(7)').text().toLowerCase();
            
            let showRow = true;
            
            // Search filter
            if (searchTerm && !name.includes(searchTerm)) {
                showRow = false;
            }
            
            // Status filter
            if (statusFilter && !status.includes(statusFilter)) {
                showRow = false;
            }
            
            // Type filter
            if (typeFilter && !type.includes(typeFilter)) {
                showRow = false;
            }
            
            row.toggle(showRow);
        });
    }
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});

function confirmDelete(packageId, packageName) {
    $('#packageName').text(packageName);
    $('#deleteForm').attr('action', '/packages/' + packageId);
    $('#deleteModal').modal('show');
}
</script>
@endpush