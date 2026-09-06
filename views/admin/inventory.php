<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../models/InventoryModel.php';

$inventoryModel = new InventoryModel();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $bg = $_POST['blood_group'] ?? 'A+';
    $units = $_POST['units'] ?? 1;
    $exp = $_POST['expiry_date'] ?? '';

    if ($inventoryModel->addInventory($_SESSION['user_id'], $bg, $units, $exp)) {
        $msg = "Blood bag inventory added successfully.";
    } else {
        $msg = "Failed to add inventory.";
    }
}

$inventoryList = isset($_SESSION['user_id']) ? $inventoryModel->getInventoryByHospital($_SESSION['user_id']) : [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hospital Blood Inventory - Smart Blood Network</title>
    <style>
        .container { max-width: 900px; margin: 20px auto; font-family: Arial, sans-serif; }
        .form-inline { display: flex; gap: 10px; margin-bottom: 20px; background: #eee; padding: 15px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .Expired { color: red; font-weight: bold; }
        .Expiring { color: orange; font-weight: bold; }
        .Valid { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>
    <div class="container">
        <h2>Hospital Blood Bag Inventory Tracker</h2>
        <?php if ($msg): ?><p style="color:green;"><?= htmlspecialchars($msg) ?></p><?php endif; ?>

        <form method="POST" class="form-inline">
            <select name="blood_group" required>
                <option value="A+">A+</option><option value="A-">A-</option>
                <option value="B+">B+</option><option value="B-">B-</option>
                <option value="O+">O+</option><option value="O-">O-</option>
                <option value="AB+">AB+</option><option value="AB-">AB-</option>
            </select>
            <input type="number" name="units" min="1" placeholder="Units" required>
            <input type="date" name="expiry_date" required>
            <button type="submit" style="background:#27ae60;color:white;border:none;padding:8px 15px;cursor:pointer;">Add Blood Stock</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Blood Group</th>
                    <th>Units Available</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventoryList as $inv): ?>
                    <tr>
                        <td><?= htmlspecialchars($inv['blood_group']) ?></td>
                        <td><?= htmlspecialchars($inv['units']) ?></td>
                        <td><?= htmlspecialchars($inv['expiry_date']) ?></td>
                        <td class="<?= strpos($inv['expiry_status'], 'Expired') !== false ? 'Expired' : ($inv['expiry_status'] == 'Expiring Soon' ? 'Expiring' : 'Valid') ?>">
                            <?= htmlspecialchars($inv['expiry_status']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>