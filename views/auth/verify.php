<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
$auth = new AuthController();
$response = $auth->handleVerify();
$debugCode = $_SESSION['debug_v_code'] ?? '';
?>
<!DOCTYPE html>
<html>
<head><title>Verify Account - Smart Blood Network</title></head>
<body>
    <h2>Account Verification</h2>
    <?php if (!empty($debugCode)): ?>
        <p style="background-color:#e2e3e5; padding: 10px; border-radius: 4px;">
            <strong>Verification Code (Debug Mode):</strong> <span style="font-size:18px; color:blue;"><?= htmlspecialchars($debugCode) ?></span>
        </p>
    <?php endif; ?>
    <?php if (isset($response['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($response['error']) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Enter 6-Digit Verification Code:</label><br>
        <input type="text" name="code" value="<?= htmlspecialchars($debugCode) ?>" maxlength="6" required><br><br>
        <button type="submit">Verify Account</button>
    </form>
</body>
</html>