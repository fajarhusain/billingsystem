@extends('layouts.app')

@section('title', 'Scan Barcode Pelanggan')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4"><i class="fas fa-qrcode me-2"></i>Scan Barcode Pelanggan</h3>

    <div id="reader" style="width: 400px;"></div>

    <div id="result" class="mt-4 text-center text-muted">
        <p><i class="fas fa-info-circle me-2"></i>Arahkan kamera ke barcode pelanggan</p>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function onScanSuccess(decodedText, decodedResult) {
    // Setelah scan, arahkan ke route find
    window.location.href = "/customers/find/" + decodedText;
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", {
        fps: 10,
        qrbox: 250
    }
);
html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection