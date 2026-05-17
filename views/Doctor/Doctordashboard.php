<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard - Hospital System</title>
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

        .welcome-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            border-left: 5px solid #3498db;
            box-shadow: 0 4px 6px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .status-warning {
            background: #fff3cd;
            color: #856404;
            padding: 12px;
            border-radius: 6px;
            margin-top: 12px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 10px;
        }

        .quick-actions {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.08);
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

        .profile { background: #8e44ad; }
        .availability { background: #16a085; }
        .leave { background: #e67e22; }
        .today { background: #3498db; }
        .calendar { background: #2ecc71; }
        .earnings { background: #f39c12; }
        .reviews { background: #9b59b6; }
        .followups { background: #34495e; }

        .doctor-info {
            margin-top: 12px;
            color: #555;
            line-height: 1.7;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Doctor Portal</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="welcome-section">
        <h1>Welcome, Dr. <?= htmlspecialchars($doctor['name']); ?>!</h1>

        <div class="doctor-info">
            <strong>Specialization:</strong> <?= htmlspecialchars($doctor['specialization'] ?? 'Not set'); ?><br>
            <strong>License Number:</strong> <?= htmlspecialchars($doctor['license_number']); ?><br>
            <strong>Consultation Fee:</strong> <?= htmlspecialchars($doctor['consultation_fee']); ?> BDT<br>
            <strong>Experience:</strong> <?= htmlspecialchars($doctor['experience_years']); ?> years
        </div>

        <?php if ($doctor['is_approved'] != 1 || $doctor['status'] !== 'approved'): ?>
            <div class="status-warning">
                Your doctor account is not approved yet. Some features may be limited.
            </div>
        <?php endif; ?>
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <h3>Today's Appointments</h3>
            <div class="number"><?= htmlspecialchars($todayAppointments); ?></div>
        </div>

        <div class="stat-card">
            <h3>Pending Requests</h3>
            <div class="number"><?= htmlspecialchars($pendingAppointments); ?></div>
        </div>

        <div class="stat-card">
            <h3>Completed Appointments</h3>
            <div class="number"><?= htmlspecialchars($completedAppointments); ?></div>
        </div>

        <div class="stat-card">
            <h3>Total Earnings</h3>
            <div class="number"><?= htmlspecialchars($totalEarnings); ?> BDT</div>
        </div>

    </div>

    <div class="quick-actions">
        <h2>Quick Actions</h2>

        <div class="action-grid">

            <a href="Dashboard.php?action=profile" class="btn profile">
                👤 Professional Profile
            </a>

            <a href="Dashboard.php?action=availability" class="btn availability">
                🗓 Weekly Availability
            </a>

            <a href="Dashboard.php?action=leave_dates" class="btn leave">
                🚫 Leave Dates
            </a>

            <a href="Dashboard.php?action=today_appointments" class="btn today">
                📋 Today's Schedule
            </a>

            <a href="Dashboard.php?action=pending_appointments" class="btn" style="background:#f39c12;">
                ✅ Pending Requests
             </a>
             <a href="Dashboard.php?action=complete_appointment" class="btn" style="background:#2ecc71;">
                    ✅ Complete Appointment
              </a>

            <a href="Dashboard.php?action=patient_history" class="btn" style="background:#34495e;">
                  📁 Patient History
            </a>

            <a href="Dashboard.php?action=earnings" class="btn earnings">
                💰 Earnings Report
            </a>
             <a href="Dashboard.php?action=statistics" class="btn" style="background:#3498db;">
                  📊 Appointment Statistics
            </a>

            <a href="Dashboard.php?action=reviews" class="btn reviews">
                ⭐ Patient Reviews
            </a>

            <a href="Dashboard.php?action=followups" class="btn followups">
                🔁 Follow-ups
            </a>
           
           
        </div>
    </div>

</div>

</body>
</html>