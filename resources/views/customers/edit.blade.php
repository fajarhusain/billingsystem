@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-edit me-2"></i>Edit Pelanggan</span>
        </div>

        <div class="card-body">
            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control form-control-sm"
                            value="{{ old('name', $customer->name) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm"
                            value="{{ old('email', $customer->email) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="phone" class="form-control form-control-sm"
                            value="{{ old('phone', $customer->phone) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Dusun</label>
                        <select name="dusun" class="form-select form-select-sm" required>
                            <option value="rumasan" {{ old('dusun', $customer->dusun) == 'rumasan' ? 'selected' : '' }}>
                                Rumasan</option>
                            <option value="rimalang"
                                {{ old('dusun', $customer->dusun) == 'rimalang' ? 'selected' : '' }}>Rimalang</option>
                            <option value="semangeng"
                                {{ old('dusun', $customer->dusun) == 'semangeng' ? 'selected' : '' }}>Semangeng</option>
                            <option value="mangonan"
                                {{ old('dusun', $customer->dusun) == 'mangonan' ? 'selected' : '' }}>Mangonan</option>
                            <option value="pedoyo" {{ old('dusun', $customer->dusun) == 'pedoyo' ? 'selected' : '' }}>
                                Pedoyo</option>
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control form-control-sm" rows="2"
                            required>{{ old('address', $customer->address) }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Paket</label>
                        <select name="package_id" class="form-select form-select-sm" required>
                            @foreach($packages as $package)
                            <option value="{{ $package->id }}"
                                {{ $package->id == old('package_id', $customer->package_id) ? 'selected' : '' }}>
                                {{ $package->name }} - {{ $package->speed_mbps }} Mbps - {{ $package->formatted_price }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Daftar</label>
                        <input type="date" name="registration_date"
                            value="{{ old('registration_date', \Carbon\Carbon::parse($customer->registration_date)->format('Y-m-d')) }}"
                            class="form-control form-control-sm" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>
                                Aktif</option>
                            <option value="suspended"
                                {{ old('status', $customer->status) == 'suspended' ? 'selected' : '' }}>Ditangguhkan
                            </option>
                            <option value="terminated"
                                {{ old('status', $customer->status) == 'terminated' ? 'selected' : '' }}>Dihentikan
                            </option>
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control form-control-sm"
                            rows="2">{{ old('notes', $customer->notes) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection