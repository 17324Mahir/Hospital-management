<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Doctors - Admin Portal</title>
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
            max-width: 1300px;
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
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .edit-btn {
            background: #3498db;
            margin-bottom: 5px;
        }

        .active-btn {
            background: #27ae60;
            margin-bottom: 5px;
        }

        .inactive-btn {
            background: #e74c3c;
            margin-bottom: 5px;
        }

        .approve-btn {
            background: #16a085;
            margin-bottom: 5px;
        }

        .reject-btn {
            background: #c0392b;
            margin-bottom: 0;
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
            padding: 11px;
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
            margin-bottom: 4px;
        }

        .approved {
            background: #d4edda;
            color: #155724;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .active {
            background: #d4edda;
            color: #155724;
        }

        .inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .small {
            color: #7f8c8d;
            font-size: 12px;
            line-height: 1.5;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #95a5a6;
        }

        @media (max-width: 900px) {
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
    <div class="logo">🏥 HospitalCare | Manage Doctors</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Manage Doctors</h1>
        <p>View, edit, approve/reject, and activate/deactivate doctor accounts.</p>

        <div class="search-box">
            <input 
                type="text" 
                id="doctorSearch" 
                placeholder="Search by doctor name, email, specialization, license, status..."
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
                    <th>Doctor</th>
                    <th>Contact</th>
                    <th>Specialization</th>
                    <th>License</th>
                    <th>Experience</th>
                    <th>Fee</th>
                    <th>Approval</th>
                    <th>Account</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="doctorTable">
                <?php if ($doctors && $doctors->num_rows > 0): ?>
                    <?php while ($doctor = $doctors->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong>Dr. <?= htmlspecialchars($doctor['name']); ?></strong>
                                <br>
                                <span class="small">Doctor ID: #<?= htmlspecialchars($doctor['doctor_id']); ?></span>
                            </td>

                            <td>
                                <?= htmlspecialchars($doctor['email']); ?>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($doctor['mobile'] ?? $doctor['phone'] ?? '-'); ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($doctor['specialization'] ?? '-'); ?></td>

                            <td><?= htmlspecialchars($doctor['license_number'] ?? '-'); ?></td>

                            <td><?= htmlspecialchars($doctor['experience_years'] ?? 0); ?> years</td>

                            <td><?= htmlspecialchars(number_format((float)($doctor['consultation_fee'] ?? 0), 2)); ?> BDT</td>

                            <td>
                                <span class="badge <?= htmlspecialchars($doctor['doctor_status']); ?>">
                                    <?= htmlspecialchars(ucfirst($doctor['doctor_status'])); ?>
                                </span>
                                <br>
                                <span class="small">
                                    Approved: <?= $doctor['is_approved'] == 1 ? 'Yes' : 'No'; ?>
                                </span>
                            </td>

                            <td>
                                <?php if ($doctor['is_active'] == 1): ?>
                                    <span class="badge active">Active</span>
                                <?php else: ?>
                                    <span class="badge inactive">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($doctor['created_at']); ?></td>

                            <td>
                                <div class="actions">

                                    <a 
                                        href="Dashboard.php?action=edit_doctor&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                        class="btn edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <?php if ($doctor['is_active'] == 1): ?>
                                        <a 
                                            href="Dashboard.php?action=toggle_doctor_user_status&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                            onclick="return confirm('Deactivate this doctor account?');"
                                            class="btn inactive-btn"
                                        >
                                            Deactivate
                                        </a>
                                    <?php else: ?>
                                        <a 
                                            href="Dashboard.php?action=toggle_doctor_user_status&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                            onclick="return confirm('Activate this doctor account?');"
                                            class="btn active-btn"
                                        >
                                            Activate
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($doctor['is_approved'] != 1 || $doctor['doctor_status'] !== 'approved'): ?>
                                        <a 
                                            href="Dashboard.php?action=manage_approve_doctor&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                            onclick="return confirm('Approve this doctor?');"
                                            class="btn approve-btn"
                                        >
                                            Approve
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($doctor['doctor_status'] !== 'rejected'): ?>
                                        <a 
                                            href="Dashboard.php?action=manage_reject_doctor&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                            onclick="return confirm('Reject this doctor?');"
                                            class="btn reject-btn"
                                        >
                                            Reject
                                        </a>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="empty">
                            No doctors found.
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