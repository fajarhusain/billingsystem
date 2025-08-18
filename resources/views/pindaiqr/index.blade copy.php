@extends('layouts.app')

@section('title', 'Pindai QR Customer')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body text-center p-4">
                    <h3 class="mb-3 text-success">
                        <i class="fas fa-qrcode me-2"></i> Pindai QR Customer
                    </h3>
                    <p class="text-muted mb-4">
                        Arahkan kamera Anda ke QR Code pelanggan untuk melihat detail.
                    </p>

                    <!-- Scanner Box -->
                    <div id="reader"
                        style="width:100%; max-width:400px; margin:auto; border:2px dashed #198754; border-radius:12px; padding:10px;">
                    </div>

                    <div id="scan-status" class="mt-3 small text-muted">
                        <i class="fas fa-spinner fa-spin me-1"></i> Menunggu hasil scan...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
function onScanSuccess(decodedText, decodedResult) {
    console.log(`Scan result: ${decodedText}`);
    document.getElementById("scan-status").innerHTML =
        `<span class="text-success"><i class="fas fa-check-circle me-1"></i> QR Code terdeteksi!</span>`;

    // redirect ke halaman detail customer sesuai ID hasil scan
    setTimeout(() => {
        window.location.href = "/customers/" + decodedText;
    }, 1000);
}

function onScanFailure(error) {
    // Bisa ditambahkan alert jika perlu
}

let html5QrcodeScanner = new Html5QrcodeScanner("reader", {
    fps: 10,
    qrbox: {
        width: 250,
        height: 250
    }
});
html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endpush