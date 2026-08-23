<?php
require_once __DIR__ . '/../../controllers/EmergencyController.php';
$emergencyCtrl = new EmergencyController();
$broadcasts = $emergencyCtrl->getSOSBroadcasts();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Real-time SOS Broadcast - Smart Blood Network</title>
    <meta http-equiv="refresh" content="15">
</head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>

    <h2 style="color: red;">Active Emergency SOS Broadcasts</h2>
    <p><i>This board auto-updates in real-time to alert nearby eligible donors.</i></p>

    <?php if (empty($broadcasts)): ?>
        <p>No active emergency SOS alerts right now.</p>
    <?php else: ?>
        <?php foreach ($broadcasts as $sos): ?>
            <div style="border: 2px solid red; background-color: #fff0f0; padding: 15px; margin-bottom: 15px; border-radius: 5px;">
                <h3 style="margin-top:0; color:#d9534f;">
                    SOS: Need <?= htmlspecialchars($sos['blood_group']) ?> <?= htmlspecialchars($sos['requirement_type']) ?>
                </h3>
                <p><strong>Hospital:</strong> <?= htmlspecialchars($sos['hospital_name']) ?> (<?= htmlspecialchars($sos['location']) ?>)</p>
                <p><strong>Units Needed:</strong> <?= htmlspecialchars($sos['units_required']) ?></p>

                <?php if ($sos['is_anonymous']): ?>
                    <p><strong>Patient Info:</strong> <i>[Hidden for Privacy - Anonymous Mode Enabled]</i></p>
                <?php else: ?>
                    <p><strong>Patient Name:</strong> <?= htmlspecialchars($sos['patient_name']) ?></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($sos['contact_number']) ?></p>
                <?php endif; ?>

                <p><strong>Medical Details:</strong> <?= htmlspecialchars($sos['medical_details']) ?></p>
                <a href="../matching/ai_matches.php?request_id=<?= $sos['id'] ?>" style="background:green; color:white; padding:6px 12px; text-decoration:none;">View AI Matched Donors</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>