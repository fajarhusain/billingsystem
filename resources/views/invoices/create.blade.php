@extends('layouts.app')

@section('title', 'Buat Invoice Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('invoices.index') }}">
                            <i class="fas fa-file-invoice mr-1"></i>Invoice
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Buat Invoice Baru</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Invoice Baru
                    </h3>
                </div>

                <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
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
                            <!-- Kolom Kiri - Informasi Invoice -->
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Informasi Invoice
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Pelanggan -->
                                        <div class="form-group">
                                            <label for="customer_id" class="form-label required">
                                                <i class="fas fa-user mr-1"></i>Pilih Pelanggan
                                            </label>
                                            <select
                                                class="form-control select2 @error('customer_id') is-invalid @enderror"
                                                id="customer_id" name="customer_id" required>
                                                <option value="">Pilih Pelanggan</option>
                                                @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    data-package="{{ $customer->package->name ?? '' }}"
                                                    data-price="{{ $customer->package->price ?? 0 }}"
                                                    data-phone="{{ $customer->phone }}"
                                                    data-address="{{ $customer->address }}"
                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }} -
                                                    {{ $customer->package->name ?? 'No Package' }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Nomor Invoice -->
                                        <div class="form-group">
                                            <label for="invoice_number" class="form-label required">
                                                <i class="fas fa-hashtag mr-1"></i>Nomor Invoice
                                            </label>
                                            <input type="text"
                                                class="form-control @error('invoice_number') is-invalid @enderror"
                                                id="invoice_number" name="invoice_number"
                                                value="{{ old('invoice_number', 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)) }}"
                                                readonly>
                                            @error('invoice_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tanggal Invoice -->
                                        <div class="form-group">
                                            <label for="invoice_date" class="form-label required">
                                                <i class="fas fa-calendar mr-1"></i>Tanggal Invoice
                                            </label>
                                            <input type="date"
                                                class="form-control @error('invoice_date') is-invalid @enderror"
                                                id="invoice_date" name="invoice_date"
                                                value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                            @error('invoice_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tanggal Jatuh Tempo -->
                                        <div class="form-group">
                                            <label for="due_date" class="form-label required">
                                                <i class="fas fa-clock mr-1"></i>Tanggal Jatuh Tempo
                                            </label>
                                            <input type="date"
                                                class="form-control @error('due_date') is-invalid @enderror"
                                                id="due_date" name="due_date"
                                                value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}"
                                                required>
                                            @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Periode -->
                                        <div class="mb-3">
                                            <label for="period" class="form-label">Periode (Bulan)</label>
                                            <input type="month" name="period" id="period" class="form-control" required>
                                        </div>

                                        <!-- Periode Layanan -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="period_start" class="form-label required">
                                                        <i class="fas fa-play mr-1"></i>Periode Mulai
                                                    </label>
                                                    <input type="date"
                                                        class="form-control @error('period_start') is-invalid @enderror"
                                                        id="period_start" name="period_start"
                                                        value="{{ old('period_start', date('Y-m-01')) }}" required>
                                                    @error('period_start')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="period_end" class="form-label required">
                                                        <i class="fas fa-stop mr-1"></i>Periode Selesai
                                                    </label>
                                                    <input type="date"
                                                        class="form-control @error('period_end') is-invalid @enderror"
                                                        id="period_end" name="period_end"
                                                        value="{{ old('period_end', date('Y-m-t')) }}" required>
                                                    @error('period_end')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan - Detail Pelanggan & Perhitungan -->
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-calculator mr-1"></i>
                                            Detail Pelanggan & Total
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="customerDetails" class="mb-3 text-muted text-center">
                                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                                            <p>Pilih pelanggan untuk melihat detail</p>
                                        </div>

                                        <div class="form-group">
                                            <label for="amount" class="form-label required">
                                                <i class="fas fa-money-bill mr-1"></i>Jumlah Tagihan
                                            </label>
                                            <input type="number" step="0.01" min="0"
                                                class="form-control @error('amount') is-invalid @enderror" id="amount"
                                                name="amount" value="{{ old('amount') }}" required>
                                            @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="additional_charges" class="form-label">
                                                <i class="fas fa-plus-circle mr-1"></i>Biaya Tambahan
                                            </label>
                                            <input type="number" step="0.01" min="0"
                                                class="form-control @error('additional_charges') is-invalid @enderror"
                                                id="additional_charges" name="additional_charges"
                                                value="{{ old('additional_charges', 0) }}">
                                            @error('additional_charges')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mt-3 text-right">
                                            <h5>Total: <span id="totalAmount">Rp 0</span></h5>
                                        </div>
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
                                <a href="{{ route('invoices.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times mr-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save mr-1"></i>Buat Invoice
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#customer_id').select2({
        placeholder: "Ketik nama pelanggan...",
        allowClear: true
    });

    // Customer selection change
    $('#customer_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            $('#customerDetails').html(`
                <p><strong>${selectedOption.text()}</strong></p>
                <p><i class="fas fa-phone mr-1"></i> ${selectedOption.data('phone')}</p>
                <p><i class="fas fa-map-marker-alt mr-1"></i> ${selectedOption.data('address')}</p>
                <p><i class="fas fa-box mr-1"></i> ${selectedOption.data('package')}</p>
            `);
            $('#amount').val(selectedOption.data('price'));
            calculateTotal();
        }
    });

    // Period auto-fill start and end date
    $('#period').on('change', function() {
        const [year, month] = $(this).val().split('-');
        if (year && month) {
            const startDate = new Date(year, month - 1, 1);
            const endDate = new Date(year, month, 0);
            $('#period_start').val(startDate.toISOString().split('T')[0]);
            $('#period_end').val(endDate.toISOString().split('T')[0]);
        }
    });

    $('#amount, #additional_charges').on('input', calculateTotal);

    function calculateTotal() {
        const amount = parseFloat($('#amount').val()) || 0;
        const additionalCharges = parseFloat($('#additional_charges').val()) || 0;
        $('#totalAmount').text('Rp ' + (amount + additionalCharges).toLocaleString('id-ID'));
    }

    calculateTotal();
});
</script>
@endpush