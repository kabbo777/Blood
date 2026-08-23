<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
$auth = new AuthController();
$response = $auth->handleLogin();
?>
<!DOCTYPE html>
<html>
<head><title>Login - Smart Blood Network</title></head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>
    <h2>Account Login</h2>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'login_required'): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; margin-bottom: 15px;">
            Access Denied: Please log in first to access system features.
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'verified'): ?>
        <p style="color:green;">Account verified successfully! You can now log in below.</p>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out'): ?>
        <p style="color:blue;">You have logged out successfully.</p>
    <?php endif; ?>
    <?php if (isset($response['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($response['error']) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Email Address:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Log In</button>
    </form>
    <br>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>