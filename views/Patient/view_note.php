<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescription Details - Patient Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/view_note.css">
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