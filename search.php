<?php
require_once 'config/database.php';

$q = trim($_GET['q'] ?? '');
$doctors = null;

if (!empty($q)) {
    $search = "%" . $q . "%";

    $sql = "SELECT 
                d.id AS doctor_id,
                d.specialization,
                d.bio,
                d.consultation_fee,
                d.experience_years,
                u.name AS doctor_name,
                u.mobile
            FROM doctors d
            JOIN users u ON d.user_id = u.id
            WHERE d.is_approved = 1
            AND d.status = 'approved'
            AND u.is_active = 1
            AND (
                u.name LIKE ?
                OR d.specialization LIKE ?
                OR d.bio LIKE ?
            )
            ORDER BY u.name ASC";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();

    $doctors = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Doctors - CareConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f4f7f6;">

<nav class="navbar bg-white shadow-sm">
    <div class="container">
        <a href="index.php" class="navbar-brand fw-bold">CareConnect</a>
    </div>
</nav>

<div class="container py-5">

    <h1>Search Result</h1>

    <form method="GET" action="search.php" class="my-4">
        <div class="input-group">
            <input 
                type="text" 
                name="q" 
                class="form-control"
                value="<?= htmlspecialchars($q); ?>"
                placeholder="Search doctors or departments..."
                required
            >
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <?php if (empty($q)): ?>
        <div class="alert alert-info">Please enter a search keyword.</div>
    <?php elseif ($doctors && $doctors->num_rows > 0): ?>

        <div class="row g-4">
            <?php while ($doctor = $doctors->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <h4>Dr. <?= htmlspecialchars($doctor['doctor_name']); ?></h4>

                            <p>
                                <strong>Specialization:</strong>
                                <?= htmlspecialchars($doctor['specialization']); ?>
                            </p>

                            <p>
                                <strong>Fee:</strong>
                                <?= htmlspecialchars(number_format((float)$doctor['consultation_fee'], 2)); ?> BDT
                            </p>

                            <p>
                                <strong>Experience:</strong>
                                <?= htmlspecialchars($doctor['experience_years']); ?> years
                            </p>

                            <a href="login.php?role=patient" class="btn btn-primary w-100">
                                Login to Book
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    <?php else: ?>
        <div class="alert alert-warning">
            No doctor found for: <strong><?= htmlspecialchars($q); ?></strong>
        </div>
    <?php endif; ?>

</div>

</body>
</html>