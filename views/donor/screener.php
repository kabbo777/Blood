<?php
require_once __DIR__ . '/../../controllers/ScreenerController.php';
$screenerCtrl = new ScreenerController();
$result = $screenerCtrl->submitScreener();
$lastScreening = $screenerCtrl->getLastStatus();
$currentBloodGroup = $screenerCtrl->getCurrentUserBloodGroup();
?>
<!DOCTYPE html>
<html>
<head><title>Health Screener - Smart Blood Network</title></head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>

    <h2>Feature 2: Self-Service Pre-Donation Health Screener</h2>

    <?php if (!empty($lastScreening)): ?>
        <div style="background-color: #f8f9fa; border: 1px solid #ddd; padding: 10px; margin-bottom: 15px;">
            <strong>Last Evaluation Result:</strong> 
            <?= !empty($lastScreening['is_eligible']) ? '<span style="color:green;">ELIGIBLE</span>' : '<span style="color:red;">TEMPORARILY INELIGIBLE</span>' ?>
            (Evaluated on: <?= htmlspecialchars($lastScreening['created_at'] ?? 'Recorded') ?>)
        </div>
    <?php endif; ?>

    <?php if (isset($result['processed'])): ?>
        <div style="border: 2px solid <?= $result['is_eligible'] ? 'green' : 'red' ?>; padding: 15px; margin-bottom: 20px;">
            <?php if (!empty($result['blood_group_updated'])): ?>
                <p style="color: blue; font-weight: bold; margin-top:0;">Blood Group updated successfully!</p>
            <?php endif; ?>

            <?php if ($result['is_eligible']): ?>
                <h3 style="color: green; margin-bottom: 0;">Screening Passed: You are eligible to donate!</h3>
            <?php else: ?>
                <h3 style="color: red; margin-bottom: 5px;">Screening Failed: Temporarily Ineligible</h3>
                <ul>
                    <?php foreach ($result['reasons'] as $reason): ?>
                        <li><?= htmlspecialchars($reason) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label><strong>Blood Group (Update if needed):</strong></label><br>
        <select name="blood_group" required>
            <option value="A+" <?= $currentBloodGroup === 'A+' ? 'selected' : '' ?>>A+</option>
            <option value="A-" <?= $currentBloodGroup === 'A-' ? 'selected' : '' ?>>A-</option>
            <option value="B+" <?= $currentBloodGroup === 'B+' ? 'selected' : '' ?>>B+</option>
            <option value="B-" <?= $currentBloodGroup === 'B-' ? 'selected' : '' ?>>B-</option>
            <option value="O+" <?= $currentBloodGroup === 'O+' ? 'selected' : '' ?>>O+</option>
            <option value="O-" <?= $currentBloodGroup === 'O-' ? 'selected' : '' ?>>O-</option>
            <option value="AB+" <?= $currentBloodGroup === 'AB+' ? 'selected' : '' ?>>AB+</option>
            <option value="AB-" <?= $currentBloodGroup === 'AB-' ? 'selected' : '' ?>>AB-</option>
        </select><br><br>

        <label>Current Age (Must be 18-65):</label><br>
        <input type="number" name="age" required><br><br>

        <label>Weight in KG (Must be >= 50kg):</label><br>
        <input type="number" step="0.1" name="weight_kg" required><br><br>

        <label>
            <input type="checkbox" name="has_chronic_illness" value="1">
            Do you have any chronic medical illnesses?
        </label><br><br>

        <label>
            <input type="checkbox" name="recent_tattoo_surgery" value="1">
            Have you had a tattoo, piercing, or major surgery in the last 6 months?
        </label><br><br>

        <label>
            <input type="checkbox" name="feeling_healthy" value="1" checked>
            Are you currently feeling healthy and well today?
        </label><br><br>

        <button type="submit">Submit Health Assessment</button>
    </form>
</body>
</html>