<?php
// Must step out of patient/ and views/ to access config/
require_once '../../config/database.php';

if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    die("Access Denied: Invalid registration tracking token.");
}
$user_id = intval($_GET['user_id']);

$message = "";
$status = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gender = $_POST['gender'] ?? '';
    $blood_group = $_POST['blood_group'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $emergency_contact = trim($_POST['emergency_contact'] ?? '');

    if (!empty($gender) && !empty($blood_group) && !empty($address) && !empty($emergency_contact)) {
        $sql = "INSERT INTO patients (user_id, gender, blood_group, address, emergency_contact) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $gender, $blood_group, $address, $emergency_contact);

        if ($stmt->execute()) {
            $status = "success";
            $message = "Medical profile saved successfully! You can now log in.";
        } else {
            $status = "error";
            $message = "Database error: " . $conn->error;
        }
        $stmt->close();
    } else {
        $status = "error";
        $message = "Please fill in all mandatory profile fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Medical Profile | CareConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; height: 100vh; display: flex; align-items: center; }
        .profile-box { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.05); max-width: 550px; margin: auto; }
    </style>
</head>
<body>
<div class="container">
    <div class="profile-box">
        <h3 class="text-center mb-2">Medical Profile Setup</h3>
        <p class="text-muted text-center mb-4">Please provide additional details to complete your patient file.</p>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $status === 'success' ? 'success' : 'danger' ?> text-center">
                <?= $message ?>
                <?php if ($status === 'success'): ?>
                    <!-- Steps out of patient/ and views/ to hit root login -->
                    <br><a href="../../index.php" class="btn btn-primary btn-sm mt-2">Go to Login</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($status !== 'success'): ?>
        <form action="" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Gender *</label>
                    <select name="gender" class="form-select" required>
                        <option value="" disabled selected>Select...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Blood Group *</label>
                    <select name="blood_group" class="form-select" required>
                        <option value="" disabled selected>Select...</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Emergency Contact Number *</label>
                    <input type="text" name="emergency_contact" class="form-control" placeholder="01XXXXXXXXX" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Permanent Address *</label>
                    <textarea name="address" class="form-control" rows="3" placeholder="Enter your full street address" required></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100 mt-4">Complete Registration</button>
        </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>