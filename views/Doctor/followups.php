<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upcoming Follow-ups - Doctor Portal</title>
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
            max-width: 1150px;
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
            border-left: 5px solid #34495e;
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

        .follow-card {
            background: #f8f9fa;
            border-left: 5px solid #2ecc71;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .follow-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .meta {
            color: #7f8c8d;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: bold;
            color: #2c3e50;
            margin-top: 12px;
        }

        .text-box {
            background: white;
            border: 1px solid #eee;
            padding: 12px;
            border-radius: 6px;
            white-space: pre-line;
            margin-top: 6px;
            line-height: 1.6;
        }

        .badge {
            display: inline-block;
            background: #d4edda;
            color: #155724;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #95a5a6;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media print {
            .navbar, .btn {
                display: none;
            }

            body {
                background: white;
            }

            .container {
                padding: 0;
            }

            .card, .follow-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Upcoming Follow-ups</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Upcoming Follow-ups</h1>
        <p>Patients with future follow-up dates from your consultation notes.</p>
    </div>

    <div class="card">
        <?php if ($followups && $followups->num_rows > 0): ?>
            <?php while ($follow = $followups->fetch_assoc()): ?>
                <div class="follow-card">
                    <h3>
                        <?= htmlspecialchars($follow['patient_name']); ?>
                        <span class="badge">
                            Follow-up: <?= htmlspecialchars($follow['follow_up_date']); ?>
                        </span>
                    </h3>

                    <div class="meta">
                        Patient ID: <?= htmlspecialchars($follow['patient_id']); ?><br>
                        Phone: <?= htmlspecialchars($follow['patient_mobile']); ?><br>

                        For:
                        <?php if (!empty($follow['dependent_name'])): ?>
                            <?= htmlspecialchars($follow['dependent_name']); ?>
                            -
                            <?= htmlspecialchars($follow['relationship']); ?>
                        <?php else: ?>
                            Self
                        <?php endif; ?>
                        <br>

                        Original Appointment:
                        <?= htmlspecialchars($follow['appointment_date']); ?>
                        <?= htmlspecialchars($follow['appointment_time']); ?>
                        <br>

                        Reason:
                        <?= htmlspecialchars($follow['reason']); ?>
                    </div>

                    <div class="section-title">Diagnosis</div>
                    <div class="text-box">
                        <?= htmlspecialchars($follow['diagnosis']); ?>
                    </div>

                    <div class="section-title">Prescription</div>
                    <div class="text-box">
                        <?= htmlspecialchars($follow['prescription']); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty">
                No upcoming follow-ups found.
            </div>
        <?php endif; ?>
    </div>

</div>

</body>
</html>