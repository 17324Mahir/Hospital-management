<?php
session_start();

$allowed_roles = ['patient', 'doctor', 'receptionist', 'admin'];
$current_role = $_GET['role'] ?? '';

$is_valid_role = in_array($current_role, $allowed_roles);
$display_role = $is_valid_role ? ucfirst($current_role) : 'User';

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CareConnect | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body class="bg-light">

<div class="login-header-bar">
    <div class="login-brand">
        <img src="assets/images/logo.png" alt="CareConnect Logo">
        <span>CareConnect</span>
    </div>
</div>

<div class="container vh-100 d-flex justify-content-center align-items-center">

    <div class="card shadow p-4" style="width: 400px; border-radius: 15px;">

        <div class="text-center mb-4">
            <h3 class="login-title">CareConnect Login</h3>

            <p class="text-muted">
                Logging in as:
                <strong><?= htmlspecialchars($display_role); ?></strong>
            </p>
        </div>

        <?php if (!$is_valid_role): ?>
            <div class="alert alert-warning">
                Please select a valid role from the homepage login menu.
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form action="login_operations.php" method="POST">

            <input 
                type="hidden" 
                name="role"
                value="<?= htmlspecialchars($current_role); ?>"
            >

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    required
                    <?= !$is_valid_role ? 'disabled' : ''; ?>
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    required
                    <?= !$is_valid_role ? 'disabled' : ''; ?>
                >
            </div>

            <button 
                type="submit" 
                name="login_btn" 
                class="btn btn-primary w-100"
                <?= !$is_valid_role ? 'disabled' : ''; ?>
            >
                Login
            </button>

        </form>

        <div class="mt-4">

            <?php if ($current_role === 'admin' || $current_role === 'receptionist'): ?>

                <div class="text-center">
                    <a href="index.php" class="text-decoration-none small text-primary">
                        ← Back to Home
                    </a>
                </div>

            <?php elseif ($is_valid_role): ?>

                <div class="d-flex justify-content-between align-items-center">

                    <a 
                        href="register.php?role=<?= urlencode($current_role); ?>" 
                        class="text-decoration-none small text-primary fw-medium"
                    >
                        Register as <?= htmlspecialchars($display_role); ?>
                    </a>

                    <a 
                        href="forgot_password.php?role=<?= urlencode($current_role); ?>"
                        class="text-decoration-none small text-primary fw-medium"
                    >
                        Forgot password?
                    </a>

                </div>

            <?php else: ?>

                <div class="text-center">
                    <a href="index.php" class="text-decoration-none small text-primary">
                        ← Back to Home
                    </a>
                </div>

            <?php endif; ?>

        </div>

    </div>
</div>

</body>
</html>