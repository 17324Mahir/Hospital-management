<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Billing History - Patient Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/billing.css">
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Billing History</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Billing History</h1>
        <p>View your appointment invoices and payment status.</p>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Billing ID</th>
                    <th>Appointment</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Paid At</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($billings && $billings->num_rows > 0): ?>
                    <?php while ($bill = $billings->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($bill['billing_id']); ?></td>

                            <td>
                                <?= htmlspecialchars($bill['appointment_date']); ?>
                                <br>
                                <?= htmlspecialchars($bill['appointment_time']); ?>
                            </td>

                            <td>Dr. <?= htmlspecialchars($bill['doctor_name']); ?></td>

                            <td><?= htmlspecialchars($bill['specialization']); ?></td>

                            <td><?= htmlspecialchars($bill['amount']); ?> BDT</td>

                            <td>
                                <?= htmlspecialchars($bill['payment_method'] ?? 'Not paid yet'); ?>
                            </td>

                            <td>
                                <span class="badge <?= htmlspecialchars($bill['payment_status']); ?>">
                                    <?= htmlspecialchars(ucfirst($bill['payment_status'])); ?>
                                </span>
                            </td>

                            <td>
                                <?= htmlspecialchars($bill['paid_at'] ?? '-'); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty">
                            No billing history found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>