<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm border-0 p-4">
            <h3 class="text-danger fw-bold mb-3 text-center">Pre-Donation Health Screener</h3>

            <?php if (isset($result)): ?>
                <?php if ($result['eligible']): ?>
                    <div class="alert alert-success fw-bold text-center mb-4">
                        ✅ Congratulations! You pass the basic pre-donation screening.
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger mb-4">
                        <h5 class="fw-bold">Eligibility Criteria Not Met:</h5>
                        <ul class="mb-0">
                            <?php foreach ($result['reasons'] as $reason): ?>
                                <li><?= htmlspecialchars($reason) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <form action="/smart_blood_network/screener/check" method="POST">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Age (Years)</label>
                        <input type="number" name="age" class="form-control" required placeholder="18-65">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Weight (kg)</label>
                        <input type="number" step="0.1" name="weight" class="form-control" required placeholder="50+">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Hemoglobin (g/dL)</label>
                        <input type="number" step="0.1" name="hemoglobin" value="13.0" class="form-control" required>
                    </div>
                </div>

                <div class="card bg-light border-0 p-3 mb-4">
                    <div class="form-check mb-2">
                        <input type="checkbox" name="infection" class="form-check-input" id="inf">
                        <label class="form-check-label" for="inf">Active cold, fever, or flu symptoms</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="tattoo" class="form-check-input" id="tat">
                        <label class="form-check-label" for="tat">Received a tattoo or body piercing within 6 months</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="medication" class="form-check-input" id="med">
                        <label class="form-check-label" for="med">Currently taking prescription antibiotics</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger w-100 fw-bold">Check Eligibility</button>
            </form>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>