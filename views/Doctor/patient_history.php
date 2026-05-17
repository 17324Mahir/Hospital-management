<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Consultation History - Doctor Portal</title>
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
            padding: 9px 14px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .view-btn {
            background: #34495e;
            margin-bottom: 0;
            padding: 6px 10px;
            font-size: 12px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }

        .patient-card {
            border-left: 4px solid #34495e;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .patient-card h3 {
            margin: 0 0 8px;
            color: #2c3e50;
        }

        .small {
            color: #7f8c8d;
            font-size: 13px;
            line-height: 1.6;
        }

        .info-box {
            background: #f8f9fa;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 18px;
            line-height: 1.7;
        }

        .history-note {
            border-left: 4px solid #2ecc71;
            background: #f8f9fa;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .history-note h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .section-title {
            color: #2c3e50;
            font-weight: bold;
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

        .empty {
            text-align: center;
            padding: 35px;
            color: #95a5a6;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .navbar {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Patient History</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Patient Consultation History</h1>
        <p>View all consultation notes for patients you have treated.</p>
    </div>

    <div class="grid">

        <div class="card">
            <h2>Patients</h2>

            <?php if ($patients && $patients->num_rows > 0): ?>
                <?php while ($patient = $patients->fetch_assoc()): ?>
                    <div class="patient-card">
                        <h3><?= htmlspecialchars($patient['patient_name']); ?></h3>

                        <div class="small">
                            Patient ID: <?= htmlspecialchars($patient['patient_id']); ?><br>
                            Phone: <?= htmlspecialchars($patient['patient_mobile']); ?><br>
                            Gender: <?= htmlspecialchars($patient['gender']); ?><br>
                            Blood: <?= htmlspecialchars($patient['blood_group']); ?>
                        </div>

                        <br>

                        <a 
                            href="Dashboard.php?action=patient_history&patient_id=<?= htmlspecialchars($patient['patient_id']); ?>"
                            class="btn view-btn"
                        >
                            View History
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty">
                    No patient consultation history found.
                </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>Consultation Records</h2>

            <?php if ($selectedPatient): ?>
                <div class="info-box">
                    <strong>Patient:</strong> <?= htmlspecialchars($selectedPatient['patient_name']); ?><br>
                    <strong>Email:</strong> <?= htmlspecialchars($selectedPatient['email']); ?><br>
                    <strong>Phone:</strong> <?= htmlspecialchars($selectedPatient['mobile']); ?><br>
                    <strong>Gender:</strong> <?= htmlspecialchars($selectedPatient['gender']); ?><br>
                    <strong>Blood Group:</strong> <?= htmlspecialchars($selectedPatient['blood_group']); ?><br>
                    <strong>Address:</strong> <?= htmlspecialchars($selectedPatient['address']); ?><br>
                    <strong>Emergency Contact:</strong>
                    <?= htmlspecialchars($selectedPatient['emergency_contact_name'] ?? '-'); ?>
                    -
                    <?= htmlspecialchars($selectedPatient['emergency_contact_phone'] ?? '-'); ?><br>
                    <strong>Medical History:</strong>
                    <?= htmlspecialchars($selectedPatient['medical_history_notes'] ?? 'No medical history notes.'); ?>
                </div>

                <?php if ($history && $history->num_rows > 0): ?>
                    <?php while ($note = $history->fetch_assoc()): ?>
                        <div class="history-note">
                            <h3>
                                Appointment:
                                <?= htmlspecialchars($note['appointment_date']); ?>
                                <?= htmlspecialchars($note['appointment_time']); ?>
                            </h3>

                            <div class="small">
                                <strong>For:</strong>
                                <?php if (!empty($note['dependent_name'])): ?>
                                    <?= htmlspecialchars($note['dependent_name']); ?>
                                    -
                                    <?= htmlspecialchars($note['relationship']); ?>
                                <?php else: ?>
                                    Self
                                <?php endif; ?>
                                <br>

                                <strong>Reason:</strong>
                                <?= htmlspecialchars($note['reason']); ?>
                                <br>

                                <strong>Created:</strong>
                                <?= htmlspecialchars($note['created_at']); ?>
                                <br>

                                <strong>Follow-up:</strong>
                                <?= !empty($note['follow_up_date'])
                                    ? htmlspecialchars($note['follow_up_date'])
                                    : '-'; ?>
                            </div>

                            <div class="section-title">Symptoms</div>
                            <div class="text-box">
                                <?= htmlspecialchars($note['symptoms']); ?>
                            </div>

                            <div class="section-title">Diagnosis</div>
                            <div class="text-box">
                                <?= htmlspecialchars($note['diagnosis']); ?>
                            </div>

                            <div class="section-title">Prescription</div>
                            <div class="text-box">
                                <?= htmlspecialchars($note['prescription']); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty">
                        No consultation records found for this patient.
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="empty">
                    Select a patient from the left side to view history.
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>

</body>
</html>