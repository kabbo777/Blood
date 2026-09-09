<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="mb-4">
    <?php if ($cooldown['eligible']): ?>
        <div class="alert alert-success d-flex align-items-center shadow-sm" role="alert">
            <span class="fs-4 me-2">✅</span>
            <div><strong>Status: Eligible!</strong> You are currently eligible to donate blood.</div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning d-flex align-items-center shadow-sm" role="alert">
            <span class="fs-4 me-2">⏳</span>
            <div>
                <strong>Resting Period Active:</strong> <?= $cooldown['days_remaining'] ?> days remaining until your next eligible donation (<?= $cooldown['next_eligible_date'] ?>).
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h4 class="text-danger fw-bold mb-0">Past Donation History</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Donation Date</th>
                        <th>Blood Group</th>
                        <th class="pe-4 text-end">Certificate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($history)): ?>
                        <tr><td colspan="3" class="text-center py-4 text-muted">No donation history available yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($history as $donation): ?>
                            <tr>
                                <td class="ps-4"><?= htmlspecialchars($donation['donation_date']) ?></td>
                                <td><span class="badge bg-danger"><?= htmlspecialchars($donation['blood_group']) ?></span></td>
                                <td class="pe-4 text-end">
                                    <a href="/smart_blood_network/certificate/view?donation_id=<?= $donation['id'] ?>" class="btn btn-sm btn-outline-danger">View Certificate</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
