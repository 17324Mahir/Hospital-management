<?php
session_start();

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration | CareConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/Register.css">
</head>

<body>

<div class="bg-overlay"></div>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="registration-box shadow-lg">

        <div class="text-center mb-4">
            <img src="assets/images/logo.png" class="logo" alt="CareConnect Logo" style="width: 60px;">
            <h2 class="title">Create New Account</h2>
            <p class="subtitle">CareConnect Hospital</p>
        </div>

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

        <form action="register_operations.php" method="POST">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">First Name *</label>
                    <input 
                        type="text" 
                        name="firstName" 
                        class="form-control" 
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Last Name *</label>
                    <input 
                        type="text" 
                        name="lastName" 
                        class="form-control" 
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date of Birth *</label>
                    <input 
                        type="date" 
                        name="dob" 
                        class="form-control" 
                        max="<?= date('Y-m-d'); ?>"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone Number *</label>
                    <input 
                        type="text" 
                        name="phone" 
                        class="form-control" 
                        placeholder="01XXXXXXXXX" 
                        required
                    >
                </div>

                <div class="col-md-12">
                    <label class="form-label">Email *</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-12">
                    <label class="form-label">Select Your Role *</label>
                    <select name="role" class="form-select" required>
                        <option value="" selected disabled>Choose your role...</option>
                        <option value="patient">Patient</option>
                        <option value="doctor">Doctor</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password *</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control" 
                        minlength="6"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirm Password *</label>
                    <input 
                        type="password" 
                        name="confirmPassword" 
                        class="form-control" 
                        minlength="6"
                        required
                    >
                </div>

            </div>

            <button type="submit" name="register_btn" class="btn btn-primary w-100 mt-4">
                Register
            </button>

            <p class="text-center mt-3">
                Already have an account?
                <a href="index.php">Login</a>
            </p>

            <p class="text-center mt-2">
                <a href="index.php">← Back to Home</a>
            </p>

        </form>
    </div>
</div>

</body>
</html>