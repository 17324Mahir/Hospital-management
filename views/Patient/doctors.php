<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Doctors - Patient Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/doctors.css">
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Browse Doctors</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="dashboard.php" class="btn back-btn">⬅ Back to Dashboard</a>

    <div class="top-section">
        <h1>Browse Approved Doctors</h1>
        <p>Search doctors by name or specialization and book an appointment.</p>

        <div class="search-box">
            <input type="text" id="doctorSearch" placeholder="Search by doctor name or specialization...">
        </div>
    </div>

    <?php if ($doctors && $doctors->num_rows > 0): ?>

        <div class="doctor-grid" id="doctorGrid">

            <?php while ($doctor = $doctors->fetch_assoc()): ?>
                <div class="doctor-card">
                    <h3>Dr. <?= htmlspecialchars($doctor['doctor_name']); ?></h3>

                    <p class="doctor-info">
                        <strong>Specialization:</strong>
                        <?= htmlspecialchars($doctor['specialization']); ?>
                    </p>

                    <p class="doctor-info">
                        <strong>Experience:</strong>
                       <?= htmlspecialchars($doctor['experience_years'] ?? '0'); ?> years
                    </p>

                    <p class="doctor-info fee">
                        Fee: <?= htmlspecialchars($doctor['consultation_fee']); ?> BDT
                    </p>

                    <p class="doctor-info">
                        <strong>Email:</strong>
                        <?= htmlspecialchars($doctor['email']); ?>
                    </p>

                    <a class="btn" href="dashboard.php?action=book_appointment&doctor_id=<?= $doctor['doctor_id']; ?>">
                       Book Appointment
                    </a>
                </div>
            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="empty">
            No approved doctors found.
        </div>

    <?php endif; ?>

</div>

<script>
    const searchInput = document.getElementById("doctorSearch");
    const doctorCards = document.querySelectorAll(".doctor-card");

    searchInput.addEventListener("keyup", function () {
        const searchValue = this.value.toLowerCase();

        doctorCards.forEach(function (card) {
            const cardText = card.innerText.toLowerCase();

            if (cardText.includes(searchValue)) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    });
</script>

</body>
</html>