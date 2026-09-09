<?php
// views/donations/history.php
// NEW FILE — Donor's personal donation history, badge level, and cooldown status.
require_once __DIR__ . '/../navbar.php';

$badgeColors = [
    'Platinum' => 'info',
    'Gold'     => 'warning',
    'Silver'   => 'secondary',
    'Bronze'   => 'danger',
    'None'     => 'light',
];
?>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success text-center fw-bold">
        <?= htmlspecialchars($_SESSION['flash_success']) ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<!-- Summary cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center p-3">
            <div class="display-5 fw-bold text-danger"><?= $count ?></div>
            <div class="text-muted small">Total Donations</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center p-3">
            <div class="fs-3"><?= $badge['icon'] ?></div>
            <span class="badge bg-<?= $badge['color'] ?> fs-6 mt-1"><?= $badge['name'] ?> Donor</span>
            <?php if ($count < 10): ?>
                <div class="text-muted small mt-1">
                    <?= match(true) {
                        $count < 1  => '1 donation for Bronze',
                        $count < 3  => (3 - $count) . ' more for Silver',
                        $count < 6  => (6 - $count) . ' more for Gold',
                        default     => (10 - $count) . ' more for Platinum',
                    } ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center p-3">
            <?php if ($cooldownDaysLeft !== null): ?>
                <div class="display-6 fw-bold text-warning"><?= $cooldownDaysLeft ?></div>
                <div class="text-muted small">Days left in cooldown</div>
                <div class="progress mt-2" style="height:6px;">
                    <div class="progress-bar bg-warning"
                         style="width:<?= min(100, round(($cooldownDaysLeft / 90) * 100)) ?>%"></div>
                </div>
            <?php else: ?>
                <div class="fs-3 text-success">✅</div>
                <div class="text-muted small">Ready to donate</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<h5 class="fw-bold mb-3">📋 Donation History</h5>

<?php if (empty($donations)): ?>
    <div class="alert alert-info text-center">
        You haven't recorded any donations yet.
        <a href="/smart_blood_network/requests/list" class="alert-link">Find a request to help with →</a>
    </div>
<?php else: ?>
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Date</th>
                        <th>Blood Group</th>
                        <th>Units</th>
                        <th>Hospital</th>
                        <th>Patient / Request</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donations as $i => $d): ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($d['donation_date']) ?></td>
                        <td><span class="badge bg-danger"><?= htmlspecialchars($d['blood_group']) ?></span></td>
                        <td><?= htmlspecialchars((string)$d['units_donated']) ?></td>
                        <td><?= htmlspecialchars($d['hospital_name']) ?></td>
                        <td class="text-muted small">
                            <?= !empty($d['patient_name'])
                                ? htmlspecialchars($d['patient_name'])
                                : '<em>Anonymous / Walk-in</em>' ?>
                            <?php if (!empty($d['req_location'])): ?>
                                <br><span class="text-muted"><?= htmlspecialchars($d['req_location']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="pe-4">
                            <span class="badge bg-<?= $d['status'] === 'Completed' ? 'success' : 'secondary' ?>">
                                <?= htmlspecialchars($d['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
