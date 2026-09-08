<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>District Donor Leaderboard</title>
</head>
<body>
    <h2>Top Donors in <?= htmlspecialchars($district) ?></h2>

    <form action="/leaderboard/district" method="GET">
        <label for="district">Select District:</label>
        <select name="district" id="district" onchange="this.form.submit()">
            <option value="Dhaka" <?= $district === 'Dhaka' ? 'selected' : '' ?>>Dhaka</option>
            <option value="Chittagong" <?= $district === 'Chittagong' ? 'selected' : '' ?>>Chittagong</option>
            <option value="Sylhet" <?= $district === 'Sylhet' ? 'selected' : '' ?>>Sylhet</option>
        </select>
    </form>

    <ol>
        <?php foreach ($rankings as $rank): ?>
            <li><?= htmlspecialchars($rank['name']) ?> - <?= $rank['total_donations'] ?> Donations</li>
        <?php endforeach; ?>
    </ol>
</body>
</html>
