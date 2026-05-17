<?php
$current_action = $_GET['action'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receptionist Panel - CareConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #edf5f4;
            color: #223;
        }

        .topbar {
            background: #0f3d3e;
            color: white;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            color: white;
            text-decoration: none;
            font-size: 22px;
            font-weight: bold;
        }

        .brand span {
            color: #f5c542;
        }

        .topbar-right {
            font-size: 14px;
        }

        .topbar-right a {
            color: #ffb3b3;
            text-decoration: none;
            font-weight: bold;
            margin-left: 10px;
        }

        .layout {
            display: flex;
            min-height: calc(100vh - 58px);
        }

        .sidebar {
            width: 255px;
            background: #123f42;
            padding: 22px 15px;
            color: white;
        }

        .user-box {
            background: #1f575a;
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 20px;
        }

        .user-icon {
            width: 58px;
            height: 58px;
            background: #f5c542;
            color: #123f42;
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 26px;
            font-weight: bold;
        }

        .user-box h3 {
            margin: 5px 0;
            font-size: 17px;
        }

        .user-box p {
            margin: 4px 0;
            font-size: 13px;
            color: #d9eeee;
        }

        .menu-title {
            font-size: 12px;
            color: #b8d6d8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 12px 0;
        }

        .menu a {
            display: block;
            color: white;
            text-decoration: none;
            background: #20585c;
            padding: 11px 13px;
            border-radius: 8px;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .menu a:hover,
        .menu a.active {
            background: #f5c542;
            color: #123f42;
            font-weight: bold;
        }

        .content {
            flex: 1;
            padding: 28px;
        }

        .page-card {
            background: white;
            padding: 24px;
            border-radius: 14px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.07);
            margin-bottom: 20px;
        }

        .page-title {
            border-left: 6px solid #0f8b8d;
        }

        .page-card h1,
        .page-card h2 {
            margin-top: 0;
            color: #123f42;
        }

        .btn {
            display: inline-block;
            background: #0f8b8d;
            color: white;
            padding: 10px 15px;
            border-radius: 7px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-gray {
            background: #7f8c8d;
        }

        .btn-green {
            background: #27ae60;
        }

        .btn-orange {
            background: #e67e22;
        }

        .btn-red {
            background: #e74c3c;
        }

        .btn-purple {
            background: #8e44ad;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 15px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 15px;
        }

        .note {
            background: #eaf6ff;
            color: #2c3e50;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccd;
            border-radius: 7px;
            font-size: 14px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #123f42;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-size: 14px;
            vertical-align: top;
        }

        th {
            color: #667;
            font-size: 12px;
            text-transform: uppercase;
        }

        .status {
            padding: 5px 10px;
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

        .checked_in,
        .active,
        .paid {
            background: #d4edda;
            color: #155724;
        }

        .cancelled,
        .inactive,
        .unpaid {
            background: #f8d7da;
            color: #721c24;
        }

        .small {
            font-size: 12px;
            color: #667;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #889;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 13px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.07);
            border-top: 5px solid #0f8b8d;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 14px;
            color: #667;
            font-weight: normal;
        }

        .stat-card .number {
            margin-top: 10px;
            font-size: 28px;
            font-weight: bold;
            color: #123f42;
        }

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        @media (max-width: 600px) {
            .topbar {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }

            .content {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<div class="topbar">
    <a href="index.php" class="brand"> Care<span>Connect</span> Hospital</a>

    <div class="topbar-right">
        Hello, <?= htmlspecialchars($_SESSION['name'] ?? 'Receptionist'); ?>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="layout">

    <div class="sidebar">

        <div class="user-box">
            <div class="user-icon">
                <?= htmlspecialchars(strtoupper(substr($_SESSION['name'] ?? 'R', 0, 1))); ?>
            </div>

            <h3><?= htmlspecialchars($_SESSION['name'] ?? 'Receptionist'); ?></h3>
            <p>Receptionist Panel</p>
        </div>

        <div class="menu-title">Menu</div>

        <div class="menu">
            <a class="<?= $current_action === 'dashboard' ? 'active' : ''; ?>" href="Dashboard.php">Dashboard</a>
            <a class="<?= $current_action === 'daily_schedule' ? 'active' : ''; ?>" href="Dashboard.php?action=daily_schedule">Daily Schedule</a>
            <a class="<?= $current_action === 'patient_search' ? 'active' : ''; ?>" href="Dashboard.php?action=patient_search">Search Patients</a>
            <a class="<?= $current_action === 'register_patient' ? 'active' : ''; ?>" href="Dashboard.php?action=register_patient">Register Patient</a>
            <a class="<?= $current_action === 'walkin_booking' ? 'active' : ''; ?>" href="Dashboard.php?action=walkin_booking">Walk-in Booking</a>
            <a class="<?= $current_action === 'manage_appointments' ? 'active' : ''; ?>" href="Dashboard.php?action=manage_appointments">Manage Appointments</a>
            <a class="<?= $current_action === 'checkin_patient' ? 'active' : ''; ?>" href="Dashboard.php?action=checkin_patient">Check-in Patient</a>
            <a class="<?= $current_action === 'waiting_queue' ? 'active' : ''; ?>" href="Dashboard.php?action=waiting_queue"> Waiting Queue</a>
            <a class="<?= $current_action === 'payments' ? 'active' : ''; ?>" href="Dashboard.php?action=payments">Payments</a>
            <a class="<?= $current_action === 'doctor_availability' ? 'active' : ''; ?>" href="Dashboard.php?action=doctor_availability">Doctor Availability</a>
            <a class="<?= $current_action === 'daily_report' ? 'active' : ''; ?>" href="Dashboard.php?action=daily_report">Daily Report</a>
        </div>

    </div>

    <div class="content">