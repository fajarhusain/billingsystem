<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form id="paymentForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-success text-white rounded-top">
                    <h5 class="modal-title fw-bold" id="paymentModalLabel">
                        <i class="fas fa-receipt me-2"></i>Konfirmasi Pembayaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <p><strong>Invoice:</strong> <span id="modalInvoiceNumber"></span></p>
                    <p><strong>Pelanggan:</strong> <span id="modalCustomerName"></span></p>
                    <p><strong>Jumlah:</strong> <span class="badge bg-success fs-6" id="modalInvoiceAmount"></span></p>
                    <div class="mb-3">
                        <label>Tanggal Pembayaran</label>
                        <input type="date" class="form-control" name="payment_date" id="modalPaymentDate" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Metode Pembayaran</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>No. Referensi</label>
                        <input type="text" class="form-control" name="reference_number" placeholder="Opsional">
                    </div>
                    <div class="mb-3">
                        <label>Catatan</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Opsional"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success shadow-sm">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>