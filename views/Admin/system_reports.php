<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Reports - Admin Portal</title>
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
            max-width: 1250px;
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
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .print-btn {
            background: #34495e;
            margin-left: 8px;
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

        .status-pill, .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .checked_in {
            background: #d4edda;
            color: #155724;
        }

        .completed {
            background: #dff9fb;
            color: #130f40;
        }

        .cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .no_show {
            background: #f5c6cb;
            color: #721c24;
        }

        .admin {
            background: #fdecea;
            color: #922b21;
        }

        .doctor {
            background: #d4edda;
            color: #155724;
        }

        .patient {
            background: #dff9fb;
            color: #130f40;
        }

        .receptionist {
            background: #efe2ff;
            color: #4a235a;
        }

        .empty {
            text-align: center;
            padding: 30px;
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

            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | System Reports</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="btn-area">
        <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>
        <button onclick="window.print()" class="btn print-btn">🖨 Print Report</button>
    </div>

    <div class="card top-section">
        <h1>System Reports</h1>
        <p>Overall hospital system statistics and appointment performance report.</p>
        <p>
            <strong>Generated At:</strong>
            <?= htmlspecialchars(date('Y-m-d H:i:s')); ?>
        </p>
    </div>

    <div class="grid-2">

        <div class="card">
            <h2>User Role Summary</h2>

            <table>
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Total</th>
                        <th>Active</th>
                        <th>Inactive</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($userRoleSummary && $userRoleSummary->num_rows > 0): ?>
                        <?php while ($role = $userRoleSummary->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <span class="badge <?= htmlspecialchars($role['role']); ?>">
                                        <?= htmlspecialchars(ucfirst($role['role'])); ?>
                                    </span>
                                </td>

                                <td><?= htmlspecialchars($role['total']); ?></td>
                                <td><?= htmlspecialchars($role['active_count']); ?></td>
                                <td><?= htmlspecialchars($role['inactive_count']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="empty">No user data found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Appointment Status Summary</h2>

            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($appointmentStatusSummary && $appointmentStatusSummary->num_rows > 0): ?>
                        <?php while ($status = $appointmentStatusSummary->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <span class="status-pill <?= htmlspecialchars($status['status']); ?>">
                                        <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $status['status']))); ?>
                                    </span>
                                </td>

                                <td><?= htmlspecialchars($status['total']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="empty">No appointment status data found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="card">
        <h2>Doctor-wise Appointment Summary</h2>

        <table>
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Total</th>
                    <th>Completed</th>
                    <th>Cancelled</th>
                    <th>No Show</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($doctorWiseSummary && $doctorWiseSummary->num_rows > 0): ?>
                    <?php while ($doctor = $doctorWiseSummary->fetch_assoc()): ?>
                        <tr>
                            <td>
                                Dr. <?= htmlspecialchars($doctor['doctor_name']); ?>
                                <br>
                                <span class="small">ID: #<?= htmlspecialchars($doctor['doctor_id']); ?></span>
                            </td>

                            <td><?= htmlspecialchars($doctor['specialization'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($doctor['total_appointments']); ?></td>
                            <td><?= htmlspecialchars($doctor['completed_count']); ?></td>
                            <td><?= htmlspecialchars($doctor['cancelled_count']); ?></td>
                            <td><?= htmlspecialchars($doctor['no_show_count']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty">No doctor-wise data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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
                <?php if ($monthlySummary && $monthlySummary->num_rows > 0): ?>
                    <?php while ($month = $monthlySummary->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($month['report_month']); ?></td>
                            <td><?= htmlspecialchars($month['total_appointments']); ?></td>
                            <td><?= htmlspecialchars($month['completed_count']); ?></td>
                            <td><?= htmlspecialchars($month['cancelled_count']); ?></td>
                            <td><?= htmlspecialchars($month['no_show_count']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty">No monthly data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Recent Appointments</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Booked By</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($recentAppointments && $recentAppointments->num_rows > 0): ?>
                    <?php while ($appointment = $recentAppointments->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($appointment['appointment_id']); ?></td>
                            <td><?= htmlspecialchars($appointment['appointment_date']); ?></td>
                            <td><?= htmlspecialchars($appointment['appointment_time']); ?></td>
                            <td><?= htmlspecialchars($appointment['patient_name'] ?? '-'); ?></td>
                            <td>
                                Dr. <?= htmlspecialchars($appointment['doctor_name'] ?? '-'); ?>
                                <br>
                                <span class="small"><?= htmlspecialchars($appointment['specialization'] ?? '-'); ?></span>
                            </td>
                            <td><?= htmlspecialchars($appointment['reason'] ?? '-'); ?></td>
                            <td>
                                <span class="status-pill <?= htmlspecialchars($appointment['status']); ?>">
                                    <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $appointment['status']))); ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars(ucfirst($appointment['booked_by'] ?? '-')); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty">No recent appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>