@extends('layouts.app')

@section('title', 'Scan QR Invoice')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Scan QR Invoice</h2>
    <p>Arahkan kamera ke QR Code untuk mencari data pelanggan/invoice.</p>

    <div id="reader" style="width: 400px; height: 400px; border: 2px solid #28a745;"></div>
    <p class="mt-3">Hasil Scan: <span id="result"></span></p>
</div>

<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById("result").innerText = decodedText;

        // Arahkan ke halaman detail invoice/customer
        window.location.href = decodedText;
    }

    let html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start({
            facingMode: "environment"
        }, // kamera belakang
        {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        },
        onScanSuccess
    );
});
</script>
@endsection