<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="card shadow-sm border-0 mb-4 p-3">
    <form action="/smart_blood_network/donor/search" method="GET" class="row g-3 align-items-center">
        <div class="col-auto"><label class="fw-bold text-danger">Select Blood Group:</label></div>
        <div class="col-auto">
            <select name="blood_group" class="form-select">
                <?php foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg): ?>
                    <option value="<?= $bg ?>" <?= $targetBloodGroup === $bg ? 'selected' : '' ?>><?= $bg ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto"><button type="submit" class="btn btn-danger fw-bold">Find Matching Donors</button></div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h4 class="text-danger fw-bold mb-0">AI-Ranked Compatible Donors</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Donor Name</th>
                        <th>Blood Group</th>
                        <th>Distance</th>
                        <th>Donations</th>
                        <th>AI Match Score</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($matches)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No matching available donors found nearby.</td></tr>
                    <?php else: ?>
                        <?php foreach ($matches as $donor): ?>
                            <tr>
                                <td class="ps-4 fw-semibold"><?= htmlspecialchars($donor['name']) ?></td>
                                <td><span class="badge bg-danger"><?= htmlspecialchars($donor['blood_group']) ?></span></td>
                                <td><?= round($donor['distance'], 2) ?> km</td>
                                <td><?= $donor['completed_donations'] ?></td>
                                <td><span class="badge bg-success fs-6"><?= $donor['match_score'] ?> / 100</span></td>
                                <td class="pe-4 text-end">
                                    <a href="tel:<?= htmlspecialchars($donor['phone']) ?>" class="btn btn-sm btn-outline-danger">Call Donor</a>
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
