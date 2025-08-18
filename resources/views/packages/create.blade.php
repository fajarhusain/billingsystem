@extends('layouts.app')

@section('title', 'Tambah Paket')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-gradient-primary text-white py-2">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Paket
                    </h6>
                </div>

                <div class="card-body">
                    <form action="{{ route('packages.store') }}" method="POST" id="packageForm">
                        @csrf

                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="form-row">
                            <!-- Nama Paket -->
                            <div class="form-group col-md-6">
                                <label for="name">Nama Paket <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Contoh: Paket Internet 50Mbps" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipe Paket -->
                            <div class="form-group col-md-6">
                                <label for="type">Tipe Paket <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror"
                                    required>
                                    <option value="">Pilih Tipe Paket</option>
                                    <option value="home" {{ old('type') == 'home' ? 'selected' : '' }}>Home</option>
                                    <option value="business" {{ old('type') == 'business' ? 'selected' : '' }}>Business
                                    </option>
                                    <option value="corporate" {{ old('type') == 'corporate' ? 'selected' : '' }}>
                                        Corporate</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <!-- Kecepatan -->
                            <div class="form-group col-md-4">
                                <label for="speed_mbps">Kecepatan (Mbps) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="speed_mbps" id="speed_mbps"
                                        class="form-control @error('speed_mbps') is-invalid @enderror"
                                        value="{{ old('speed_mbps') }}" placeholder="50" min="1" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Mbps</span>
                                    </div>
                                </div>
                                @error('speed_mbps')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kuota -->
                            <div class="form-group col-md-4">
                                <label for="quota">Kuota <span class="text-danger">*</span></label>
                                <input type="text" name="quota" id="quota"
                                    class="form-control @error('quota') is-invalid @enderror"
                                    value="{{ old('quota', 'Unlimited') }}" placeholder="Contoh: Unlimited" required>
                                @error('quota')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Harga -->
                            <div class="form-group col-md-4">
                                <label for="price">Harga / Bulan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" name="price" id="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price') }}" placeholder="300000" required>
                                </div>
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group col-md-6 pl-0">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror"
                                required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" rows="3"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Deskripsi fitur paket...">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="text-right">
                            <button type="reset" class="btn btn-sm btn-outline-secondary mr-2">
                                <i class="fas fa-undo mr-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#price').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>
@endpush