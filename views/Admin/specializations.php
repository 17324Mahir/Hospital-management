<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Specializations - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Adminspecialization.css">

    
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Specializations</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Manage Specializations</h1>
        <p>Add and manage doctor specialization categories.</p>
    </div>

    <?php if (!empty($message)): ?>
        <div class="success"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Add New Specialization</h2>

        <form method="POST" action="">
            <div class="form-grid">
                <div>
                    <label for="name">Name *</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        placeholder="Example: Cardiology"
                        required
                    >
                </div>

                <div>
                    <label for="description">Description</label>
                    <textarea 
                        name="description" 
                        id="description"
                        placeholder="Short description..."
                    ></textarea>
                </div>

                <div>
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn submit-btn">Add Specialization</button>
        </form>
    </div>

    <div class="card">
        <h2>All Specializations</h2>

        <div class="search-box">
            <input 
                type="text" 
                id="specializationSearch" 
                placeholder="Search specialization..."
            >
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="specializationTable">
                <?php if ($specializations && $specializations->num_rows > 0): ?>
                    <?php while ($specialization = $specializations->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($specialization['id']); ?></td>

                            <td><?= htmlspecialchars($specialization['name']); ?></td>

                            <td><?= htmlspecialchars($specialization['description'] ?? '-'); ?></td>

                            <td>
                                <span class="badge <?= htmlspecialchars($specialization['status']); ?>">
                                    <?= htmlspecialchars(ucfirst($specialization['status'])); ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($specialization['created_at']); ?></td>

                            <td>
                                <div class="actions">
                                    <a 
                                        href="Dashboard.php?action=edit_specialization&id=<?= htmlspecialchars($specialization['id']); ?>"
                                        class="btn edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <a 
                                        href="Dashboard.php?action=delete_specialization&id=<?= htmlspecialchars($specialization['id']); ?>"
                                        onclick="return confirm('Delete this specialization?');"
                                        class="btn delete-btn"
                                    >
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty">
                            No specializations found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("specializationSearch");
    const rows = document.querySelectorAll("#specializationTable tr");

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