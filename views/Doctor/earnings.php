<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earnings Report - Doctor Portal</title>
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
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .print-btn {
            background: #f39c12;
            margin-left: 8px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
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
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 10px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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

        @media (max-width: 900px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }

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

        @media print {
            .navbar, .btn-area {
                display: none;
            }

            body {
                background: white;
            }

            .container {
                padding: 0;
                max-width: 100%;
            }

            .card, .stat-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Earnings Report</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="btn-area">
        <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>
        <button onclick="window.print()" class="btn print-btn">🖨 Print Report</button>
    </div>

    <div class="card top-section">
        <h1>Earnings Report</h1>
        <p>Completed appointment earnings based on your consultation fee.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Completed</h3>
            <div class="number">
                <?= htmlspecialchars($summary['completed_count'] ?? 0); ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>Total Earnings</h3>
            <div class="number">
                <?= htmlspecialchars(number_format((float)($summary['total_earnings'] ?? 0), 2)); ?> BDT
            </div>
        </div>

        <div class="stat-card">
            <h3>Consultation Fee</h3>
            <div class="number">
                <?= htmlspecialchars(number_format((float)$doctor['consultation_fee'], 2)); ?> BDT
            </div>
        </div>
    </div>

    <div class="grid-2">

        <div class="card">
            <h2>Daily Earnings</h2>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Completed</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($dailyEarnings && $dailyEarnings->num_rows > 0): ?>
                        <?php while ($day = $dailyEarnings->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($day['appointment_date']); ?></td>
                                <td><?= htmlspecialchars($day['completed_count']); ?></td>
                                <td><?= htmlspecialchars(number_format((float)$day['total_amount'], 2)); ?> BDT</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="empty">
                                No daily earnings found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Monthly Earnings</h2>

            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Completed</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($monthlyEarnings && $monthlyEarnings->num_rows > 0): ?>
                        <?php while ($month = $monthlyEarnings->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($month['earning_month']); ?></td>
                                <td><?= htmlspecialchars($month['completed_count']); ?></td>
                                <td><?= htmlspecialchars(number_format((float)$month['total_amount'], 2)); ?> BDT</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="empty">
                                No monthly earnings found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="card">
        <h2>Completed Appointment Details</h2>

        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>For</th>
                    <th>Fee</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($completedAppointments && $completedAppointments->num_rows > 0): ?>
                    <?php while ($appointment = $completedAppointments->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($appointment['appointment_id']); ?></td>

                            <td><?= htmlspecialchars($appointment['appointment_date']); ?></td>

                            <td><?= htmlspecialchars($appointment['appointment_time']); ?></td>

                            <td><?= htmlspecialchars($appointment['patient_name']); ?></td>

                            <td>
                                <?php if (!empty($appointment['dependent_name'])): ?>
                                    <?= htmlspecialchars($appointment['dependent_name']); ?>
                                    <br>
                                    <span class="small"><?= htmlspecialchars($appointment['relationship']); ?></span>
                                <?php else: ?>
                                    Self
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(number_format((float)$appointment['consultation_fee'], 2)); ?> BDT
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty">
                            No completed appointments found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>