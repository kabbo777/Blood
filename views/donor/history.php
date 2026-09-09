<?php require_once __DIR__ . '/../navbar.php'; ?>

<div class="card shadow-sm border-0 p-4">
    <h3 class="text-danger mb-3 fw-bold">Donation Status</h3>
    
    <?php if (isset($isEligible) && $isEligible): ?>
        <div class="alert alert-success" role="alert">
            ✅ You are currently eligible to donate blood!
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            ⏳ Resting Period Active. You can donate again in <strong><?= htmlspecialchars($daysRemaining ?? 0) ?></strong> days.
        </div>
    <?php endif; ?>

    <h4 class="mt-4 mb-3">Past Donation History</h4>
    <?php if (!empty($history)): ?>
        <ul class="list-group">
            <?php foreach ($history as $entry): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Donated <?= htmlspecialchars($entry['blood_group']) ?> on <?= htmlspecialchars($entry['donation_date']) ?>
                    <a href="/smart_blood_network/certificate/view?donation_id=<?= $entry['id'] ?>" class="btn btn-sm btn-outline-primary">Certificate</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-muted">No past donations recorded yet.</p>
    <?php endif; ?>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>