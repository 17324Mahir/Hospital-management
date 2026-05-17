<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Policies - Admin Portal</title>
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
            max-width: 900px;
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
            border-left: 5px solid #e67e22;
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

        .submit-btn {
            background: #e67e22;
            margin-top: 20px;
        }

        label {
            display: block;
            margin-top: 16px;
            margin-bottom: 6px;
            font-weight: bold;
            color: #2c3e50;
        }

        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            line-height: 1.7;
            margin-bottom: 18px;
        }

        .policy-note {
            background: #eaf6ff;
            color: #2c3e50;
            padding: 12px;
            border-radius: 6px;
            margin-top: 18px;
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Appointment Policies</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Appointment Policies</h1>
        <p>Manage global rules for appointments and billing defaults.</p>
    </div>

    <div class="card">

        <?php if (!empty($message)): ?>
            <div class="success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="info-box">
            <strong>Policy ID:</strong> #<?= htmlspecialchars($policy['id']); ?><br>
            <strong>Last Updated:</strong> <?= htmlspecialchars($policy['updated_at']); ?>
        </div>

        <form method="POST" action="">

            <label for="minimum_cancellation_notice_hours">
                Minimum Cancellation Notice Hours *
            </label>
            <input 
                type="number" 
                min="0"
                name="minimum_cancellation_notice_hours" 
                id="minimum_cancellation_notice_hours"
                value="<?= htmlspecialchars($policy['minimum_cancellation_notice_hours']); ?>"
                required
            >

            <label for="maximum_advance_booking_days">
                Maximum Advance Booking Days *
            </label>
            <input 
                type="number" 
                min="1"
                name="maximum_advance_booking_days" 
                id="maximum_advance_booking_days"
                value="<?= htmlspecialchars($policy['maximum_advance_booking_days']); ?>"
                required
            >

            <label for="default_consultation_fee">
                Default Consultation Fee *
            </label>
            <input 
                type="number" 
                step="0.01"
                min="0"
                name="default_consultation_fee" 
                id="default_consultation_fee"
                value="<?= htmlspecialchars($policy['default_consultation_fee']); ?>"
                required
            >

            <button type="submit" class="btn submit-btn">
                Update Policies
            </button>

        </form>

        <div class="policy-note">
            <strong>Report note:</strong> This section allows the admin to configure hospital-wide appointment rules such as cancellation notice, maximum booking period, and the default consultation fee.
        </div>

    </div>

</div>

</body>
</html>