@extends('layouts.app')

@section('title', 'Edit Paket')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Paket: {{ $package->name }}
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('packages.update', $package->id) }}" method="POST" id="packageForm">
                        @csrf
                        @method('PUT')

                        <!-- Alert Error Validasi -->
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Nama Paket -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Paket <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $package->name) }}"
                                           placeholder="Contoh: Paket Internet 50Mbps" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tipe Paket -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Tipe Paket <span class="text-danger">*</span></label>
                                    <select name="type" id="type" 
                                            class="form-control @error('type') is-invalid @enderror" required>
                                        <option value="">Pilih Tipe Paket</option>
                                        <option value="home" {{ old('type', $package->type) == 'home' ? 'selected' : '' }}>Home</option>
                                        <option value="business" {{ old('type', $package->type) == 'business' ? 'selected' : '' }}>Business</option>
                                        <option value="corporate" {{ old('type', $package->type) == 'corporate' ? 'selected' : '' }}>Corporate</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Kecepatan -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="speed_mbps">Kecepatan (Mbps) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="speed_mbps" id="speed_mbps" 
                                               class="form-control @error('speed_mbps') is-invalid @enderror" 
                                               value="{{ old('speed_mbps', $package->speed_mbps) }}"
                                               placeholder="50" min="1" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Mbps</span>
                                        </div>
                                    </div>
                                    @error('speed_mbps')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kuota -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="quota">Kuota <span class="text-danger">*</span></label>
                                    <input type="text" name="quota" id="quota" 
                                           class="form-control @error('quota') is-invalid @enderror" 
                                           value="{{ old('quota', $package->quota) }}"
                                           placeholder="Contoh: Unlimited" required>
                                    @error('quota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Harga -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">Harga per Bulan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" name="price" id="price" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               value="{{ old('price', $package->price) }}"
                                               placeholder="300000" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" 
                                            class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status', $package->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status', $package->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Deskripsi fitur paket...">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="form-group text-right mt-4">
                            <a href="{{ route('packages.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-times mr-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save mr-1"></i> Update Paket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .form-group label {
        font-weight: 600;
    }
    
    .form-control:focus, .custom-select:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    
    .input-group-text {
        background-color: #f8f9fa;
    }
    
    .btn-warning {
        color: #212529;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Format input harga (hanya angka)
    $('#price').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Validasi form sebelum submit
    $('#packageForm').submit(function() {
        let isValid = true;
        
        // Validasi kecepatan minimal 1 Mbps
        if ($('#speed_mbps').val() < 1) {
            $('#speed_mbps').addClass('is-invalid');
            $('#speed_mbps').next('.invalid-feedback').text('Kecepatan minimal 1 Mbps');
            isValid = false;
        }
        
        // Validasi harga minimal 1000
        if ($('#price').val() < 1000) {
            $('#price').addClass('is-invalid');
            $('#price').next('.invalid-feedback').text('Harga minimal Rp 1.000');
            isValid = false;
        }
        
        return isValid;
    });

    // Hapus kelas invalid saat user mulai mengisi
    $('input, select').on('input change', function() {
        if ($(this).hasClass('is-invalid')) {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
@endpush