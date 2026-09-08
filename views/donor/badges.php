<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Badges & Milestones</title>
    <style>
        .badge-card { border: 1px solid #ccc; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .bronze { border-left: 5px solid #cd7f32; }
        .silver { border-left: 5px solid #c0c0c0; }
        .gold { border-left: 5px solid #ffd700; }
        .platinum { border-left: 5px solid #e5e4e2; }
    </style>
</head>
<body>
    <h2>Your Donation Milestones</h2>
    <p>Total Completed Donations: <strong><?= $badgeData['total_donations'] ?></strong></p>

    <div class="badges-container">
        <?php foreach ($badgeData['badges'] as $badge): ?>
            <div class="badge-card <?= strtolower($badge['tier']) ?>">
                <h3><?= htmlspecialchars($badge['tier']) ?> Tier</h3>
                <p><?= htmlspecialchars($badge['description']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
