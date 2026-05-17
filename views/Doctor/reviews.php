<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Reviews - Doctor Portal</title>
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
            max-width: 1050px;
            margin: auto;
            padding: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .top-section {
            border-left: 5px solid #9b59b6;
        }

        .btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.08);
        }

        .stat-card h3 {
            margin: 0;
            color: #7f8c8d;
            font-size: 14px;
            text-transform: uppercase;
        }

        .number {
            font-size: 34px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 10px;
        }

        .stars {
            color: #f39c12;
            font-size: 18px;
            font-weight: bold;
        }

        .review-card {
            border-left: 5px solid #f1c40f;
            background: #f8f9fa;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .review-card h3 {
            margin: 0 0 8px;
            color: #2c3e50;
        }

        .meta {
            color: #7f8c8d;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .review-text {
            white-space: pre-line;
            line-height: 1.6;
            color: #333;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #95a5a6;
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Patient Reviews</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Patient Reviews</h1>
        <p>View ratings and feedback submitted by patients after completed appointments.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Reviews</h3>
            <div class="number">
                <?= htmlspecialchars($reviewStats['total_reviews'] ?? 0); ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>Average Rating</h3>
            <div class="number">
                <?php
                    $avg = $reviewStats['average_rating'] ?? 0;
                    echo htmlspecialchars(number_format((float)$avg, 1));
                ?>/5
            </div>
        </div>
    </div>

    <div class="card">
        <h2>All Reviews</h2>

        <?php if ($reviews && $reviews->num_rows > 0): ?>
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <div class="review-card">
                    <h3><?= htmlspecialchars($review['patient_name']); ?></h3>

                    <div class="meta">
                        Patient ID: <?= htmlspecialchars($review['patient_id']); ?>
                        |
                        Appointment: <?= htmlspecialchars($review['appointment_date']); ?>
                        |
                        Submitted: <?= htmlspecialchars($review['created_at']); ?>
                    </div>

                    <div class="stars">
                        <?= str_repeat("★", (int)$review['rating']); ?>
                        <?= str_repeat("☆", 5 - (int)$review['rating']); ?>
                        <?= htmlspecialchars($review['rating']); ?>/5
                    </div>

                    <div class="review-text">
                        <?= htmlspecialchars($review['review_text']); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty">
                No patient reviews found yet.
            </div>
        <?php endif; ?>
    </div>

</div>

</body>
</html>