<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation History & Cooldown</title>
</head>
<body>
    <h2>Donation Status</h2>
    <?php if ($cooldown['eligible']): ?>
        <p style="color: green;"><strong>You are currently eligible to donate blood!</strong></p>
    <?php else: ?>
        <p style="color: orange;"><strong>Resting Period Active:</strong> <?= $cooldown['days_remaining'] ?> days remaining until next eligible donation (<?= $cooldown['next_eligible_date'] ?>).</p>
    <?php endif; ?>

    <h2>Past Donation History</h2>
    <ul>
        <?php foreach ($history as $donation): ?>
            <li>Donated on <?= htmlspecialchars($donation['donation_date']) ?> - Blood Group: <?= htmlspecialchars($donation['blood_group']) ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
