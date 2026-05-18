<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Hospital System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Admindashboard.css">

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