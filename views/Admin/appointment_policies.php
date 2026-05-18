<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Policies - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Adminappointmentpolicies.css">

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