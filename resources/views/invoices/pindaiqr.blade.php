@extends('layouts.app')

@section('title', 'Pindai QR Tagihan')

@section('content')
<div class="container">
    <h2 class="mb-4">Pindai QR Tagihan</h2>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div id="reader" style="width:100%;"></div>
            <p class="text-muted mt-3">Arahkan kamera ke QR Code pelanggan untuk membuka detail tagihan.</p>
        </div>
    </div>
</div>

<!-- Script QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function onScanSuccess(decodedText, decodedResult) {
    console.log(`Scan result: ${decodedText}`);
    document.getElementById("scan-status").innerHTML =
        `<span class="text-success"><i class="fas fa-check-circle me-1"></i> QR Code terdeteksi!</span>`;

    setTimeout(() => {
        // Jika hasil scan sudah URL lengkap, langsung arahkan
        if (decodedText.startsWith("http")) {
            window.location.href = decodedText;
        } else {
            // Jika hanya ID, buat URL dengan prefix
            window.location.href = "/invoices/detailtagihancustomer/" + decodedText;
        }
    }, 1000);
}


function onScanFailure(error) {
    // error bisa diabaikan, scanner tetap jalan
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", {
        fps: 10,
        qrbox: 250
    }
);
html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endsection