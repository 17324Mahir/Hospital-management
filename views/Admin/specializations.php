<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Specializations - Admin Portal</title>
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
            border-left: 5px solid #2ecc71;
        }

        .btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 9px 13px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .submit-btn {
            background: #2ecc71;
            margin-top: 15px;
        }

        .edit-btn {
            background: #3498db;
            margin-bottom: 0;
            padding: 7px 12px;
            font-size: 13px;
        }

        .delete-btn {
            background: #e74c3c;
            margin-bottom: 0;
            padding: 7px 12px;
            font-size: 13px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 2fr 160px;
            gap: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #2c3e50;
        }

        input, textarea, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
        }

        textarea {
            min-height: 45px;
            resize: vertical;
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
        }

        .active {
            background: #d4edda;
            color: #155724;
        }

        .inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #95a5a6;
        }

        @media (max-width: 900px) {
            .form-grid {
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
    </style>
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