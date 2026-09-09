<?php
// views/requests/list.php
// UPDATED: Added "Accept & Donate" button for matching donors.
//   - Only visible to logged-in donors
//   - Only shown when donor's blood group matches the request
//   - Hidden if donor is in Resting (cooldown) status
//   - Hidden if request is not Active
require_once __DIR__ . '/../navbar.php';

$isDonor        = (($_SESSION['role'] ?? '') === 'donor');
$donorBg        = $_SESSION['blood_group'] ?? '';
$donorAvailable = (($_SESSION['user_status'] ?? 'Available') === 'Available');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="text-danger fw-bold mb-0">📋 All Blood Requests</h3>
    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['recipient','hospital_admin'])): ?>
        <a href="/smart_blood_network/requests/create" class="btn btn-danger btn-sm fw-bold">
            + New Request
        </a>
    <?php endif; ?>
</div>

<?php if (empty($requests)): ?>
    <div class="alert alert-info text-center">No blood requests have been posted yet.</div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($requests as $req): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 <?= $req['urgency_level'] === 'Emergency_SOS' ? 'border-start border-danger border-4' : '' ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-danger fs-6"><?= htmlspecialchars($req['blood_group']) ?></span>
                            <span class="badge <?= match($req['urgency_level']) {
                                'Emergency_SOS' => 'bg-danger',
                                'High'          => 'bg-warning text-dark',
                                'Medium'        => 'bg-info text-dark',
                                default         => 'bg-secondary',
                            } ?>">
                                <?= htmlspecialchars($req['urgency_level']) ?>
                            </span>
                        </div>

                        <?php if ($req['is_anonymous']): ?>
                            <h6 class="card-title text-muted fst-italic">Anonymous Patient</h6>
                        <?php else: ?>
                            <h6 class="card-title fw-bold"><?= htmlspecialchars($req['patient_name']) ?></h6>
                        <?php endif; ?>

                        <p class="card-text mb-1 small">
                            <strong>Type:</strong> <?= htmlspecialchars($req['requirement_type'] ?? 'Blood') ?>
                            &nbsp;|&nbsp;
                            <strong>Units:</strong> <?= htmlspecialchars((string)$req['units_required']) ?>
                        </p>
                        <p class="card-text mb-1 small">
                            <strong>Hospital:</strong> <?= htmlspecialchars($req['hospital_name']) ?>
                        </p>
                        <p class="card-text mb-2 small text-muted">
                            <strong>Location:</strong> <?= htmlspecialchars($req['location'] ?? '') ?>
                        </p>
                        <p class="card-text small text-muted">
                            <i class="bi bi-clock me-1"></i><?= htmlspecialchars($req['created_at']) ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3 d-flex flex-column gap-2">
                        <div class="d-flex gap-2">
                            <a href="/smart_blood_network/requests/show?id=<?= $req['id'] ?>"
                               class="btn btn-sm btn-outline-danger flex-fill">View &amp; Share</a>
                            <?php if (!$req['is_anonymous'] && !empty($req['contact_number'])): ?>
                                <a href="tel:<?= htmlspecialchars($req['contact_number']) ?>"
                                   class="btn btn-sm btn-success">📞</a>
                            <?php endif; ?>
                        </div>

                        <?php
                        // "Accept & Donate" button:
                        // - User must be a donor
                        // - Blood group must match
                        // - Donor must not be in cooldown
                        // - Request must be Active
                        $canAccept = $isDonor
                                  && $donorBg === $req['blood_group']
                                  && $req['status'] === 'Active';
                        ?>

                        <?php if ($canAccept && $donorAvailable): ?>
                            <a href="/smart_blood_network/donations/accept?request_id=<?= $req['id'] ?>"
                               class="btn btn-sm btn-danger fw-bold">
                                🩸 Accept &amp; Donate
                            </a>
                        <?php elseif ($canAccept && !$donorAvailable): ?>
                            <button class="btn btn-sm btn-outline-secondary" disabled
                                    title="You are in your 90-day rest period">
                                ⏳ In Cooldown
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
