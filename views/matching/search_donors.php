<?php
require_once __DIR__ . '/../../controllers/MatchingController.php';
$matchCtrl = new MatchingController();
$donors = $matchCtrl->searchDonors();
?>
<!DOCTYPE html>
<html>
<head><title>Filter and Search Donors</title></head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>

    <h2>Filter Donors</h2>

    <form method="GET" action="" style="background:#e9ecef; padding:15px; margin-bottom:20px;">
        <label>Blood Group:</label>
        <select name="blood_group">
            <option value="">All Groups</option>
            <option value="A+" <?= ($_GET['blood_group']??'')==='A+'?'selected':'' ?>>A+</option>
            <option value="B+" <?= ($_GET['blood_group']??'')==='B+'?'selected':'' ?>>B+</option>
            <option value="O+" <?= ($_GET['blood_group']??'')==='O+'?'selected':'' ?>>O+</option>
            <option value="AB+" <?= ($_GET['blood_group']??'')==='AB+'?'selected':'' ?>>AB+</option>
            <option value="O-" <?= ($_GET['blood_group']??'')==='O-'?'selected':'' ?>>O-</option>
        </select>&nbsp;&nbsp;

        <label>Location:</label>
        <input type="text" name="location" value="<?= htmlspecialchars($_GET['location'] ?? '') ?>" placeholder="e.g. Dhaka">&nbsp;&nbsp;

        <label>Last Donation Before Date:</label>
        <input type="date" name="max_donation_date" value="<?= htmlspecialchars($_GET['max_donation_date'] ?? '') ?>">&nbsp;&nbsp;

        <button type="submit">Filter Donors</button>
    </form>

    <h3>Donor Search Results (<?= count($donors) ?> Found)</h3>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr style="background:#f2f2f2;">
            <th>Name</th>
            <th>Blood Group</th>
            <th>Location</th>
            <th>Status</th>
            <th>Last Donation Date</th>
            <th>Reliability Score</th>
            <th>Contact</th>
        </tr>
        <?php foreach ($donors as $donor): ?>
            <tr>
                <td><?= htmlspecialchars($donor['full_name']) ?></td>
                <td><strong><?= htmlspecialchars($donor['blood_group']) ?></strong></td>
                <td><?= htmlspecialchars($donor['location']) ?></td>
                <td><?= htmlspecialchars($donor['availability_status']) ?></td>
                <td><?= $donor['last_donation_date'] ? htmlspecialchars($donor['last_donation_date']) : 'Never / Fresh' ?></td>
                <td><?= htmlspecialchars($donor['reliability_score']) ?> / 5.0</td>
                <td><?= htmlspecialchars($donor['phone']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>