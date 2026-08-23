<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
$auth = new AuthController();
$response = $auth->handleRegister();
?>
<!DOCTYPE html>
<html>
<head><title>Register - Smart Blood Network</title></head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>
    <h2>Account Registration</h2>
    <?php if (isset($response['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($response['error']) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Full Name:</label><br>
        <input type="text" name="full_name" required><br><br>
        <label>Email Address:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Phone Number:</label><br>
        <input type="text" name="phone" required><br><br>
        <label>Blood Group:</label><br>
        <select name="blood_group" required>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+" selected>O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
        </select><br><br>
        <label>Role:</label><br>
        <select name="role" required>
            <option value="donor">Donor</option>
            <option value="recipient">Recipient</option>
            <option value="hospital_admin">Hospital Admin</option>
        </select><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Register</button>
    </form>
    <br>
    <p>Already have an account? <a href="login.php">Log in here</a></p>
</body>
</html>