<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../models/DonationHistory.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$historyModel = new DonationHistory();
$donations = $historyModel->getHistoryByDonor($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Donation History & Health Tips - Smart Blood Network</title>
    <style>
        .container { max-width: 900px; margin: 20px auto; font-family: Arial, sans-serif; }
        .section { background: #fff; padding: 20px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #2c3e50; color: white; }
        .tip-box { background: #eaf2f8; border-left: 4px solid #3498db; padding: 10px 15px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>
    <div class="container">
        <div class="section">
            <h2>Your Donation History</h2>
            <?php if (empty($donations)): ?>
                <p>No donation records found yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Blood Group</th>
                            <th>Hospital</th>
                            <th>District</th>
                            <th>Certificate Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($donations as $d): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['donation_date']) ?></td>
                                <td><?= htmlspecialchars($d['blood_group']) ?></td>
                                <td><?= htmlspecialchars($d['hospital_name']) ?></td>
                                <td><?= htmlspecialchars($d['district']) ?></td>
                                <td><?= htmlspecialchars($d['certificate_code']) ?></td>
                                <td>
                                    <a href="../certificates/view.php?code=<?= $d['certificate_code'] ?>" target="_blank" style="background:#27ae60;color:white;padding:5px 10px;text-decoration:none;border-radius:3px;">View Cert</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>Post-Donation Health Guidelines & Tips</h2>
            <div class="tip-box">
                <strong>Hydration:</strong> Drink plenty of water and fluids for 24-48 hours post-donation to replace fluid loss.
            </div>
            <div class="tip-box">
                <strong>Iron-Rich Diet:</strong> Eat iron-rich foods such as spinach, beans, fish, and lean meat to replenish red blood cells.
            </div>
            <div class="tip-box">
                <strong>Rest & Recovery:</strong> Avoid strenuous physical exercise or heavy lifting for at least 5 hours after donating.
            </div>
        </div>
    </div>
</body>
</html>