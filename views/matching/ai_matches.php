<?php
require_once __DIR__ . '/../../controllers/MatchingController.php';
$matchCtrl = new MatchingController();
$requestId = (int)($_GET['request_id'] ?? 0);
$data = $matchCtrl->getAIMatchesForRequest($requestId);
$request = $data['request'];
$matches = $data['matches'];
$aiSummary = $data['ai_summary'] ?? '';
?>
<!DOCTYPE html>
<html>
<head><title>AI Donor-Recipient Match Scoring</title></head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>

    <h2>AI Donor-Recipient Match Scoring</h2>

    <?php if (!$request): ?>
        <p>No valid blood request selected. <a href="../emergency/create_request.php">Create one here</a>.</p>
    <?php else: ?>
        <div style="background:#e8f4f8; padding:15px; border-left:4px solid #31708f; margin-bottom:20px;">
            <h3>Request Summary</h3>
            <p><strong>Required Group:</strong> <?= htmlspecialchars($request['blood_group']) ?> | 
               <strong>Type:</strong> <?= htmlspecialchars($request['requirement_type']) ?> | 
               <strong>Hospital:</strong> <?= htmlspecialchars($request['hospital_name']) ?></p>
        </div>

        <?php if (!empty($aiSummary)): ?>
            <div style="background:#f0f9ff; border:1px solid #bfe3ff; padding:15px; margin-bottom:20px; border-radius:5px;">
                <h3 style="color:#0056b3; margin-top:0;">Gemini AI Compatibility Insights</h3>
                <p style="white-space: pre-wrap; font-family: sans-serif;"><?= htmlspecialchars($aiSummary) ?></p>
            </div>
        <?php endif; ?>

        <h3>Top AI Recommended Donors Ranked by Compatibility</h3>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <tr style="background:#007bff; color:white;">
                <th>Rank</th>
                <th>AI Match Score</th>
                <th>Donor Name</th>
                <th>Blood Group</th>
                <th>Distance</th>
                <th>Reliability Score</th>
                <th>Contact</th>
            </tr>
            <?php $rank = 1; foreach ($matches as $match): ?>
                <tr>
                    <td><strong>#<?= $rank++ ?></strong></td>
                    <td style="font-size:16px;">
                        <span style="background:green; color:white; padding:4px 8px; border-radius:4px;">
                            <strong><?= htmlspecialchars($match['match_score']) ?>% Match</strong>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($match['full_name']) ?></td>
                    <td><strong><?= htmlspecialchars($match['blood_group']) ?></strong></td>
                    <td><?= htmlspecialchars($match['distance_km']) ?> km away</td>
                    <td><?= htmlspecialchars($match['reliability_score']) ?> / 5.0</td>
                    <td><?= htmlspecialchars($match['phone']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>