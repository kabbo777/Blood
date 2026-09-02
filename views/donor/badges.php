<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../models/BadgeSystem.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$badgeModel = new BadgeSystem();
$badgeData = $badgeModel->getDonorBadges($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Milestone Badges - Smart Blood Network</title>
    <style>
        .container { max-width: 800px; margin: 30px auto; font-family: Arial, sans-serif; }
        .grid { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 20px; }
        .badge-box { width: 220px; border: 1px solid #ccc; padding: 15px; border-radius: 8px; text-align: center; background: #fafafa; }
        .Bronze { border-color: #cd7f32; background: #fff5eb; }
        .Silver { border-color: #bdc3c7; background: #f2f4f4; }
        .Gold { border-color: #f1c40f; background: #fefde8; }
        .Platinum { border-color: #8e44ad; background: #f5eeed; }
    </style>
</head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>
    <div class="container">
        <h2>Your Donation Milestones & Badges</h2>
        <p>Total Completed Donations: <strong><?= $badgeData['count'] ?></strong></p>
        <div class="grid">
            <?php if (empty($badgeData['badges'])): ?>
                <p>Complete your first donation to unlock milestone badges!</p>
            <?php else: ?>
                <?php foreach ($badgeData['badges'] as $b): ?>
                    <div class="badge-box <?= $b['level'] ?>">
                        <h3 style="margin:5px 0;"><?= htmlspecialchars($b['title']) ?></h3>
                        <p style="font-weight:bold;"><?= htmlspecialchars($b['level']) ?> Badge</p>
                        <small><?= htmlspecialchars($b['desc']) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>