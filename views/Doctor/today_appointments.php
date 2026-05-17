<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Today's Appointments - Doctor Portal</title>
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

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .top-section {
            border-left: 5px solid #3498db;
        }

        .btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 14px;
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
            padding: 35px;
            color: #95a5a6;
        }

        .small {
            color: #7f8c8d;
            font-size: 12px;
        }

        .summary {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .summary-box {
            background: #f8f9fa;
            padding: 12px 16px;
            border-radius: 8px;
            color: #2c3e50;
            font-weight: bold;
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
    <div class="logo">🏥 HospitalCare | Today's Schedule</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Today's Appointment Schedule</h1>
        <p>
            Date:
            <strong><?= htmlspecialchars(date('Y-m-d')); ?></strong>
            |
            Day:
            <strong><?= htmlspecialchars(date('l')); ?></strong>
        </p>

        <div class="summary">
            <div class="summary-box">
                Total Today:
                <?= $appointments ? htmlspecialchars($appointments->num_rows) : 0; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>For</th>
                    <th>Phone</th>
                    <th>Reason</th>
                    <th>Booked By</th>
                    <th>Status</th>
                    <th>Check-in Time</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($appointments && $appointments->num_rows > 0): ?>
                    <?php while ($appointment = $appointments->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['appointment_time']); ?></td>

                            <td>
                                <?= htmlspecialchars($appointment['patient_name']); ?>
                                <br>
                                <span class="small">Patient ID: <?= htmlspecialchars($appointment['patient_id']); ?></span>
                            </td>

                            <td>
                                <?php if (!empty($appointment['dependent_name'])): ?>
                                    <?= htmlspecialchars($appointment['dependent_name']); ?>
                                    <br>
                                    <span class="small"><?= htmlspecialchars($appointment['relationship']); ?></span>
                                <?php else: ?>
                                    Self
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($appointment['patient_mobile']); ?></td>

                            <td><?= htmlspecialchars($appointment['reason']); ?></td>

                            <td><?= htmlspecialchars(ucfirst($appointment['booked_by'])); ?></td>

                            <td>
                                <span class="status-pill <?= htmlspecialchars($appointment['status']); ?>">
                                    <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $appointment['status']))); ?>
                                </span>
                            </td>

                            <td>
                                <?= !empty($appointment['checkin_time'])
                                    ? htmlspecialchars($appointment['checkin_time'])
                                    : '-'; ?>
                            </td>

                            <td>
                                 <?php if ($appointment['status'] === 'confirmed'): ?>
                               <button 
                                      class="checkin-btn"
                                        data-id="<?= htmlspecialchars($appointment['appointment_id']); ?>"
                                      style="background:#27ae60; color:white; padding:7px 10px; border:none; border-radius:5px; cursor:pointer; font-size:12px;"
                                       >  Check In </button>
    <?php else: ?>
        -
    <?php endif; ?>
                        </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="empty">
                            No appointments scheduled for today.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".checkin-btn");

    buttons.forEach(function (button) {
        button.addEventListener("click", function () {
            const appointmentId = this.getAttribute("data-id");
            const currentButton = this;

            if (!confirm("Check in this patient?")) {
                return;
            }

            currentButton.disabled = true;
            currentButton.textContent = "Checking...";

            const formData = new FormData();
            formData.append("appointment_id", appointmentId);

            fetch("./api/doctor_checkin.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = currentButton.closest("tr");

                    const statusCell = row.querySelector(".status-pill");
                    statusCell.className = "status-pill checked_in";
                    statusCell.textContent = "Checked In";

                    const cells = row.querySelectorAll("td");
                    cells[7].textContent = data.checkin_time;

                    currentButton.parentElement.textContent = "-";

                    alert(data.message);
                } else {
                    alert(data.message);
                    currentButton.disabled = false;
                    currentButton.textContent = "Check In";
                }
            })
            .catch(error => {
                alert("AJAX error. Check console or API path.");
                console.error(error);
                currentButton.disabled = false;
                currentButton.textContent = "Check In";
            });
        });
    });
});
</script>