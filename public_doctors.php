<?php
require_once 'config/database.php';

$sql = "SELECT 
            d.id AS doctor_id,
            d.specialization,
            d.bio,
            d.consultation_fee,
            d.experience_years,
            u.name AS doctor_name,
            u.email,
            u.mobile
        FROM doctors d
        JOIN users u ON d.user_id = u.id
        WHERE d.is_approved = 1
        AND d.status = 'approved'
        AND u.is_active = 1
        ORDER BY u.name ASC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->execute();
$doctors = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Doctors - CareConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body style="background:#f4f7f6;">

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">CareConnect</a>
        <a href="login.php?role=patient" class="btn btn-primary">Patient Login</a>
    </div>
</nav>

<div class="container py-5">

    <div class="mb-4">
        <h1>Find a Doctor</h1>
        <p class="text-muted">Browse approved doctors and book appointment after patient login.</p>
    </div>

    <div class="row g-4">
        <?php if ($doctors && $doctors->num_rows > 0): ?>
            <?php while ($doctor = $doctors->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0 h-100 rounded-4">
                        <div class="card-body">
                            <h4>Dr. <?= htmlspecialchars($doctor['doctor_name']); ?></h4>

                            <p class="mb-1">
                                <strong>Specialization:</strong>
                                <?= htmlspecialchars($doctor['specialization']); ?>
                            </p>

                            <p class="mb-1">
                                <strong>Experience:</strong>
                                <?= htmlspecialchars($doctor['experience_years']); ?> years
                            </p>

                            <p class="mb-1">
                                <strong>Fee:</strong>
                                <?= htmlspecialchars(number_format((float)$doctor['consultation_fee'], 2)); ?> BDT
                            </p>

                            <p class="text-muted mt-3">
                                <?= htmlspecialchars($doctor['bio'] ?? 'No bio available.'); ?>
                            </p>

                            <a href="login.php?role=patient" class="btn btn-primary w-100">
                                Book Appointment
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">No approved doctors found.</div>
            </div>
        <?php endif; ?>
    </div>

</div>

</body>
</html>