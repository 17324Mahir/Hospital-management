<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Receptionists - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Adminmanagereceptionist.css">

    
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