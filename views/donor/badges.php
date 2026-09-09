<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body text-center py-4 bg-white rounded-3">
        <h2 class="text-danger fw-bold mb-1">Your Donation Milestones</h2>
        <p class="text-muted mb-0">Total Completed Donations: <span class="badge bg-danger fs-5 ms-1"><?= $badgeData['total_donations'] ?></span></p>
    </div>
</div>

<div class="row g-3">
    <?php if (empty($badgeData['badges'])): ?>
        <div class="col-12"><div class="alert alert-info text-center">Complete your first donation to earn your first milestone badge!</div></div>
    <?php else: ?>
        <?php foreach ($badgeData['badges'] as $badge): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 text-center p-3">
                    <div class="card-body">
                        <div class="fs-1 mb-2">⭐</div>
                        <h4 class="card-title text-danger fw-bold"><?= htmlspecialchars($badge['tier']) ?> Tier</h4>
                        <p class="card-text text-muted small"><?= htmlspecialchars($badge['description']) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
