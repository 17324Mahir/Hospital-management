<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Patients - Admin Portal</title>
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
            border-left: 5px solid #16a085;
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
            margin-bottom: 0;
        }

        .inactive-btn {
            background: #e74c3c;
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
    <div class="logo">🏥 HospitalCare | Manage Patients</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Manage Patients</h1>
        <p>View, edit, activate, or deactivate patient accounts.</p>

        <div class="search-box">
            <input 
                type="text" 
                id="patientSearch" 
                placeholder="Search by name, email, phone, patient ID, blood group, gender..."
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
                    <th>Patient</th>
                    <th>Contact</th>
                    <th>Medical Info</th>
                    <th>Address</th>
                    <th>Emergency</th>
                    <th>Account</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="patientTable">
                <?php if ($patients && $patients->num_rows > 0): ?>
                    <?php while ($patient = $patients->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($patient['name']); ?></strong>
                                <br>
                                <span class="small">
                                    Patient ID: #<?= htmlspecialchars($patient['patient_id']); ?><br>
                                    DOB: <?= htmlspecialchars($patient['date_of_birth'] ?? '-'); ?>
                                </span>
                            </td>

                            <td>
                                <?= htmlspecialchars($patient['email']); ?>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($patient['mobile'] ?? $patient['phone'] ?? '-'); ?>
                                </span>
                            </td>

                            <td>
                                <strong>Gender:</strong> <?= htmlspecialchars($patient['gender'] ?? '-'); ?><br>
                                <strong>Blood:</strong> <?= htmlspecialchars($patient['blood_group'] ?? '-'); ?>
                            </td>

                            <td><?= htmlspecialchars($patient['address'] ?? '-'); ?></td>

                            <td>
                                <?= htmlspecialchars($patient['emergency_contact_name'] ?? '-'); ?>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($patient['emergency_contact_phone'] ?? $patient['emergency_contact'] ?? '-'); ?>
                                </span>
                            </td>

                            <td>
                                <?php if ($patient['is_active'] == 1): ?>
                                    <span class="badge active">Active</span>
                                <?php else: ?>
                                    <span class="badge inactive">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($patient['created_at']); ?></td>

                            <td>
                                <div class="actions">
                                    <a 
                                        href="Dashboard.php?action=edit_patient&id=<?= htmlspecialchars($patient['patient_id']); ?>"
                                        class="btn edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <?php if ($patient['is_active'] == 1): ?>
                                        <a 
                                            href="Dashboard.php?action=toggle_patient_user_status&id=<?= htmlspecialchars($patient['patient_id']); ?>"
                                            onclick="return confirm('Deactivate this patient account?');"
                                            class="btn inactive-btn"
                                        >
                                            Deactivate
                                        </a>
                                    <?php else: ?>
                                        <a 
                                            href="Dashboard.php?action=toggle_patient_user_status&id=<?= htmlspecialchars($patient['patient_id']); ?>"
                                            onclick="return confirm('Activate this patient account?');"
                                            class="btn active-btn"
                                        >
                                            Activate
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty">
                            No patients found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("patientSearch");
    const rows = document.querySelectorAll("#patientTable tr");

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