<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Statistics - Doctor Portal</title>
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
            border-left: 5px solid #3498db;
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
            background: #3498db;
            margin-left: 8px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
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

        .pending { border-left: 5px solid #f1c40f; }
        .confirmed { border-left: 5px solid #3498db; }
        .checked_in { border-left: 5px solid #16a085; }
        .completed { border-left: 5px solid #2ecc71; }
        .cancelled { border-left: 5px solid #e74c3c; }
        .no_show { border-left: 5px solid #c0392b; }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .progress-box {
            margin-bottom: 18px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            color: #2c3e50;
            font-weight: bold;
        }

        .progress-bar {
            background: #ecf0f1;
            border-radius: 20px;
            overflow: hidden;
            height: 18px;
        }

        .progress-fill {
            height: 18px;
            background: #3498db;
        }

        .green { background: #2ecc71; }
        .orange { background: #e67e22; }
        .red { background: #e74c3c; }

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
        }

        th {
            color: #7f8c8d;
            font-size: 13px;
            text-transform: uppercase;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #95a5a6;
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
    <div class="logo">🏥 HospitalCare | Appointment Statistics</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="btn-area">
        <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>
        <button onclick="window.print()" class="btn print-btn">🖨 Print Statistics</button>
    </div>

    <div class="card top-section">
        <h1>Appointment Statistics</h1>
        <p>Analyze your appointment performance, busiest days, and patient attendance trends.</p>
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <h3>Total Appointments</h3>
            <div class="number"><?= htmlspecialchars($totalAppointments); ?></div>
        </div>

        <div class="stat-card pending">
            <h3>Pending</h3>
            <div class="number"><?= htmlspecialchars($statusStats['pending']); ?></div>
        </div>

        <div class="stat-card confirmed">
            <h3>Confirmed</h3>
            <div class="number"><?= htmlspecialchars($statusStats['confirmed']); ?></div>
        </div>

        <div class="stat-card checked_in">
            <h3>Checked In</h3>
            <div class="number"><?= htmlspecialchars($statusStats['checked_in']); ?></div>
        </div>

        <div class="stat-card completed">
            <h3>Completed</h3>
            <div class="number"><?= htmlspecialchars($statusStats['completed']); ?></div>
        </div>

        <div class="stat-card cancelled">
            <h3>Cancelled</h3>
            <div class="number"><?= htmlspecialchars($statusStats['cancelled']); ?></div>
        </div>

        <div class="stat-card no_show">
            <h3>No Show</h3>
            <div class="number"><?= htmlspecialchars($statusStats['no_show']); ?></div>
        </div>

    </div>

    <div class="card">
        <h2>Performance Rates</h2>

        <div class="progress-box">
            <div class="progress-label">
                <span>Completion Rate</span>
                <span><?= htmlspecialchars(number_format($completionRate, 2)); ?>%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill green" style="width: <?= htmlspecialchars($completionRate); ?>%;"></div>
            </div>
        </div>

        <div class="progress-box">
            <div class="progress-label">
                <span>Cancellation Rate</span>
                <span><?= htmlspecialchars(number_format($cancelRate, 2)); ?>%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill orange" style="width: <?= htmlspecialchars($cancelRate); ?>%;"></div>
            </div>
        </div>

        <div class="progress-box">
            <div class="progress-label">
                <span>No-show Rate</span>
                <span><?= htmlspecialchars(number_format($noShowRate, 2)); ?>%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill red" style="width: <?= htmlspecialchars($noShowRate); ?>%;"></div>
            </div>
        </div>
    </div>

    <div class="grid-2">

        <div class="card">
            <h2>Busiest Days</h2>

            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Total Appointments</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($busiestDays && $busiestDays->num_rows > 0): ?>
                        <?php while ($day = $busiestDays->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($day['day_name']); ?></td>
                                <td><?= htmlspecialchars($day['total']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="empty">
                                No day statistics found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Busiest Times</h2>

            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Total Appointments</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($busiestTimes && $busiestTimes->num_rows > 0): ?>
                        <?php while ($time = $busiestTimes->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($time['appointment_time']); ?></td>
                                <td><?= htmlspecialchars($time['total']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="empty">
                                No time statistics found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="card">
        <h2>Monthly Appointment Summary</h2>

        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total</th>
                    <th>Completed</th>
                    <th>Cancelled</th>
                    <th>No Show</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($monthlyStats && $monthlyStats->num_rows > 0): ?>
                    <?php while ($month = $monthlyStats->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($month['month_name']); ?></td>
                            <td><?= htmlspecialchars($month['total']); ?></td>
                            <td><?= htmlspecialchars($month['completed']); ?></td>
                            <td><?= htmlspecialchars($month['cancelled']); ?></td>
                            <td><?= htmlspecialchars($month['no_show']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty">
                            No monthly statistics found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>