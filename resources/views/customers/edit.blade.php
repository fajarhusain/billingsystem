@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h3 class="card-title">
                <i class="fas fa-edit me-2"></i>
                Edit Pelanggan: {{ $customer->name }}
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}"
                        required>
                </div>

                <div class="mb-3">
    <label class="form-label">Dusun</label>
    <select name="dusun" class="form-select" required>
        <option value="rumasan" {{ old('dusun', $customer->dusun) == 'rumasan' ? 'selected' : '' }}>Rumasan</option>
        <option value="rimalang" {{ old('dusun', $customer->dusun) == 'rimalang' ? 'selected' : '' }}>Rimalang</option>
        <option value="semangeng" {{ old('dusun', $customer->dusun) == 'semangeng' ? 'selected' : '' }}>Semangeng</option>
        <option value="mangonan" {{ old('dusun', $customer->dusun) == 'mangonan' ? 'selected' : '' }}>Mangonan</option>
        <option value="pedoyo" {{ old('dusun', $customer->dusun) == 'pedoyo' ? 'selected' : '' }}>Pedoyo</option>
    </select>
</div>


                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="3"
                        required>{{ old('address', $customer->address) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Paket</label>
                    <select name="package_id" class="form-select" required>
                        @foreach($packages as $package)
                        <option value="{{ $package->id }}"
                            {{ $package->id == old('package_id', $customer->package_id) ? 'selected' : '' }}>
                            {{ $package->name }} - {{ $package->speed_mbps }} Mbps - {{ $package->formatted_price }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Daftar</label>
                    <input type="date" name="registration_date"
                        value="{{ old('registration_date', \Carbon\Carbon::parse($customer->registration_date)->format('Y-m-d')) }}"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="active"
                            {{ old('status', $customer->status ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="suspended"
                            {{ old('status', $customer->status ?? '') == 'suspended' ? 'selected' : '' }}>Ditangguhkan
                        </option>
                        <option value="terminated"
                            {{ old('status', $customer->status ?? '') == 'terminated' ? 'selected' : '' }}>Dihentikan
                        </option>
                    </select>

                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $customer->notes) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>
@endsection