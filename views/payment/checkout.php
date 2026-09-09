<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 p-4">
            <h3 class="text-danger fw-bold mb-3 text-center">Support & Processing Fees</h3>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form action="/smart_blood_network/payment/process" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Amount (BDT)</label>
                    <input type="number" name="amount" value="500" min="10" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Select Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="bKash">bKash Mobile Banking</option>
                        <option value="Nagad">Nagad Wallet</option>
                        <option value="Card">Credit / Debit Card</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger w-100 fw-bold">Proceed to Secure Payment</button>
            </form>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>