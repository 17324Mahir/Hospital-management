<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard - Hospital System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            display: flex;
            min-height: 100vh;
            font-size: 14px;
            color: #2c3e50;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1a2332;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 20px 18px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand .brand-name {
            font-size: 15px;
            font-weight: 600;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-brand .brand-name i {
            font-size: 20px;
            color: #3b8de0;
        }

        .sidebar-brand .brand-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.3);
            margin-top: 3px;
            padding-left: 28px;
            letter-spacing: 0.03em;
        }

        .nav-section-label {
            padding: 16px 18px 6px;
            font-size: 10px;
            font-weight: 600;
            color: rgba(255,255,255,0.28);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            margin: 1px 8px;
            border-radius: 6px;
            text-decoration: none;
            color: rgba(255,255,255,0.55);
            font-size: 13.5px;
            transition: background 0.15s, color 0.15s;
        }

        .nav-item i {
            font-size: 17px;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.9);
        }

        .nav-item.active {
            background: #3b8de0;
            color: #ffffff;
            font-weight: 500;
        }

        .nav-item .nav-badge {
            margin-left: auto;
            background: rgba(231,76,60,0.9);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 14px 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255,100,90,0.85);
            text-decoration: none;
            padding: 6px 0;
        }

        .sidebar-footer a i { font-size: 17px; }
        .sidebar-footer a:hover { color: #e74c3c; }

        /* ── Main ── */
        .main-wrapper {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e6ea;
            padding: 0 28px;
            display: flex;
            align-items: stretch;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-tabs {
            display: flex;
            align-items: stretch;
        }

        .topbar-tab {
            padding: 15px 18px;
            font-size: 13.5px;
            color: #95a5a6;
            border-bottom: 2px solid transparent;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.15s, border-color 0.15s;
            white-space: nowrap;
        }

        .topbar-tab i { font-size: 16px; }
        .topbar-tab:hover { color: #2c3e50; }

        .topbar-tab.active {
            color: #2980b9;
            border-bottom-color: #3498db;
            font-weight: 500;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #7f8c8d;
        }

        .doctor-avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #d6eaf8;
            color: #2980b9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
        }

        .topbar-divider {
            width: 1px;
            height: 20px;
            background: #e2e6ea;
        }

        .topbar-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #e74c3c;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            transition: background 0.15s;
        }

        .topbar-logout i { font-size: 16px; }
        .topbar-logout:hover { background: #fdecea; }

        /* ── Content ── */
        .content {
            padding: 24px 28px;
            flex: 1;
        }

        /* ── Doctor header card ── */
        .doctor-header {
            background: linear-gradient(135deg, #1a2332 0%, #1e4976 100%);
            border-radius: 10px;
            padding: 22px 26px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
            overflow: hidden;
        }

        .doctor-header::before {
            content: '';
            position: absolute;
            right: -30px; top: -30px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .doctor-header::after {
            content: '';
            position: absolute;
            right: 60px; bottom: -40px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }

        .doc-avatar-lg {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #3b8de0;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 600;
            flex-shrink: 0;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .doc-info .doc-name {
            font-size: 18px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 3px;
        }

        .doc-info .doc-spec {
            font-size: 13px;
            color: rgba(255,255,255,0.55);
            margin-bottom: 10px;
        }

        .doc-meta {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }

        .doc-meta-item {
            font-size: 12px;
            color: rgba(255,255,255,0.45);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .doc-meta-item i { font-size: 14px; color: rgba(255,255,255,0.3); }

        .doc-header-right {
            margin-left: auto;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        .status-badge {
            font-size: 11.5px;
            font-weight: 500;
            padding: 5px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-badge i { font-size: 13px; }

        .status-badge.pending {
            background: rgba(243,156,18,0.18);
            border: 1px solid rgba(243,156,18,0.35);
            color: #f5cba7;
        }

        .status-badge.approved {
            background: rgba(39,174,96,0.18);
            border: 1px solid rgba(39,174,96,0.35);
            color: #a9dfbf;
        }

        .fee-tag {
            background: rgba(255,255,255,0.09);
            color: rgba(255,255,255,0.7);
            font-size: 12.5px;
            font-weight: 500;
            padding: 5px 14px;
            border-radius: 6px;
        }

        /* ── Stats ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e6ea;
            border-top: 3px solid transparent;
            border-radius: 8px;
            padding: 16px 20px;
        }

        .stat-card.blue   { border-top-color: #3498db; }
        .stat-card.orange { border-top-color: #f39c12; }
        .stat-card.green  { border-top-color: #2ecc71; }
        .stat-card.purple { border-top-color: #9b59b6; }

        .stat-card .stat-label {
            font-size: 11px;
            font-weight: 600;
            color: #95a5a6;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        .stat-card .stat-label i { font-size: 15px; }
        .stat-card.blue   .stat-label i { color: #3498db; }
        .stat-card.orange .stat-label i { color: #f39c12; }
        .stat-card.green  .stat-label i { color: #2ecc71; }
        .stat-card.purple .stat-label i { color: #9b59b6; }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            line-height: 1;
        }

        .stat-card.purple .stat-value {
            font-size: 20px;
            color: #1e8449;
        }


        .topbar-divider {
            width: 1px;
            height: 20px;
            background: #e2e6ea;
        }

        .topbar-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #e74c3c;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            transition: background 0.15s;
        }

        .topbar-logout i { font-size: 16px; }
        .topbar-logout:hover { background: #fdecea; }
        @media (max-width: 900px) {
            .sidebar { width: 100%; min-height: auto; position: relative; }
            .main-wrapper { margin-left: 0; }
            .doctor-header { flex-wrap: wrap; }
            .doc-header-right { margin-left: 0; align-items: flex-start; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>

<body>

<?php
$currentAction = $_GET['action'] ?? 'dashboard';
function isActive($action, $current) {
    return $action === $current ? ' active' : '';
}
?>

<!-- ── Sidebar ── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-name">
            <i class="ti ti-building-hospital" aria-hidden="true"></i>
            HospitalCare
        </div>
        <div class="brand-sub">Doctor Portal</div>
    </div>

    <div class="nav-section-label">Overview</div>
    <a href="Dashboard.php" class="nav-item<?= isActive('dashboard', $currentAction); ?>">
        <i class="ti ti-layout-dashboard" aria-hidden="true"></i> Dashboard
    </a>
    <a href="Dashboard.php?action=today_appointments" class="nav-item<?= isActive('today_appointments', $currentAction); ?>">
        <i class="ti ti-calendar-event" aria-hidden="true"></i> Today's schedule
    </a>
    <a href="Dashboard.php?action=pending_appointments" class="nav-item<?= isActive('pending_appointments', $currentAction); ?>">
        <i class="ti ti-clock-hour-4" aria-hidden="true"></i> Pending requests
        <span class="nav-badge"><?= htmlspecialchars($pendingAppointments); ?></span>
    </a>

    <div class="nav-section-label">Management</div>
    <a href="Dashboard.php?action=profile" class="nav-item<?= isActive('profile', $currentAction); ?>">
        <i class="ti ti-user-circle" aria-hidden="true"></i> Professional profile
    </a>
    <a href="Dashboard.php?action=availability" class="nav-item<?= isActive('availability', $currentAction); ?>">
        <i class="ti ti-calendar-time" aria-hidden="true"></i> Weekly availability
    </a>
    <a href="Dashboard.php?action=leave_dates" class="nav-item<?= isActive('leave_dates', $currentAction); ?>">
        <i class="ti ti-calendar-off" aria-hidden="true"></i> Leave dates
    </a>
    <a href="Dashboard.php?action=complete_appointment" class="nav-item<?= isActive('complete_appointment', $currentAction); ?>">
        <i class="ti ti-circle-check" aria-hidden="true"></i> Complete appointment
    </a>

    <div class="nav-section-label">Records</div>
    <a href="Dashboard.php?action=patient_history" class="nav-item<?= isActive('patient_history', $currentAction); ?>">
        <i class="ti ti-folder-open" aria-hidden="true"></i> Patient history
    </a>
    <a href="Dashboard.php?action=followups" class="nav-item<?= isActive('followups', $currentAction); ?>">
        <i class="ti ti-repeat" aria-hidden="true"></i> Follow-ups
    </a>

    <div class="nav-section-label">Reports</div>
    <a href="Dashboard.php?action=earnings" class="nav-item<?= isActive('earnings', $currentAction); ?>">
        <i class="ti ti-report-money" aria-hidden="true"></i> Earnings report
    </a>
    <a href="Dashboard.php?action=statistics" class="nav-item<?= isActive('statistics', $currentAction); ?>">
        <i class="ti ti-chart-bar" aria-hidden="true"></i> Appointment statistics
    </a>
    <a href="Dashboard.php?action=reviews" class="nav-item<?= isActive('reviews', $currentAction); ?>">
        <i class="ti ti-star" aria-hidden="true"></i> Patient reviews
    </a>

    <div class="sidebar-footer">
        <a href="logout.php"><i class="ti ti-logout" aria-hidden="true"></i> Logout</a>
    </div>
</aside>

<!-- ── Main ── -->
<div class="main-wrapper">

    <header class="topbar">
        <nav class="topbar-tabs">
            <a href="Dashboard.php" class="topbar-tab<?= isActive('dashboard', $currentAction); ?>">
                <i class="ti ti-layout-dashboard" aria-hidden="true"></i> Dashboard
            </a>
            <a href="Dashboard.php?action=today_appointments" class="topbar-tab<?= isActive('today_appointments', $currentAction); ?>">
                <i class="ti ti-calendar-event" aria-hidden="true"></i> Schedule
            </a>
            <a href="Dashboard.php?action=earnings" class="topbar-tab<?= isActive('earnings', $currentAction); ?>">
                <i class="ti ti-report-money" aria-hidden="true"></i> Reports
            </a>
        </nav>
        <div class="topbar-right">
            <div class="doctor-avatar-sm">
                <?= strtoupper(substr($doctor['name'], 0, 2)); ?>
            </div>
            Dr. <?= htmlspecialchars($_SESSION['name']); ?>
            <div class="topbar-divider"></div>
            <a href="logout.php" class="topbar-logout">
                <i class="ti ti-logout" aria-hidden="true"></i> Logout
            </a>
        </div>
    </header>

    <main class="content">

        <!-- Doctor header card -->
        <div class="doctor-header">
            <div class="doc-avatar-lg">
                <?= strtoupper(substr($doctor['name'], 0, 2)); ?>
            </div>
            <div class="doc-info">
                <div class="doc-name">Dr. <?= htmlspecialchars($doctor['name']); ?></div>
                <div class="doc-spec"><?= htmlspecialchars($doctor['specialization'] ?? 'Specialist'); ?></div>
                <div class="doc-meta">
                    <span class="doc-meta-item">
                        <i class="ti ti-id-badge" aria-hidden="true"></i>
                        <?= htmlspecialchars($doctor['license_number']); ?>
                    </span>
                    <span class="doc-meta-item">
                        <i class="ti ti-clock" aria-hidden="true"></i>
                        <?= htmlspecialchars($doctor['experience_years']); ?> years experience
                    </span>
                </div>
            </div>
            <div class="doc-header-right">
                <?php if ($doctor['is_approved'] != 1 || $doctor['status'] !== 'approved'): ?>
                    <span class="status-badge pending">
                        <i class="ti ti-clock-hour-4" aria-hidden="true"></i> Pending approval
                    </span>
                <?php else: ?>
                    <span class="status-badge approved">
                        <i class="ti ti-circle-check" aria-hidden="true"></i> Approved
                    </span>
                <?php endif; ?>
                <span class="fee-tag">
                    <?= htmlspecialchars($doctor['consultation_fee']); ?> BDT / consultation
                </span>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-label">
                    <i class="ti ti-calendar-event" aria-hidden="true"></i> Today's appointments
                </div>
                <div class="stat-value"><?= htmlspecialchars($todayAppointments); ?></div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">
                    <i class="ti ti-clock-hour-4" aria-hidden="true"></i> Pending requests
                </div>
                <div class="stat-value"><?= htmlspecialchars($pendingAppointments); ?></div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">
                    <i class="ti ti-circle-check" aria-hidden="true"></i> Completed
                </div>
                <div class="stat-value"><?= htmlspecialchars($completedAppointments); ?></div>
            </div>
            <div class="stat-card purple">
                <div class="stat-label">
                    <i class="ti ti-report-money" aria-hidden="true"></i> Total earnings
                </div>
                <div class="stat-value">
                    <?= htmlspecialchars($totalEarnings); ?>
                    <span style="font-size:13px;font-weight:400;color:#95a5a6;">BDT</span>
                </div>
            </div>
        </div>

    </main>
</div>

</body>
</html>
