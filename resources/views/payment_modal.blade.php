<!-- Modal Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="paymentModalLabel">Pembayaran Tagihan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="invoice_id">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nomor Tagihan</label>
                        <p class="form-control-plaintext invoice-number"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <p class="form-control-plaintext customer-name"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Tagihan</label>
                        <p class="form-control-plaintext invoice-amount fw-bold text-danger"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pembayaran</label>
                        <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="">-- Pilih --</option>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Referensi (opsional)</label>
                        <input type="text" class="form-control" name="reference_number"
                            placeholder="Misal: No. Transfer / No. Transaksi">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Konfirmasi Bayar</button>
                </div>
            </form>
        </div>
    </div>
</div>