<?php

require_once __DIR__ . '/../navbar.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

        <?php if ($inCooldown): ?>
        <div class="alert alert-warning text-center fw-bold">
            ⏳ You are currently in your 90-day rest period after your last donation.
            Please check your <a href="/smart_blood_network/donations/history">donation history</a>
            for your cooldown end date.
        </div>
        <?php else: ?>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h5 class="text-danger fw-bold mb-1">📋 Request Details</h5>
                <hr class="my-2">
                <p class="mb-1"><strong>Patient:</strong>
                    <?= $request['is_anonymous'] ? '<em class="text-muted">Anonymous</em>' : htmlspecialchars($request['patient_name']) ?>
                </p>
                <p class="mb-1"><strong>Blood Group Needed:</strong>
                    <span class="badge bg-danger fs-6"><?= htmlspecialchars($request['blood_group']) ?></span>
                </p>
                <p class="mb-1"><strong>Type:</strong> <?= htmlspecialchars($request['requirement_type'] ?? 'Blood') ?></p>
                <p class="mb-1"><strong>Units Required:</strong> <?= htmlspecialchars((string)$request['units_required']) ?></p>
                <p class="mb-1"><strong>Hospital:</strong> <?= htmlspecialchars($request['hospital_name']) ?></p>
                <p class="mb-1"><strong>Location:</strong> <?= htmlspecialchars($request['location'] ?? '') ?></p>
                <p class="mb-0"><strong>Urgency:</strong>
                    <span class="badge bg-<?= $request['urgency_level'] === 'Emergency_SOS' ? 'danger' : ($request['urgency_level'] === 'High' ? 'warning text-dark' : 'secondary') ?>">
                        <?= htmlspecialchars($request['urgency_level']) ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <h4 class="text-danger fw-bold text-center mb-4">🩸 Confirm Your Donation</h4>

            <div class="alert alert-info small mb-3">
                <strong>Note:</strong> Submitting this form records your donation and starts your
                <strong>90-day rest period</strong>. Make sure you have actually donated or are
                confirmed to donate at the hospital.
            </div>

            <form action="/smart_blood_network/donations/save" method="POST">
                <input type="hidden" name="request_id" value="<?= (int)$request['id'] ?>">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Your Name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Your Blood Group</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['blood_group'] ?? '') ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Hospital</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($request['hospital_name']) ?>" readonly>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Units You Are Donating</label>
                    <input type="number" name="units_donated" class="form-control"
                           min="1" max="<?= (int)$request['units_required'] ?>"
                           value="1" required>
                    <div class="form-text">Max needed: <?= (int)$request['units_required'] ?> units</div>
                </div>

                <button type="submit" class="btn btn-danger w-100 fw-bold py-2"
                        onclick="return confirm('Are you sure you want to confirm this donation? This will start your 90-day rest period.')">
                    ✅ Confirm Donation
                </button>
                <a href="/smart_blood_network/requests/list" class="btn btn-outline-secondary w-100 mt-2">
                    Cancel
                </a>
            </form>
        </div>

        <?php endif; ?>
    </div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
