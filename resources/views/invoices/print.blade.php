<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Invoice Pembayaran</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background: #f0f2f5;
        margin: 0;
        padding: 20px;
    }

    .invoice {
        max-width: 400px;
        margin: 0 auto;
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-top: 5px solid #28a745;
    }

    .invoice-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .invoice-header img {
        max-width: 80px;
        margin-bottom: 10px;
    }

    .invoice-header h2 {
        margin: 0;
        font-size: 18px;
        color: #333;
    }

    .invoice-details,
    .invoice-items,
    .invoice-footer {
        width: 100%;
        margin-bottom: 15px;
    }

    .invoice-details table,
    .invoice-items table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-details td,
    .invoice-items th,
    .invoice-items td {
        padding: 6px 4px;
        border-bottom: 1px solid #e0e0e0;
        font-size: 14px;
    }

    .invoice-items th {
        text-align: left;
        background: #f7f7f7;
        font-weight: bold;
    }

    .invoice-total {
        text-align: right;
        font-size: 16px;
        font-weight: bold;
        margin-top: 10px;
    }

    .status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        color: #fff;
        font-size: 13px;
    }

    .status-paid {
        background-color: #28a745;
    }

    .status-unpaid {
        background-color: #dc3545;
    }

    .status-overdue {
        background-color: #ffc107;
        color: #212529;
    }

    .invoice-footer {
        text-align: center;
        font-size: 13px;
        color: #666;
        border-top: 1px solid #e0e0e0;
        padding-top: 10px;
    }

    .btn-share {
        display: block;
        width: 200px;
        margin: 15px auto 0;
        padding: 10px;
        text-align: center;
        background: #28a745;
        color: #fff;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        font-weight: bold;
    }
    </style>
</head>

<body>

    <div class="invoice" id="invoice">
        <div class="invoice-header">
            <img src="https://via.placeholder.com/80x80.png?text=LOGO" alt="Logo">
            <h2>INVOICE PEMBAYARAN</h2>
            <small>JRC MEDIA ID</small>
        </div>

        <div class="invoice-details">
            <table>
                <tr>
                    <td><strong>Pelanggan:</strong></td>
                    <td>{{ $invoice->customer->name }}</td>
                </tr>
                <tr>
                    <td><strong>Alamat:</strong></td>
                    <td>{{ $invoice->customer->address ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Bulan Periode:</strong></td>
                    <td>{{ \Carbon\Carbon::parse($invoice->period.'-01')->format('F Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Pembayaran:</strong></td>
                    <td>
                        {{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td><strong>Status:</strong></td>
                    <td>
                        @if($invoice->status === 'paid')
                        <span class="status status-paid">LUNAS</span>
                        @elseif($invoice->status === 'unpaid')
                        <span class="status status-unpaid">BELUM BAYAR</span>
                        @else
                        <span class="status status-overdue">LEWAT JATUH TEMPO</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>


        <div class="invoice-items">
            <table>
                <tr>
                    <th>Paket</th>
                    <th>Harga</th>
                </tr>
                <tr>
                    <td>{{ $invoice->customer->package->name ?? '-' }}</td>
                    <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                </tr>
            </table>
            <div class="invoice-total">
                Total: Rp {{ number_format($invoice->amount, 0, ',', '.') }}
            </div>
        </div>

        <div class="invoice-footer">
            Terima kasih telah melakukan pembayaran.<br>
            Hubungi admin jika ada pertanyaan.
        </div>
    </div>

    <!-- Tombol Kembali / Home -->
    <!-- <a href="{{ route('invoices.index') }}" class="btn-share" style="background:#007bff; margin-bottom:10px;">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Tagihan
    </a> -->
    <!-- Tombol Kembali ke Halaman Sebelumnya -->
    <a href="{{ url()->previous() }}" class="btn-share" style="background:#6c757d; margin-bottom:10px;">
        ⬅ Kembali
    </a>


    <!-- Tombol Bagikan / Unduh Invoice -->
    <a class="btn-share" id="shareInvoice">Bagikan / Unduh Invoice</a>


    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
    document.getElementById("shareInvoice").addEventListener("click", async () => {
        const invoiceElement = document.getElementById("invoice");

        const canvas = await html2canvas(invoiceElement, {
            scale: 2
        });
        const dataUrl = canvas.toDataURL("image/png");

        const response = await fetch(dataUrl);
        const blob = await response.blob();
        const file = new File([blob], "invoice.png", {
            type: "image/png"
        });

        if (navigator.share && navigator.canShare && navigator.canShare({
                files: [file]
            })) {
            try {
                await navigator.share({
                    title: "Invoice Pembayaran",
                    text: "Berikut invoice pembayaran Anda.",
                    files: [file]
                });
            } catch (err) {
                alert("Gagal membagikan: " + err);
            }
        } else {
            const link = document.createElement("a");
            link.href = dataUrl;
            link.download = "invoice.png";
            link.click();
        }
    });
    </script>

</body>

</html>