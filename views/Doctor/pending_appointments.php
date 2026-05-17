<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Appointments - Doctor Portal</title>
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
            border-left: 5px solid #f39c12;
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
        }

        .confirm-btn {
            background: #27ae60;
            margin-bottom: 0;
            padding: 6px 10px;
            font-size: 12px;
        }

        .reject-btn {
            background: #e74c3c;
            margin-bottom: 0;
            padding: 6px 10px;
            font-size: 12px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
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

        .status-pill {
            background: #fff3cd;
            color: #856404;
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

        .small {
            color: #7f8c8d;
            font-size: 12px;
        }

        .action-links {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 10px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Pending Appointments</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Pending Appointment Requests</h1>
        <p>Confirm or reject appointment requests submitted by patients or receptionists.</p>
    </div>

    <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
        <div class="success">Appointment status updated successfully.</div>
    <?php endif; ?>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>For</th>
                    <th>Phone</th>
                    <th>Reason</th>
                    <th>Booked By</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($appointments && $appointments->num_rows > 0): ?>
                    <?php while ($appointment = $appointments->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['appointment_date']); ?></td>

                            <td><?= htmlspecialchars($appointment['appointment_time']); ?></td>

                            <td>
                                <?= htmlspecialchars($appointment['patient_name']); ?>
                                <br>
                                <span class="small">Patient ID: <?= htmlspecialchars($appointment['patient_id']); ?></span>
                            </td>

                            <td>
                                <?php if (!empty($appointment['dependent_name'])): ?>
                                    <?= htmlspecialchars($appointment['dependent_name']); ?>
                                    <br>
                                    <span class="small"><?= htmlspecialchars($appointment['relationship']); ?></span>
                                <?php else: ?>
                                    Self
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($appointment['patient_mobile']); ?></td>

                            <td><?= htmlspecialchars($appointment['reason']); ?></td>

                            <td><?= htmlspecialchars(ucfirst($appointment['booked_by'])); ?></td>

                            <td>
                                <span class="status-pill">Pending</span>
                            </td>

                            <td>
                                <div class="action-links">
                                    <a 
                                        href="Dashboard.php?action=update_appointment_status&id=<?= htmlspecialchars($appointment['appointment_id']); ?>&status=confirmed"
                                        onclick="return confirm('Confirm this appointment?');"
                                        class="btn confirm-btn"
                                    >
                                        Confirm
                                    </a>

                                    <a 
                                        href="Dashboard.php?action=update_appointment_status&id=<?= htmlspecialchars($appointment['appointment_id']); ?>&status=cancelled"
                                        onclick="return confirm('Reject this appointment?');"
                                        class="btn reject-btn"
                                    >
                                        Reject
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="empty">
                            No pending appointments found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>