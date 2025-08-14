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
                    <div class="mb-3">
                        <p class="mb-1"><strong>Invoice:</strong> <span class="text-primary"
                                id="modalInvoiceNumber"></span></p>
                        <p class="mb-1"><strong>Pelanggan:</strong> <span id="modalCustomerName"></span></p>
                        <p class="mb-3"><strong>Jumlah:</strong> <span class="badge bg-success fs-6"
                                id="modalInvoiceAmount"></span></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fas fa-calendar-day me-1"></i> Tanggal
                            Pembayaran</label>
                        <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fas fa-money-check-alt me-1"></i> Metode
                            Pembayaran</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fas fa-hashtag me-1"></i> No. Referensi</label>
                        <input type="text" class="form-control" name="reference_number" placeholder="Opsional">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="fas fa-sticky-note me-1"></i> Catatan</label>
                        <textarea class="form-control" name="notes" rows="2"
                            placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-check-circle me-1"></i> Konfirmasi
                    </button>
                </div>
            </form>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var paymentModal = document.getElementById('paymentModal');
                paymentModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var invoiceId = button.getAttribute('data-invoice-id');

                    // Gunakan route bernama
                    paymentModal.querySelector('#paymentForm').action = "{{ url('invoices') }}/" +
                        invoiceId + "/mark-as-paid";
                });
            });
            </script>
        </div>
    </div>
</div>