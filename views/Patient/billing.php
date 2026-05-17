<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Billing History - Patient Portal</title>
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
            max-width: 1100px;
            margin: auto;
            padding: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        }

        .top-section {
            border-left: 5px solid #e67e22;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
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
        }

        th {
            color: #7f8c8d;
            font-size: 13px;
            text-transform: uppercase;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .paid {
            background: #d4edda;
            color: #155724;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #95a5a6;
        }
    </style>
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