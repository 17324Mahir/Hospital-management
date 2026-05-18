<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Adminmanageusers.css">


    
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