<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="card shadow-sm border-0 mb-4 p-3">
    <form action="/smart_blood_network/leaderboard/district" method="GET" class="row g-3 align-items-center">
        <div class="col-auto"><label for="district" class="fw-bold text-danger">Select District Leaderboard:</label></div>
        <div class="col-auto">
            <select name="district" onchange="this.form.submit()" class="form-select">
                <option value="Dhaka" <?= $district === 'Dhaka' ? 'selected' : '' ?>>Dhaka</option>
                <option value="Chittagong" <?= $district === 'Chittagong' ? 'selected' : '' ?>>Chittagong</option>
                <option value="Sylhet" <?= $district === 'Sylhet' ? 'selected' : '' ?>>Sylhet</option>
            </select>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h4 class="text-danger fw-bold mb-0">Top Donors in <?= htmlspecialchars($district) ?></h4>
    </div>
    <div class="card-body p-0">
        <ol class="list-group list-group-numbered list-group-flush">
            <?php if (empty($rankings)): ?>
                <li class="list-group-item py-3 text-muted text-center">No ranked donors found in this district yet.</li>
            <?php else: ?>
                <?php foreach ($rankings as $rank): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="ms-2 me-auto fw-semibold"><?= htmlspecialchars($rank['name']) ?></div>
                        <span class="badge bg-danger rounded-pill fs-6"><?= $rank['total_donations'] ?> Donations</span>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ol>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
