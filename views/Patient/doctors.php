<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Doctors - Patient Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f6;
            margin: 0;
        }

        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: #ff7675;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            padding: 30px;
        }

        .top-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            border-left: 5px solid #3498db;
            box-shadow: 0 4px 6px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .search-box {
            margin-top: 15px;
        }

        .search-box input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            box-sizing: border-box;
        }

        .doctor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }

        .doctor-card {
            background: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.08);
        }

        .doctor-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .doctor-info {
            color: #555;
            margin: 8px 0;
            font-size: 14px;
        }

        .fee {
            color: #27ae60;
            font-weight: bold;
            font-size: 16px;
        }

        .btn {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 12px;
        }

        .btn:hover {
            background: #2980b9;
        }

        .back-btn {
            background: #7f8c8d;
            margin-bottom: 20px;
        }

        .empty {
            background: white;
            padding: 30px;
            text-align: center;
            color: #888;
            border-radius: 10px;
        }
    </style>
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