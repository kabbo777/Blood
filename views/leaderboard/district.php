<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$config_files = [
    __DIR__ . '/../../config/database.php',
    __DIR__ . '/../../config/db.php',
    __DIR__ . '/../../config.php',
    __DIR__ . '/../../db.php'
];

foreach ($config_files as $file) {
    if (file_exists($file)) {
        require_once $file;
        break;
    }
}

require_once __DIR__ . '/../../models/LeaderboardModel.php';

$dbConnection = $pdo ?? $db ?? $conn ?? null;
$leaderboardModel = new LeaderboardModel($dbConnection);

$selectedDistrict = isset($_GET['district']) ? trim($_GET['district']) : '';
$leaderboard = $leaderboardModel->getDistrictLeaderboard($selectedDistrict);

$districts = [
    'Bagerhat', 'Bandarban', 'Barguna', 'Barishal', 'Bhola', 'Bogra', 'Brahmanbaria',
    'Chandpur', 'Chittagong', 'Chuadanga', 'Comilla', "Cox's Bazar", 'Dhaka', 'Dinajpur',
    'Faridpur', 'Feni', 'Gaibandha', 'Gazipur', 'Gopalganj', 'Habiganj', 'Jamalpur',
    'Jashore', 'Jhalokati', 'Jhenaidah', 'Joypurhat', 'Khagrachhari', 'Khulna', 'Kishoreganj',
    'Kurigram', 'Kushtia', 'Lakshmipur', 'Lalmonirhat', 'Madaripur', 'Magura', 'Manikganj',
    'Meherpur', 'Moulvibazar', 'Munshiganj', 'Mymensingh', 'Naogaon', 'Narail', 'Narayanganj',
    'Narsingdi', 'Natore', 'Nawabganj', 'Netrokona', 'Nilphamari', 'Noakhali', 'Pabna',
    'Panchagarh', 'Patuakhali', 'Pirojpur', 'Rajbari', 'Rajshahi', 'Rangamati', 'Rangpur',
    'Satkhira', 'Shariatpur', 'Sherpur', 'Sirajganj', 'Sunamganj', 'Sylhet', 'Tangail', 'Thakurgaon'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>District Leaderboard - Smart Blood Network</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">District Donor Leaderboard</h2>
            <a href="../../index.php" class="btn btn-outline-secondary">Back to Home</a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="" class="row g-3 align-items-end">
                    <div class="col-md-9">
                        <label for="district" class="form-label fw-bold">Select District</label>
                        <select name="district" id="district" class="form-select">
                            <option value="">All Districts</option>
                            <?php foreach ($districts as $d): ?>
                                <option value="<?php echo htmlspecialchars($d); ?>" <?php echo ($selectedDistrict === $d) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($d); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="district.php" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Rank</th>
                                <th>Donor Name</th>
                                <th>Blood Group</th>
                                <th>Location</th>
                                <th>Reliability Score</th>
                                <th>Total Donations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($leaderboard)): ?>
                                <?php $rank = 1; ?>
                                <?php foreach ($leaderboard as $donor): ?>
                                    <tr>
                                        <td><strong>#<?php echo $rank++; ?></strong></td>
                                        <td><?php echo htmlspecialchars($donor['name']); ?></td>
                                        <td><span class="badge bg-danger"><?php echo htmlspecialchars($donor['blood_group']); ?></span></td>
                                        <td><?php echo htmlspecialchars($donor['location']); ?></td>
                                        <td><?php echo htmlspecialchars($donor['reliability_score']); ?> / 5.00</td>
                                        <td><strong><?php echo htmlspecialchars($donor['total_donations']); ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No donor records found for the selected criteria.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
