<?php

require_once __DIR__ . '/../navbar.php';


$canManage = $canManage ?? false;
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="text-danger fw-bold mb-0">🏥 Hospital Blood Bag Inventory</h4>
        <?php if (!$canManage): ?>
            <small class="text-muted">Read-only view — only hospital admins can manage stock.</small>
        <?php endif; ?>
    </div>
    <?php if ($canManage): ?>
    <button class="btn btn-danger btn-sm" data-bs-toggle="collapse" data-bs-target="#addForm">
        + Add Stock
    </button>
    <?php endif; ?>
</div>

<?php if ($canManage): ?>
<!-- Add inventory form (collapsible) — admins only -->
<div class="collapse mb-4" id="addForm">
    <div class="card border-0 shadow-sm p-3">
        <h6 class="fw-bold mb-3">Add New Inventory Entry</h6>
        <form action="/smart_blood_network/admin/inventory/add" method="POST" class="row g-2">
            <div class="col-md-3">
                <select name="hospital_id" class="form-select" required>
                    <option value="1">Square Hospital</option>
                    <option value="2">Dhaka Medical College Hospital</option>
                    <option value="3">Red Crescent Blood Bank</option>
                    <option value="4">Quantum Blood Bank</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="blood_group" class="form-select" required>
                    <?php foreach (['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg): ?>
                        <option value="<?= $bg ?>"><?= $bg ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="units" class="form-control" placeholder="Units" min="1" required>
            </div>
            <div class="col-md-3">
                <input type="date" name="expiry_date" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-danger w-100">Add</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Hospital / Blood Bank</th>
                        <th>Blood Group</th>
                        <th>Units Available</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <?php if ($canManage): ?>
                            <th class="pe-4 text-end">Update Units</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inventory)): ?>
                        <tr>
                            <td colspan="<?= $canManage ? 6 : 5 ?>" class="text-center py-4 text-muted">
                                No inventory records found.<?= $canManage ? ' Add stock above.' : '' ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inventory as $item): ?>
                            <?php
                                $expiry  = new DateTime($item['expiry_date']);
                                $today   = new DateTime();
                                $diff    = (int) $today->diff($expiry)->days;
                                $expired = $expiry < $today;
                                $warning = !$expired && $diff <= 7;
                            ?>
                            <tr class="<?= $expired ? 'table-danger' : ($warning ? 'table-warning' : '') ?>">
                                <td class="ps-4 fw-semibold"><?= htmlspecialchars($item['hospital_name'] ?? 'Unknown') ?></td>
                                <td><span class="badge bg-danger fs-6"><?= htmlspecialchars($item['blood_group']) ?></span></td>
                                <td><strong><?= htmlspecialchars((string)$item['units']) ?></strong> units</td>
                                <td>
                                    <?= htmlspecialchars($item['expiry_date']) ?>
                                    <?php if ($expired): ?>
                                        <span class="badge bg-danger ms-1">Expired</span>
                                    <?php elseif ($warning): ?>
                                        <span class="badge bg-warning text-dark ms-1">Expires soon</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $item['status'] === 'Available' ? 'success' : 'secondary' ?>">
                                        <?= htmlspecialchars($item['status'] ?? 'Available') ?>
                                    </span>
                                </td>
                                <?php if ($canManage): ?>
                                <td class="pe-4 text-end">
                                    <form action="/smart_blood_network/admin/inventory/update" method="POST" class="d-inline-flex gap-2 justify-content-end">
                                        <input type="hidden" name="inventory_id" value="<?= $item['id'] ?>">
                                        <input type="number" name="units" value="<?= $item['units'] ?>" min="0"
                                               class="form-control form-control-sm" style="width:90px;" required>
                                        <button type="submit" class="btn btn-sm btn-danger">Update</button>
                                    </form>
                                </td>
                                <?php endif; ?>
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
