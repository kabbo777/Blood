<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Blood Network</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
<div class="card shadow-sm border-0 p-4" style="max-width: 400px; width: 100%;">
    <h3 class="text-danger fw-bold text-center mb-3">Sign In</h3>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="/smart_blood_network/login/process" method="POST">
        <div class="mb-3"><label>Email Address</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
        <button type="submit" class="btn btn-danger w-100 fw-bold">Log In</button>
    </form>
    <p class="text-center mt-3 mb-0 text-muted">Don't have an account? <a href="/smart_blood_network/register" class="text-danger">Register Here</a></p>
</div>
</body>
</html>