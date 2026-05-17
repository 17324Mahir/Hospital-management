<?php
require_once 'config/database.php';

$message = "";
$error = "";

$allowed_roles = ['patient', 'doctor', 'receptionist'];

$role_from_url = $_GET['role'] ?? '';

if ($role_from_url === 'admin') {
    header("Location: login.php?role=admin&error=" . urlencode("Admin password cannot be reset from forgot password."));
    exit();
}

if (!empty($role_from_url) && !in_array($role_from_url, $allowed_roles)) {
    $role_from_url = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';
    $mobile = trim($_POST['mobile'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (
        empty($email) ||
        empty($role) ||
        empty($mobile) ||
        empty($new_password) ||
        empty($confirm_password)
    ) {
        $error = "Please fill all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (!in_array($role, $allowed_roles)) {
        $error = "Invalid role selected.";
    } elseif ($role === 'admin') {
        $error = "Admin password cannot be reset from forgot password.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $sql = "SELECT id 
                FROM users 
                WHERE email = ? 
                AND role = ? 
                AND mobile = ?
                LIMIT 1";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sss", $email, $role, $mobile);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            $error = "No account found with this email, role, and mobile number.";
        } else {
            /*
                Your current project supports plain text passwords.
                So this keeps the same system.

                For secure hashed password, use this instead:
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            */
            $password_hash = $new_password;

            $update_sql = "UPDATE users 
                           SET password_hash = ?
                           WHERE id = ?";

            $update_stmt = $conn->prepare($update_sql);

            if (!$update_stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $update_stmt->bind_param("si", $password_hash, $user['id']);

            if ($update_stmt->execute()) {
                $message = "Password reset successful. You can login now.";
            } else {
                $error = "Failed to reset password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - CareConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f4f7f6;">

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height:100vh;">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4">

                    <h3 class="text-center mb-3">Forgot Password</h3>

                    <p class="text-muted text-center">
                        Verify your email, role, and mobile number to reset password.
                    </p>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($message); ?>
                            <br>

                            <?php if (!empty($role)): ?>
                                <a href="login.php?role=<?= urlencode($role); ?>" class="alert-link">
                                    Go to Login
                                </a>
                            <?php else: ?>
                                <a href="index.php" class="alert-link">
                                    Go to Home
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">Select role</option>

                                <option 
                                    value="patient"
                                    <?= $role_from_url === 'patient' ? 'selected' : ''; ?>
                                >
                                    Patient
                                </option>

                                <option 
                                    value="doctor"
                                    <?= $role_from_url === 'doctor' ? 'selected' : ''; ?>
                                >
                                    Doctor
                                </option>

                                <option 
                                    value="receptionist"
                                    <?= $role_from_url === 'receptionist' ? 'selected' : ''; ?>
                                >
                                    Receptionist
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mobile Number</label>
                            <input 
                                type="text" 
                                name="mobile" 
                                class="form-control"
                                placeholder="Enter registered mobile"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input 
                                type="password" 
                                name="new_password" 
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input 
                                type="password" 
                                name="confirm_password" 
                                class="form-control"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Reset Password
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        <?php if (!empty($role_from_url)): ?>
                            <a href="login.php?role=<?= urlencode($role_from_url); ?>">
                                Back to Login
                            </a>
                        <?php else: ?>
                            <a href="index.php">
                                Back to Home
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>