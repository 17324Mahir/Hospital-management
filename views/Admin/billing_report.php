<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Billing & Revenue Report - Admin Portal</title>
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
            border-left: 5px solid #f39c12;
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
            background: #f39c12;
            margin-left: 8px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 18px;
            margin-bottom: 20px;
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
            font-size: 29px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 10px;
        }

        .green {
            color: #27ae60;
        }

        .orange {
            color: #e67e22;
        }

        .blue {
            color: #3498db;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .search-box {
            margin-top: 15px;
        }

        .search-box input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
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

        .status-pill {
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

        .small {
            color: #7f8c8d;
            font-size: 12px;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #95a5a6;
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
            .navbar, .btn-area, .search-box {
                display: none;
            }

            body {
                background: white;
            }

            .container {
                padding: 0;
                max-width: 100%;
            }

            .card, .stat-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Billing & Revenue</div>

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
        <h1>Billing & Revenue Report</h1>
        <p>Hospital-wide billing, revenue, pending dues, and payment method summary.</p>
        <p>
            <strong>Generated At:</strong>
            <?= htmlspecialchars(date('Y-m-d H:i:s')); ?>
        </p>
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <h3>Total Bills</h3>
            <div class="number blue">
                <?= htmlspecialchars($billingSummary['total_bills'] ?? 0); ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>Paid Bills</h3>
            <div class="number green">
                <?= htmlspecialchars($billingSummary['paid_bills'] ?? 0); ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>Pending Bills</h3>
            <div class="number orange">
                <?= htmlspecialchars($billingSummary['pending_bills'] ?? 0); ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>Total Revenue</h3>
            <div class="number green">
                <?= htmlspecialchars(number_format((float)($billingSummary['total_revenue'] ?? 0), 2)); ?> BDT
            </div>
        </div>

        <div class="stat-card">
            <h3>Pending Amount</h3>
            <div class="number orange">
                <?= htmlspecialchars(number_format((float)($billingSummary['pending_amount'] ?? 0), 2)); ?> BDT
            </div>
        </div>

    </div>

    <div class="grid-2">

        <div class="card">
            <h2>Payment Method Summary</h2>

            <table>
                <thead>
                    <tr>
                        <th>Method</th>
                        <th>Total Payments</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($paymentMethodSummary && $paymentMethodSummary->num_rows > 0): ?>
                        <?php while ($method = $paymentMethodSummary->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($method['payment_method'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($method['total_payments']); ?></td>
                                <td><?= htmlspecialchars(number_format((float)$method['total_amount'], 2)); ?> BDT</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="empty">
                                No paid payment method data found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Monthly Revenue</h2>

            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Bills</th>
                        <th>Paid</th>
                        <th>Pending</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($monthlyRevenue && $monthlyRevenue->num_rows > 0): ?>
                        <?php while ($month = $monthlyRevenue->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($month['report_month'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($month['total_bills']); ?></td>
                                <td><?= htmlspecialchars(number_format((float)$month['paid_revenue'], 2)); ?> BDT</td>
                                <td><?= htmlspecialchars(number_format((float)$month['pending_revenue'], 2)); ?> BDT</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="empty">
                                No monthly revenue data found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="card">
        <h2>Doctor-wise Revenue</h2>

        <table>
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Total Bills</th>
                    <th>Paid Revenue</th>
                    <th>Pending Revenue</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($doctorWiseRevenue && $doctorWiseRevenue->num_rows > 0): ?>
                    <?php while ($doctor = $doctorWiseRevenue->fetch_assoc()): ?>
                        <tr>
                            <td>
                                Dr. <?= htmlspecialchars($doctor['doctor_name']); ?>
                                <br>
                                <span class="small">ID: #<?= htmlspecialchars($doctor['doctor_id']); ?></span>
                            </td>

                            <td><?= htmlspecialchars($doctor['specialization'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($doctor['total_bills']); ?></td>
                            <td><?= htmlspecialchars(number_format((float)$doctor['paid_revenue'], 2)); ?> BDT</td>
                            <td><?= htmlspecialchars(number_format((float)$doctor['pending_revenue'], 2)); ?> BDT</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty">
                            No doctor revenue data found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Detailed Billing Records</h2>

        <div class="search-box">
            <input 
                type="text" 
                id="billingSearch" 
                placeholder="Search by bill ID, patient, doctor, status, payment method..."
            >
        </div>

        <table>
            <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Appointment</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Paid At</th>
                </tr>
            </thead>

            <tbody id="billingTable">
                <?php if ($billingRecords && $billingRecords->num_rows > 0): ?>
                    <?php while ($bill = $billingRecords->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($bill['billing_id']); ?></td>

                            <td>
                                #<?= htmlspecialchars($bill['appointment_id'] ?? '-'); ?>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($bill['appointment_date'] ?? '-'); ?>
                                    <?= htmlspecialchars($bill['appointment_time'] ?? ''); ?>
                                </span>
                            </td>

                            <td>
                                <?= htmlspecialchars($bill['patient_name'] ?? '-'); ?>
                                <br>
                                <span class="small">
                                    ID: <?= htmlspecialchars($bill['patient_id'] ?? '-'); ?>
                                    |
                                    <?= htmlspecialchars($bill['patient_mobile'] ?? '-'); ?>
                                </span>
                            </td>

                            <td>
                                Dr. <?= htmlspecialchars($bill['doctor_name'] ?? '-'); ?>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($bill['specialization'] ?? '-'); ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars(number_format((float)($bill['amount'] ?? 0), 2)); ?> BDT</td>

                            <td><?= htmlspecialchars($bill['payment_method'] ?? '-'); ?></td>

                            <td>
                                <span class="status-pill <?= htmlspecialchars($bill['payment_status']); ?>">
                                    <?= htmlspecialchars(ucfirst($bill['payment_status'])); ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($bill['paid_at'] ?? '-'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty">
                            No billing records found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("billingSearch");
    const rows = document.querySelectorAll("#billingTable tr");

    searchInput.addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();

        rows.forEach(function (row) {
            const text = row.innerText.toLowerCase();

            if (text.includes(keyword)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});
</script>

</body>
</html>