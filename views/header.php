<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (file_exists(__DIR__ . '/../config/Config.php')) {
    require_once __DIR__ . '/../config/Config.php';
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? 'User';
$userRole = $_SESSION['user_role'] ?? '';
$baseUrl = defined('BASE_URL') ? BASE_URL : '/smart_blood_network/views';
?>
<div style="background-color:#2c3e50;padding:12px 20px;color:white;font-family:Arial,sans-serif;margin-bottom:20px;">
    <span style="font-size:18px;font-weight:bold;margin-right:20px;color:#e74c3c;">Smart Blood Network</span>
    <?php if ($isLoggedIn): ?>
        <a href="<?= $baseUrl ?>/donor/dashboard.php" style="color:white;margin-right:12px;text-decoration:none;">Dashboard</a>
        <a href="<?= $baseUrl ?>/donor/screener.php" style="color:white;margin-right:12px;text-decoration:none;">Screener</a>
        <a href="<?= $baseUrl ?>/emergency/create_request.php" style="color:#f1c40f;margin-right:12px;text-decoration:none;font-weight:bold;">Post Request</a>
        <a href="<?= $baseUrl ?>/emergency/sos_broadcast.php" style="color:#e74c3c;margin-right:12px;text-decoration:none;font-weight:bold;">SOS Alerts</a>
        <a href="<?= $baseUrl ?>/matching/search_donors.php" style="color:white;margin-right:12px;text-decoration:none;">Search</a>
        <a href="<?= $baseUrl ?>/map/location_map.php" style="color:white;margin-right:12px;text-decoration:none;">Map</a>
        <a href="<?= $baseUrl ?>/ai/assistant.php" style="color:white;margin-right:12px;text-decoration:none;">AI Chat</a>
        <a href="<?= $baseUrl ?>/notifications/list.php" style="color:white;margin-right:12px;text-decoration:none;">Notifications</a>
        <a href="<?= $baseUrl ?>/donor/followup.php" style="color:white;margin-right:12px;text-decoration:none;">Follow-Up</a>
        <a href="<?= $baseUrl ?>/payment/checkout.php" style="color:#27ae60;margin-right:12px;text-decoration:none;font-weight:bold;">Donate/Pay</a>
        <a href="<?= $baseUrl ?>/donor/history_tips.php" style="color:white;margin-right:12px;text-decoration:none;">History & Tips</a>
        <a href="<?= $baseUrl ?>/donor/badges.php" style="color:white;margin-right:12px;text-decoration:none;">Badges</a>
        <a href="<?= $baseUrl ?>/admin/inventory.php" style="color:white;margin-right:12px;text-decoration:none;">Inventory</a>
        <a href="<?= $baseUrl ?>/leaderboard/district.php" style="color:white;margin-right:12px;text-decoration:none;">Leaderboard</a>
        <span style="float:right;">
            Logged in as: <strong><?= htmlspecialchars($userName) ?></strong> (<?= strtoupper(htmlspecialchars($userRole)) ?>) | 
            <a href="<?= $baseUrl ?>/auth/logout.php" style="color:#e74c3c;font-weight:bold;text-decoration:none;margin-left:5px;">Logout</a>
        </span>
    <?php else: ?>
        <a href="<?= $baseUrl ?>/auth/login.php" style="color:white;margin-right:15px;text-decoration:none;">Login</a>
        <a href="<?= $baseUrl ?>/auth/register.php" style="color:white;text-decoration:none;">Register</a>
    <?php endif; ?>
</div>