<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Hospital System</title>
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

        .welcome-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            border-left: 5px solid #e74c3c;
            box-shadow: 0 4px 6px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .admin-info {
            color: #555;
            line-height: 1.7;
            margin-top: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 18px;
            margin-bottom: 25px;
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
            font-size: 31px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 10px;
        }

        .quick-actions {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .btn {
            display: block;
            color: white;
            padding: 13px 16px;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
            font-weight: 500;
        }

        .users { background: #3498db; }
        .doctors { background: #27ae60; }
        .patients { background: #16a085; }
        .receptionists { background: #8e44ad; }
        .specializations { background: #2ecc71; }
        .announcements { background: #9b59b6; }
        .policies { background: #e67e22; }
        .reports { background: #34495e; }
        .billing { background: #f39c12; }
        .complaints { background: #e74c3c; }
        .logs { background: #7f8c8d; }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
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

        .empty {
            text-align: center;
            padding: 30px;
            color: #95a5a6;
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
    <div class="logo">🏥 HospitalCare | Admin Portal</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="welcome-section">
        <h1>Welcome, <?= htmlspecialchars($admin['name']); ?>!</h1>

        <div class="admin-info">
            <strong>Email:</strong> <?= htmlspecialchars($admin['email']); ?><br>
            <strong>Phone:</strong> <?= htmlspecialchars($admin['mobile']); ?><br>
            <strong>Role:</strong> Administrator
        </div>
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <h3>Total Admins</h3>
            <div class="number"><?= htmlspecialchars($totalAdmins); ?></div>
        </div>

        <div class="stat-card">
            <h3>Total Doctors</h3>
            <div class="number"><?= htmlspecialchars($totalDoctors); ?></div>
        </div>

        <div class="stat-card">
            <h3>Total Patients</h3>
            <div class="number"><?= htmlspecialchars($totalPatients); ?></div>
        </div>

        <div class="stat-card">
            <h3>Total Receptionists</h3>
            <div class="number"><?= htmlspecialchars($totalReceptionists); ?></div>
        </div>

        <div class="stat-card">
            <h3>Total Appointments</h3>
            <div class="number"><?= htmlspecialchars($totalAppointments); ?></div>
        </div>

        <div class="stat-card">
            <h3>Today's Appointments</h3>
            <div class="number"><?= htmlspecialchars($todayAppointments); ?></div>
        </div>

        <div class="stat-card">
            <h3>Pending Doctors</h3>
            <div class="number"><?= htmlspecialchars($pendingDoctors); ?></div>
        </div>

        <div class="stat-card">
            <h3>Total Revenue</h3>
            <div class="number"><?= htmlspecialchars(number_format((float)$totalRevenue, 2)); ?> BDT</div>
        </div>

        <div class="stat-card">
            <h3>Pending Bills</h3>
            <div class="number"><?= htmlspecialchars($pendingBills); ?></div>
        </div>

    </div>

    <div class="quick-actions">
        <h2>Quick Actions</h2>

        <div class="action-grid">

            <a href="Dashboard.php?action=manage_users" class="btn users">
                👥 Manage Users
            </a>

            <a href="Dashboard.php?action=doctor_approvals" class="btn doctors">
                ✅ Doctor Approvals
            </a>

            <a href="Dashboard.php?action=manage_doctors" class="btn doctors">
                🩺 Manage Doctors
            </a>

            <a href="Dashboard.php?action=manage_patients" class="btn patients">
                🧑 Manage Patients
            </a>

            <a href="Dashboard.php?action=manage_receptionists" class="btn receptionists">
                🧾 Manage Receptionists
            </a>

            <a href="Dashboard.php?action=specializations" class="btn specializations">
                🏷 Specializations
            </a>

            <a href="Dashboard.php?action=announcements" class="btn announcements">
                📢 Announcements
            </a>

            <a href="Dashboard.php?action=appointment_policies" class="btn policies">
                ⚙ Appointment Policies
            </a>

            <a href="Dashboard.php?action=system_reports" class="btn reports">
                📊 System Reports
            </a>

            <a href="Dashboard.php?action=billing_report" class="btn billing">
                💳 Billing / Revenue
            </a>

            <a href="Dashboard.php?action=complaints" class="btn complaints">
                📝 Complaints
            </a>

            <a href="Dashboard.php?action=activity_logs" class="btn logs">
                📜 Activity Logs
            </a>

        </div>
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
                    <th>Specialization</th>
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
                            <td><?= htmlspecialchars($appointment['patient_name']); ?></td>
                            <td>Dr. <?= htmlspecialchars($appointment['doctor_name']); ?></td>
                            <td><?= htmlspecialchars($appointment['specialization']); ?></td>
                            <td>
                                <span class="status-pill <?= htmlspecialchars($appointment['status']); ?>">
                                    <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $appointment['status']))); ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars(ucfirst($appointment['booked_by'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty">
                            No recent appointments found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>