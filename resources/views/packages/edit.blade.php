@extends('layouts.app')

@section('title', 'Edit Paket')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-warning d-flex justify-content-between align-items-center">
            <span><i class="fas fa-edit me-2"></i> Edit Paket</span>
            <span class="fw-bold text-dark">{{ $package->name }}</span>
        </div>

        <div class="card-body">
            <form action="{{ route('packages.update', $package->id) }}" method="POST" id="packageForm">
                @csrf
                @method('PUT')

                <!-- Alert Error Validasi -->
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="row g-3">
                    <!-- Nama Paket -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                            value="{{ old('name', $package->name) }}" placeholder="Contoh: Paket Internet 50Mbps"
                            required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Tipe Paket -->
                    <div class="col-md-6">
                        <label for="type" class="form-label">Tipe Paket <span class="text-danger">*</span></label>
                        <select name="type" id="type"
                            class="form-control form-control-sm @error('type') is-invalid @enderror" required>
                            <option value="">Pilih Tipe Paket</option>
                            <option value="home" {{ old('type', $package->type) == 'home' ? 'selected' : '' }}>Home
                            </option>
                            <option value="business" {{ old('type', $package->type) == 'business' ? 'selected' : '' }}>
                                Business</option>
                            <option value="corporate"
                                {{ old('type', $package->type) == 'corporate' ? 'selected' : '' }}>Corporate</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Kecepatan -->
                    <div class="col-md-4">
                        <label for="speed_mbps" class="form-label">Kecepatan <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="speed_mbps" id="speed_mbps"
                                class="form-control @error('speed_mbps') is-invalid @enderror"
                                value="{{ old('speed_mbps', $package->speed_mbps) }}" placeholder="50" min="1" required>
                            <span class="input-group-text">Mbps</span>
                        </div>
                        @error('speed_mbps') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Kuota -->
                    <div class="col-md-4">
                        <label for="quota" class="form-label">Kuota <span class="text-danger">*</span></label>
                        <input type="text" name="quota" id="quota"
                            class="form-control form-control-sm @error('quota') is-invalid @enderror"
                            value="{{ old('quota', $package->quota) }}" placeholder="Contoh: Unlimited" required>
                        @error('quota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Harga -->
                    <div class="col-md-4">
                        <label for="price" class="form-label">Harga / Bulan <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="text" name="price" id="price"
                                class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $package->price) }}" placeholder="300000" required>
                        </div>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status"
                            class="form-control form-control-sm @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $package->status) == 'active' ? 'selected' : '' }}>
                                Aktif</option>
                            <option value="inactive"
                                {{ old('status', $package->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                            </option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-12">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control form-control-sm @error('description') is-invalid @enderror"
                            placeholder="Deskripsi fitur paket...">{{ old('description', $package->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-4 text-end">
                    <a href="{{ route('packages.index') }}" class="btn btn-sm btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fas fa-save me-1"></i> Update Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection