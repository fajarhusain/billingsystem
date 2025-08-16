@extends('layouts.app')

@section('title', isset($customer) ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-{{ isset($customer) ? 'warning' : 'primary' }} text-white">
            <h3 class="card-title">
                <i class="fas fa-user-{{ isset($customer) ? 'edit' : 'plus' }} mr-2"></i>
                {{ isset($customer) ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru' }}
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ isset($customer) ? route('customers.update', $customer->id) : route('customers.store') }}"
                method="POST">
                @csrf
                @if(isset($customer))
                @method('PUT')
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $customer->name ?? '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $customer->email ?? '') }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $customer->phone ?? '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Dusun <span class="text-danger">*</span></label>
                            <select name="dusun" class="form-control" required>
                                <option value="">Pilih Dusun</option>
                                @php
                                $dusuns = ['rumasan', 'rimalang', 'semangeng', 'mangonan', 'pedoyo'];
                                @endphp
                                @foreach($dusuns as $dusun)
                                <option value="{{ $dusun }}"
                                    {{ old('dusun', $customer->dusun ?? '') == $dusun ? 'selected' : '' }}>
                                    {{ ucfirst($dusun) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                @if(isset($customer))
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Unik Pelanggan</label>
                            <input type="text" class="form-control" value="{{ $customer->unique_code }}" readonly>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Paket Internet <span class="text-danger">*</span></label>
                            <select name="package_id" class="form-control" required>
                                <option value="">Pilih Paket</option>
                                @foreach($packages as $package)
                                <option value="{{ $package->id }}"
                                    {{ old('package_id', $customer->package_id ?? '') == $package->id ? 'selected' : '' }}>
                                    {{ $package->name }} ({{ $package->speed_mbps }} Mbps) -
                                    {{ $package->formatted_price }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Pendaftaran <span class="text-danger">*</span></label>
                            <input type="date" name="registration_date" class="form-control"
                                value="{{ old('registration_date', isset($customer) ? $customer->registration_date->format('Y-m-d') : date('Y-m-d')) }}"
                                required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="active"
                                    {{ old('status', $customer->status ?? '') == 'active' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="suspended"
                                    {{ old('status', $customer->status ?? '') == 'suspended' ? 'selected' : '' }}>
                                    Ditangguhkan</option>
                                <option value="terminated"
                                    {{ old('status', $customer->status ?? '') == 'terminated' ? 'selected' : '' }}>
                                    Dihentikan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" rows="3"
                        required>{{ old('address', $customer->address ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="notes" class="form-control"
                        rows="2">{{ old('notes', $customer->notes ?? '') }}</textarea>
                </div>

                <div class="form-group text-right">
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary mr-2">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-{{ isset($customer) ? 'warning' : 'primary' }}">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection