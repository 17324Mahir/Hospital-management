<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin Portal</title>
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
            border-left: 5px solid #3498db;
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

        .activate-btn {
            background: #27ae60;
            margin-bottom: 0;
            padding: 7px 12px;
            font-size: 13px;
        }

        .deactivate-btn {
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

        .stats-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .stat-box {
            background: #f8f9fa;
            padding: 10px 14px;
            border-radius: 8px;
            font-weight: bold;
            color: #2c3e50;
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

        .admin {
            background: #fdecea;
            color: #922b21;
        }

        .doctor {
            background: #d4edda;
            color: #155724;
        }

        .patient {
            background: #dff9fb;
            color: #130f40;
        }

        .receptionist {
            background: #efe2ff;
            color: #4a235a;
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
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #95a5a6;
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
    <div class="logo">🏥 HospitalCare | Manage Users</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Manage Users</h1>
        <p>View all system users and activate or deactivate accounts.</p>

        <div class="search-box">
            <input 
                type="text" 
                id="userSearch" 
                placeholder="Search by name, email, phone, role, status..."
            >
        </div>

        <div class="stats-row">
            <div class="stat-box">All system roles included</div>
            <div class="stat-box">Admin cannot deactivate own account</div>
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
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="userTable">
                <?php if ($users && $users->num_rows > 0): ?>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($user['id']); ?></td>

                            <td><?= htmlspecialchars($user['name']); ?></td>

                            <td>
                                <?= htmlspecialchars($user['email']); ?>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($user['mobile'] ?? $user['phone'] ?? '-'); ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge <?= htmlspecialchars($user['role']); ?>">
                                    <?= htmlspecialchars(ucfirst($user['role'])); ?>
                                </span>
                            </td>

                            <td>
                                <?php if ($user['is_active'] == 1): ?>
                                    <span class="badge active">Active</span>
                                <?php else: ?>
                                    <span class="badge inactive">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($user['created_at']); ?></td>

                            <td>
                                <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                    <span class="small">Current admin</span>
                                <?php else: ?>
                                    <?php if ($user['is_active'] == 1): ?>
                                        <a 
                                            href="Dashboard.php?action=toggle_user_status&id=<?= htmlspecialchars($user['id']); ?>"
                                            onclick="return confirm('Deactivate this user?');"
                                            class="btn deactivate-btn"
                                        >
                                            Deactivate
                                        </a>
                                    <?php else: ?>
                                        <a 
                                            href="Dashboard.php?action=toggle_user_status&id=<?= htmlspecialchars($user['id']); ?>"
                                            onclick="return confirm('Activate this user?');"
                                            class="btn activate-btn"
                                        >
                                            Activate
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty">
                            No users found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("userSearch");
    const rows = document.querySelectorAll("#userTable tr");

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