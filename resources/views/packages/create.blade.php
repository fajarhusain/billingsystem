@extends('layouts.app')

@section('title', 'Tambah Paket Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('packages.index') }}">
                            <i class="fas fa-box mr-1"></i>Paket Layanan
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Tambah Paket Baru</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Paket Layanan Baru
                    </h3>
                </div>

                <form action="{{ route('packages.store') }}" method="POST" id="packageForm">
                    @csrf
                    <div class="card-body">
                        <!-- Alert untuk error -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <strong>Terjadi kesalahan:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <!-- Nama Paket -->
                                <div class="form-group">
                                    <label for="name" class="form-label required">
                                        <i class="fas fa-tag mr-1"></i>Nama Paket
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Contoh: Paket Home 10 Mbps"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tipe Paket -->
                                <div class="form-group">
                                    <label for="type" class="form-label required">
                                        <i class="fas fa-layer-group mr-1"></i>Tipe Paket
                                    </label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Pilih Tipe Paket</option>
                                        <option value="home" {{ old('type') == 'home' ? 'selected' : '' }}>
                                            Home - Untuk Rumahan
                                        </option>
                                        <option value="business" {{ old('type') == 'business' ? 'selected' : '' }}>
                                            Business - Untuk Bisnis
                                        </option>
                                        <option value="corporate" {{ old('type') == 'corporate' ? 'selected' : '' }}>
                                            Corporate - Untuk Perusahaan
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Kecepatan -->
                                <div class="form-group">
                                    <label for="speed" class="form-label required">
                                        <i class="fas fa-tachometer-alt mr-1"></i>Kecepatan (Mbps)
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('speed') is-invalid @enderror" 
                                               id="speed" 
                                               name="speed" 
                                               value="{{ old('speed') }}" 
                                               placeholder="10"
                                               min="1"
                                               required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Mbps</span>
                                        </div>
                                        @error('speed')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Masukkan kecepatan internet dalam Megabits per second
                                    </small>
                                </div>

                                <!-- Harga -->
                                <div class="form-group">
                                    <label for="price" class="form-label required">
                                        <i class="fas fa-money-bill-wave mr-1"></i>Harga per Bulan
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               id="price" 
                                               name="price" 
                                               value="{{ old('price') }}" 
                                               placeholder="300000"
                                               min="0"
                                               required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Masukkan harga tanpa titik atau koma (contoh: 300000)
                                    </small>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <!-- Status -->
                                <div class="form-group">
                                    <label for="status" class="form-label required">
                                        <i class="fas fa-toggle-on mr-1"></i>Status
                                    </label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                            <i class="fas fa-check"></i> Aktif
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            <i class="fas fa-times"></i> Tidak Aktif
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="form-group">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left mr-1"></i>Deskripsi
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Masukkan deskripsi paket (opsional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Opsional: Jelaskan fitur atau keunggulan paket ini
                                    </small>
                                </div>

                                <!-- Preview Harga -->
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-eye mr-1"></i>Preview Harga
                                        </h6>
                                        <div id="pricePreview" class="text-success font-weight-bold">
                                            Rp 0
                                        </div>
                                        <small class="text-muted">per bulan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Field yang bertanda <span class="text-danger">*</span> wajib diisi
                                </small>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('packages.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times mr-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save mr-1"></i>Simpan Paket
                                </button>
                            </div>
                        </div>
                    </div>
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
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        border-bottom: none;
    }
    
    .form-label.required::after {
        content: " *";
        color: #dc3545;
    }
    
    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    
    .input-group-text {
        background-color: #f8f9fa;
    }
    
    .breadcrumb {
        background-color: transparent;
        padding: 0.5rem 0;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        font-weight: bold;
    }
    
    .alert {
        border: none;
        border-radius: 5px;
    }
    
    #pricePreview {
        font-size: 1.2em;
    }
    
    .bg-light {
        background-color: #f8f9fa!important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Format harga saat mengetik
    $('#price').on('input', function() {
        let price = $(this).val();
        if (price) {
            // Format ke Rupiah
            let formatted = 'Rp ' + parseInt(price).toLocaleString('id-ID');
            $('#pricePreview').text(formatted);
        } else {
            $('#pricePreview').text('Rp 0');
        }
    });
    
    // Auto generate nama paket
    $('#type, #speed').on('change input', function() {
        generatePackageName();
    });
    
    function generatePackageName() {
        let type = $('#type').val();
        let speed = $('#speed').val();
        
        if (type && speed && !$('#name').val()) {
            let typeName = type.charAt(0).toUpperCase() + type.slice(1);
            let suggestedName = `Paket ${typeName} ${speed} Mbps`;
            $('#name').attr('placeholder', suggestedName);
        }
    }
    
    // Validasi form sebelum submit
    $('#packageForm').on('submit', function(e) {
        let isValid = true;
        let errorMessages = [];
        
        // Validasi nama paket
        if (!$('#name').val().trim()) {
            errorMessages.push('Nama paket harus diisi');
            isValid = false;
        }
        
        // Validasi kecepatan
        let speed = parseInt($('#speed').val());
        if (!speed || speed < 1) {
            errorMessages.push('Kecepatan harus lebih dari 0 Mbps');
            isValid = false;
        }
        
        // Validasi harga
        let price = parseInt($('#price').val());
        if (!price || price < 0) {
            errorMessages.push('Harga harus lebih dari atau sama dengan 0');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon perbaiki kesalahan berikut:\n' + errorMessages.join('\n'));
            return false;
        }
        
        // Disable submit button untuk mencegah double submit
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');
    });
    
    // Reset button jika ada error
    if ($('.alert-danger').length > 0) {
        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Simpan Paket');
    }
    
    // Auto-hide alert after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush