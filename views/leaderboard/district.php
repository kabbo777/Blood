<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../models/LeaderboardModel.php';

$lbModel = new LeaderboardModel();
$selectedDistrict = $_GET['district'] ?? '';
$leaderboard = $lbModel->getDistrictLeaderboard($selectedDistrict);
?>
<!DOCTYPE html>
<html>
<head>
    <title>District Top Donors Leaderboard - Smart Blood Network</title>
    <style>
        .container { max-width: 800px; margin: 20px auto; font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #c0392b; color: white; }
    </style>
</head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>
    <div class="container">
        <h2>Top Donors District Leaderboard</h2>
        <form method="GET" style="margin-bottom:15px;">
            <input type="text" name="district" placeholder="Filter by District (e.g. Dhaka)" value="<?= htmlspecialchars($selectedDistrict) ?>">
            <button type="submit" style="padding:6px 12px;background:#2c3e50;color:white;border:none;">Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Donor Name</th>
                    <th>Blood Group</th>
                    <th>District</th>
                    <th>Total Donations</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; foreach ($leaderboard as $row): ?>
                    <tr>
                        <td><strong>#<?= $rank++ ?></strong></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['blood_group']) ?></td>
                        <td><?= htmlspecialchars($row['district']) ?></td>
                        <td><strong><?= htmlspecialchars($row['donation_count']) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>