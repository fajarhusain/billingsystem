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
                                            <small class="form-text text-muted">
                                                Nomor invoice akan di-generate otomatis
                                            </small>
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


                                        <div class="mb-3">
                                            <label for="period" class="form-label">Periode</label>
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
                                <!-- Detail Pelanggan -->
                                <div class="card border-info mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-user-circle mr-1"></i>
                                            Detail Pelanggan
                                        </h6>
                                    </div>
                                    <div class="card-body" id="customerDetails">
                                        <div class="text-muted text-center py-3">
                                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                                            <p>Pilih pelanggan untuk melihat detail</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Perhitungan Biaya -->
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-calculator mr-1"></i>
                                            Perhitungan Biaya
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Biaya Paket -->
                                        <div class="form-group">
                                            <label for="amount" class="form-label required">
                                                <i class="fas fa-money-bill mr-1"></i>Jumlah Tagihan
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number"
                                                    class="form-control @error('amount') is-invalid @enderror"
                                                    id="amount" name="amount" value="{{ old('amount') }}" min="0"
                                                    required>
                                                @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Biaya Tambahan -->
                                        <div class="form-group">
                                            <label for="additional_charges" class="form-label">
                                                <i class="fas fa-plus-circle mr-1"></i>Biaya Tambahan
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number"
                                                    class="form-control @error('additional_charges') is-invalid @enderror"
                                                    id="additional_charges" name="additional_charges"
                                                    value="{{ old('additional_charges', 0) }}" min="0">
                                                @error('additional_charges')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">
                                                Opsional: biaya instalasi, denda, dll.
                                            </small>
                                        </div>

                                        <!-- Total -->
                                        <div class="border-top pt-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0">Total Tagihan:</h5>
                                                <h4 class="mb-0 text-success" id="totalAmount">Rp 0</h4>
                                            </div>
                                        </div>

                                        <!-- Catatan -->
                                        <div class="form-group mt-3">
                                            <label for="notes" class="form-label">
                                                <i class="fas fa-sticky-note mr-1"></i>Catatan
                                            </label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                                id="notes" name="notes" rows="3"
                                                placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                                            @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
<style>
.card {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border: none;
}

.card-header {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    border-bottom: none;
}

.form-label.required::after {
    content: " *";
    color: #dc3545;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.input-group-text {
    background-color: #f8f9fa;
}

.breadcrumb {
    background-color: transparent;
    padding: 0.5rem 0;
}

.breadcrumb-item+.breadcrumb-item::before {
    content: "›";
    font-weight: bold;
}

.alert {
    border: none;
    border-radius: 5px;
}

.select2-container--default .select2-selection--single {
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    border: 1px solid #ced4da;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: calc(1.5em + 0.75rem);
}

#totalAmount {
    font-weight: bold;
    font-size: 1.3em;
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('#customer_id').select2({
        placeholder: "Ketik nama pelanggan...",
        allowClear: true
    });

    // Customer selection change
    $('#customer_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');

        if (selectedOption.val()) {
            const customerName = selectedOption.text().split(' - ')[0];
            const packageName = selectedOption.data('package');
            const packagePrice = selectedOption.data('price');
            const customerPhone = selectedOption.data('phone');
            const customerAddress = selectedOption.data('address');

            // Update customer details
            $('#customerDetails').html(`
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-primary">${customerName}</h6>
                        <p class="mb-1"><i class="fas fa-phone mr-1"></i> ${customerPhone}</p>
                        <p class="mb-1"><i class="fas fa-map-marker-alt mr-1"></i> ${customerAddress}</p>
                        <p class="mb-0"><i class="fas fa-box mr-1"></i> <strong>${packageName}</strong></p>
                    </div>
                </div>
            `);

            // Set package price as default amount
            $('#amount').val(packagePrice);
            calculateTotal();
        } else {
            // Reset customer details
            $('#customerDetails').html(`
                <div class="text-muted text-center py-3">
                    <i class="fas fa-user-plus fa-2x mb-2"></i>
                    <p>Pilih pelanggan untuk melihat detail</p>
                </div>
            `);
            $('#amount').val('');
            calculateTotal();
        }
    });

    // Calculate total when amount or additional charges change
    $('#amount, #additional_charges').on('input', function() {
        calculateTotal();
    });

    function calculateTotal() {
        const amount = parseFloat($('#amount').val()) || 0;
        const additionalCharges = parseFloat($('#additional_charges').val()) || 0;
        const total = amount + additionalCharges;

        $('#totalAmount').text('Rp ' + total.toLocaleString('id-ID'));
    }

    // Auto-calculate period end when period start changes
    $('#period_start').on('change', function() {
        const startDate = new Date($(this).val());
        if (startDate) {
            // Set end date to last day of the same month
            const endDate = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
            $('#period_end').val(endDate.toISOString().split('T')[0]);
        }
    });

    // Auto-calculate due date when invoice date changes
    $('#invoice_date').on('change', function() {
        const invoiceDate = new Date($(this).val());
        if (invoiceDate) {
            // Set due date to 30 days after invoice date
            const dueDate = new Date(invoiceDate);
            dueDate.setDate(dueDate.getDate() + 30);
            $('#due_date').val(dueDate.toISOString().split('T')[0]);
        }
    });

    // Form validation
    $('#invoiceForm').on('submit', function(e) {
        let isValid = true;
        let errorMessages = [];

        // Validate customer selection
        if (!$('#customer_id').val()) {
            errorMessages.push('Pilih pelanggan terlebih dahulu');
            isValid = false;
        }

        // Validate amount
        const amount = parseFloat($('#amount').val());
        if (!amount || amount <= 0) {
            errorMessages.push('Jumlah tagihan harus lebih dari 0');
            isValid = false;
        }

        // Validate dates
        const invoiceDate = new Date($('#invoice_date').val());
        const dueDate = new Date($('#due_date').val());
        const periodStart = new Date($('#period_start').val());
        const periodEnd = new Date($('#period_end').val());

        if (dueDate <= invoiceDate) {
            errorMessages.push('Tanggal jatuh tempo harus setelah tanggal invoice');
            isValid = false;
        }

        if (periodEnd <= periodStart) {
            errorMessages.push('Periode selesai harus setelah periode mulai');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert('Mohon perbaiki kesalahan berikut:\n' + errorMessages.join('\n'));
            return false;
        }

        // Disable submit button to prevent double submission
        $('#submitBtn').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin mr-1"></i>Membuat Invoice...');
    });

    // Reset button if there are errors
    if ($('.alert-danger').length > 0) {
        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Buat Invoice');
    }

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Initialize calculation on page load
    calculateTotal();
});
</script>
@endpush