<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Appointment - Doctor Portal</title>
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
            max-width: 1000px;
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
            border-left: 5px solid #2ecc71;
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
            font-size: 15px;
        }

        .submit-btn {
            background: #2ecc71;
            margin-top: 15px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
            font-weight: bold;
            color: #2c3e50;
        }

        select, textarea, input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
        }

        textarea {
            min-height: 95px;
            resize: vertical;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #95a5a6;
        }

        .note {
            background: #eaf6ff;
            color: #2c3e50;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Complete Appointment</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Complete Appointment</h1>
        <p>Add consultation notes and prescription for checked-in patients.</p>

        <div class="note">
            Only checked-in appointments will appear here. Use Today's Schedule to check in a confirmed patient first.
        </div>
    </div>

    <div class="card">

        <?php if (!empty($message)): ?>
            <div class="success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($appointments && $appointments->num_rows > 0): ?>

            <form method="POST" action="">

                <label for="appointment_id">Select Checked-in Appointment</label>

                <select name="appointment_id" id="appointment_id" required onchange="setPatientId(this)">
                    <option value="">Choose appointment</option>

                    <?php while ($appointment = $appointments->fetch_assoc()): ?>
                        <option 
                            value="<?= htmlspecialchars($appointment['appointment_id']); ?>"
                            data-patient-id="<?= htmlspecialchars($appointment['patient_id']); ?>"
                        >
                            <?= htmlspecialchars($appointment['appointment_date']); ?>
                            <?= htmlspecialchars($appointment['appointment_time']); ?>
                            |
                            <?= htmlspecialchars($appointment['patient_name']); ?>
                            |
                            For:
                            <?php if (!empty($appointment['dependent_name'])): ?>
                                <?= htmlspecialchars($appointment['dependent_name']); ?>
                                (<?= htmlspecialchars($appointment['relationship']); ?>)
                            <?php else: ?>
                                Self
                            <?php endif; ?>
                            |
                            Reason:
                            <?= htmlspecialchars($appointment['reason']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <input type="hidden" name="patient_id" id="patient_id">

                <label for="symptoms">Symptoms *</label>
                <textarea 
                    name="symptoms" 
                    id="symptoms" 
                    placeholder="Write patient symptoms..."
                    required
                ></textarea>

                <label for="diagnosis">Diagnosis *</label>
                <textarea 
                    name="diagnosis" 
                    id="diagnosis" 
                    placeholder="Write diagnosis..."
                    required
                ></textarea>

                <label for="prescription">Prescription *</label>
                <textarea 
                    name="prescription" 
                    id="prescription" 
                    placeholder="Write prescription details..."
                    required
                ></textarea>

                <label for="follow_up_date">Follow-up Date</label>
                <input 
                    type="date" 
                    name="follow_up_date" 
                    id="follow_up_date" 
                    min="<?= date('Y-m-d'); ?>"
                >

                <button type="submit" class="btn submit-btn">Complete Appointment</button>

            </form>

        <?php else: ?>

            <div class="empty">
                No checked-in appointments available.
            </div>

        <?php endif; ?>

    </div>

</div>

<script>
function setPatientId(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const patientId = selectedOption.getAttribute("data-patient-id") || "";
    document.getElementById("patient_id").value = patientId;
}
</script>

</body>
</html>