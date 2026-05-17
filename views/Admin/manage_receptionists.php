<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Receptionists - Admin Portal</title>
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
            border-left: 5px solid #8e44ad;
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
        }

        .active {
            background: #d4edda;
            color: #155724;
        }

        .inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .role {
            background: #efe2ff;
            color: #4a235a;
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
    <div class="logo">🏥 HospitalCare | Manage Receptionists</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Manage Receptionists</h1>
        <p>View, edit, activate, or deactivate receptionist accounts.</p>

        <div class="search-box">
            <input 
                type="text" 
                id="receptionistSearch" 
                placeholder="Search by name, email, phone, status..."
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
                    <th>User ID</th>
                    <th>Receptionist</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Account</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="receptionistTable">
                <?php if ($receptionists && $receptionists->num_rows > 0): ?>
                    <?php while ($receptionist = $receptionists->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($receptionist['id']); ?></td>

                            <td>
                                <strong><?= htmlspecialchars($receptionist['name']); ?></strong>
                            </td>

                            <td>
                                <?= htmlspecialchars($receptionist['email']); ?>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($receptionist['mobile'] ?? $receptionist['phone'] ?? '-'); ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge role">Receptionist</span>
                            </td>

                            <td>
                                <?php if ($receptionist['is_active'] == 1): ?>
                                    <span class="badge active">Active</span>
                                <?php else: ?>
                                    <span class="badge inactive">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($receptionist['created_at']); ?></td>

                            <td>
                                <div class="actions">
                                    <a 
                                        href="Dashboard.php?action=edit_receptionist&id=<?= htmlspecialchars($receptionist['id']); ?>"
                                        class="btn edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <?php if ($receptionist['is_active'] == 1): ?>
                                        <a 
                                            href="Dashboard.php?action=toggle_receptionist_user_status&id=<?= htmlspecialchars($receptionist['id']); ?>"
                                            onclick="return confirm('Deactivate this receptionist account?');"
                                            class="btn inactive-btn"
                                        >
                                            Deactivate
                                        </a>
                                    <?php else: ?>
                                        <a 
                                            href="Dashboard.php?action=toggle_receptionist_user_status&id=<?= htmlspecialchars($receptionist['id']); ?>"
                                            onclick="return confirm('Activate this receptionist account?');"
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
                        <td colspan="7" class="empty">
                            No receptionists found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("receptionistSearch");
    const rows = document.querySelectorAll("#receptionistTable tr");

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