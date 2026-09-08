<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Inventory Management</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .expiring { background-color: #fff3cd; }
        .valid { background-color: #d4edda; }
    </style>
</head>
<body>
    <h2>Hospital Blood Inventory</h2>
    <table>
        <thead>
            <tr>
                <th>Blood Group</th>
                <th>Units</th>
                <th>Expiry Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventory as $item): ?>
            <tr class="<?= (strtotime($item['expiry_date']) - time() < 86400 * 7) ? 'expiring' : 'valid' ?>">
                <td><?= htmlspecialchars($item['blood_group']) ?></td>
                <td><?= htmlspecialchars($item['units']) ?></td>
                <td><?= htmlspecialchars($item['expiry_date']) ?></td>
                <td>
                    <form action="/admin/inventory/update" method="POST">
                        <input type="hidden" name="inventory_id" value="<?= $item['id'] ?>">
                        <input type="number" name="units" value="<?= $item['units'] ?>" required>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
