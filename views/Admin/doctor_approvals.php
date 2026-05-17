<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Approvals - Admin Portal</title>
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
            border-left: 5px solid #27ae60;
        }

        .btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 9px 13px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .approve-btn {
            background: #27ae60;
            margin-bottom: 5px;
            padding: 7px 12px;
            font-size: 13px;
        }

        .reject-btn {
            background: #e74c3c;
            margin-bottom: 0;
            padding: 7px 12px;
            font-size: 13px;
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

        .badge {
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

        .rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .approved {
            background: #d4edda;
            color: #155724;
        }

        .small {
            color: #7f8c8d;
            font-size: 12px;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #95a5a6;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 5px;
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
    <div class="logo">🏥 HospitalCare | Doctor Approvals</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Doctor Approvals</h1>
        <p>Review pending doctor accounts and approve or reject them.</p>

        <div class="search-box">
            <input 
                type="text" 
                id="doctorSearch" 
                placeholder="Search by name, email, specialization, license..."
            >
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="success"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Doctor ID</th>
                    <th>Name / Contact</th>
                    <th>Specialization</th>
                    <th>License</th>
                    <th>Experience</th>
                    <th>Fee</th>
                    <th>Bio</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="doctorTable">
                <?php if ($pendingDoctors && $pendingDoctors->num_rows > 0): ?>
                    <?php while ($doctor = $pendingDoctors->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($doctor['doctor_id']); ?></td>

                            <td>
                                Dr. <?= htmlspecialchars($doctor['name']); ?>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($doctor['email']); ?><br>
                                    <?= htmlspecialchars($doctor['mobile']); ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($doctor['specialization'] ?? '-'); ?></td>

                            <td><?= htmlspecialchars($doctor['license_number'] ?? '-'); ?></td>

                            <td>
                                <?= htmlspecialchars($doctor['experience_years'] ?? 0); ?> years
                            </td>

                            <td>
                                <?= htmlspecialchars(number_format((float)($doctor['consultation_fee'] ?? 0), 2)); ?> BDT
                            </td>

                            <td>
                                <?= htmlspecialchars(substr($doctor['bio'] ?? 'No bio', 0, 80)); ?>
                                <?= strlen($doctor['bio'] ?? '') > 80 ? '...' : ''; ?>
                            </td>

                            <td>
                                <span class="badge <?= htmlspecialchars($doctor['status']); ?>">
                                    <?= htmlspecialchars(ucfirst($doctor['status'])); ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($doctor['created_at']); ?></td>

                            <td>
                                <div class="actions">
                                    <a 
                                        href="Dashboard.php?action=approve_doctor&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                        onclick="return confirm('Approve this doctor?');"
                                        class="btn approve-btn"
                                    >
                                        Approve
                                    </a>

                                    <a 
                                        href="Dashboard.php?action=reject_doctor&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                        onclick="return confirm('Reject this doctor?');"
                                        class="btn reject-btn"
                                    >
                                        Reject
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="empty">
                            No pending doctor approvals found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("doctorSearch");
    const rows = document.querySelectorAll("#doctorTable tr");

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