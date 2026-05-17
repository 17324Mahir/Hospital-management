<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consultation Notes - Patient Portal</title>
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
            font-size: 14px;
        }

        .view-btn {
            background: #2ecc71;
            margin-bottom: 0;
            padding: 7px 12px;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            vertical-align: top;
        }

        th {
            color: #7f8c8d;
            font-size: 13px;
            text-transform: uppercase;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #95a5a6;
        }

        .small {
            color: #7f8c8d;
            font-size: 12px;
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Consultation Notes</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Consultation Notes & Prescriptions</h1>
        <p>View notes and prescriptions added by doctors after completed consultations.</p>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Appointment</th>
                    <th>For</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Diagnosis</th>
                    <th>Follow-up</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($notes && $notes->num_rows > 0): ?>
                    <?php while ($note = $notes->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($note['appointment_date']); ?>
                                <br>
                                <span class="small"><?= htmlspecialchars($note['appointment_time']); ?></span>
                            </td>

                            <td>
                                <?php if (!empty($note['dependent_name'])): ?>
                                    <?= htmlspecialchars($note['dependent_name']); ?>
                                    <br>
                                    <span class="small"><?= htmlspecialchars($note['relationship']); ?></span>
                                <?php else: ?>
                                    Self
                                <?php endif; ?>
                            </td>

                            <td>Dr. <?= htmlspecialchars($note['doctor_name']); ?></td>

                            <td><?= htmlspecialchars($note['specialization']); ?></td>

                            <td>
                                <?= htmlspecialchars(substr($note['diagnosis'] ?? 'No diagnosis', 0, 60)); ?>
                                <?= strlen($note['diagnosis'] ?? '') > 60 ? '...' : ''; ?>
                            </td>

                            <td>
                                <?= !empty($note['follow_up_date']) 
                                    ? htmlspecialchars($note['follow_up_date']) 
                                    : '-'; ?>
                            </td>

                            <td>
                                <a 
                                    href="Dashboard.php?action=view_note&id=<?= htmlspecialchars($note['note_id']); ?>"
                                    class="btn view-btn"
                                >
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty">
                            No consultation notes found yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>