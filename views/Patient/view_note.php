<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescription Details - Patient Portal</title>
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
            max-width: 900px;
            margin: auto;
            padding: 30px;
        }

        .btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            border: none;
            cursor: pointer;
        }

        .print-btn {
            background: #2ecc71;
            margin-left: 8px;
        }

        .prescription {
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            border-top: 6px solid #2ecc71;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            color: #2c3e50;
        }

        .header p {
            margin: 6px 0 0;
            color: #7f8c8d;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
        }

        .info-box strong {
            color: #2c3e50;
            display: block;
            margin-bottom: 5px;
        }

        .section {
            margin-top: 25px;
        }

        .section h3 {
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .text-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            white-space: pre-line;
            line-height: 1.6;
            color: #333;
        }

        @media print {
            .navbar, .btn-area {
                display: none;
            }

            body {
                background: white;
            }

            .container {
                padding: 0;
            }

            .prescription {
                box-shadow: none;
                border-radius: 0;
            }
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Prescription Details</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="btn-area">
        <a href="Dashboard.php?action=consultation_notes" class="btn">⬅ Back to Notes</a>
        <button onclick="window.print()" class="btn print-btn">🖨 Print Prescription</button>
    </div>

    <div class="prescription">

        <div class="header">
            <h1>HospitalCare</h1>
            <p>Consultation Notes & Prescription</p>
        </div>

        <div class="info-grid">

            <div class="info-box">
                <strong>Doctor</strong>
                Dr. <?= htmlspecialchars($note['doctor_name']); ?>
                <br>
                <?= htmlspecialchars($note['specialization']); ?>
            </div>

            <div class="info-box">
                <strong>Appointment Date & Time</strong>
                <?= htmlspecialchars($note['appointment_date']); ?>
                <br>
                <?= htmlspecialchars($note['appointment_time']); ?>
            </div>

            <div class="info-box">
                <strong>Patient</strong>
                <?php if (!empty($note['dependent_name'])): ?>
                    <?= htmlspecialchars($note['dependent_name']); ?>
                    <br>
                    <?= htmlspecialchars($note['relationship']); ?>
                <?php else: ?>
                    <?= htmlspecialchars($_SESSION['name']); ?> 
                    <br>
                    Self
                <?php endif; ?>
            </div>

            <div class="info-box">
                <strong>Follow-up Date</strong>
                <?= !empty($note['follow_up_date']) 
                    ? htmlspecialchars($note['follow_up_date']) 
                    : 'No follow-up date'; ?>
            </div>

        </div>

        <div class="section">
            <h3>Symptoms</h3>
            <div class="text-box">
                <?= htmlspecialchars($note['symptoms'] ?? 'No symptoms recorded.'); ?>
            </div>
        </div>

        <div class="section">
            <h3>Diagnosis</h3>
            <div class="text-box">
                <?= htmlspecialchars($note['diagnosis'] ?? 'No diagnosis recorded.'); ?>
            </div>
        </div>

        <div class="section">
            <h3>Prescription</h3>
            <div class="text-box">
                <?= htmlspecialchars($note['prescription'] ?? 'No prescription recorded.'); ?>
            </div>
        </div>

        <div class="section">
            <p style="text-align:right; margin-top:40px;">
                ___________________________<br>
                Doctor Signature
            </p>
        </div>

    </div>

</div>

</body>
</html>