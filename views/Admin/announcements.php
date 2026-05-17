<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements - Admin Portal</title>
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
            border-left: 5px solid #9b59b6;
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
            background: #9b59b6;
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

        label {
            display: block;
            margin-bottom: 6px;
            margin-top: 15px;
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
            min-height: 120px;
            resize: vertical;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
        }

        .full {
            grid-column: 1 / -1;
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

        .announcement-card {
            background: #f8f9fa;
            border-left: 5px solid #9b59b6;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .announcement-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .meta {
            color: #7f8c8d;
            font-size: 13px;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .message {
            white-space: pre-line;
            line-height: 1.7;
            color: #333;
            margin-bottom: 12px;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .all {
            background: #dff9fb;
            color: #130f40;
        }

        .patient {
            background: #d4edda;
            color: #155724;
        }

        .doctor {
            background: #d1ecf1;
            color: #0c5460;
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

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: auto;
            }

            .navbar {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Announcements</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Announcements</h1>
        <p>Create and manage announcements for patients, doctors, or all users.</p>
    </div>

    <?php if (!empty($message)): ?>
        <div class="success"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Create New Announcement</h2>

        <form method="POST" action="">

            <div class="form-grid">

                <div>
                    <label for="title">Title *</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        placeholder="Example: Clinic Closed Notice"
                        required
                    >
                </div>

                <div>
                    <label for="target_role">Target Role *</label>
                    <select name="target_role" id="target_role" required>
                        <option value="all">All Users</option>
                        <option value="patient">Patients</option>
                        <option value="doctor">Doctors</option>
                    </select>
                </div>

                <div class="full">
                    <label for="body">Message *</label>
                    <textarea 
                        name="body" 
                        id="body"
                        placeholder="Write announcement message..."
                        required
                    ></textarea>
                </div>

            </div>

            <button type="submit" class="btn submit-btn">Publish Announcement</button>

        </form>
    </div>

    <div class="card">
        <h2>All Announcements</h2>

        <?php if ($announcements && $announcements->num_rows > 0): ?>
            <?php while ($announcement = $announcements->fetch_assoc()): ?>
                <div class="announcement-card">
                    <h3><?= htmlspecialchars($announcement['title']); ?></h3>

                    <div class="meta">
                        <span class="badge <?= htmlspecialchars($announcement['target_role']); ?>">
                            <?= htmlspecialchars(ucfirst($announcement['target_role'])); ?>
                        </span>
                        <br>
                        Author:
                        <?= htmlspecialchars($announcement['author_name'] ?? 'Unknown'); ?>
                        |
                        Published:
                        <?= htmlspecialchars($announcement['published_at']); ?>
                    </div>

                    <div class="message">
                        <?= htmlspecialchars($announcement['body']); ?>
                    </div>

                    <div class="actions">
                        <a 
                            href="Dashboard.php?action=edit_announcement&id=<?= htmlspecialchars($announcement['id']); ?>"
                            class="btn edit-btn"
                        >
                            Edit
                        </a>

                        <a 
                            href="Dashboard.php?action=delete_announcement&id=<?= htmlspecialchars($announcement['id']); ?>"
                            onclick="return confirm('Delete this announcement?');"
                            class="btn delete-btn"
                        >
                            Delete
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty">
                No announcements found.
            </div>
        <?php endif; ?>
    </div>

</div>

</body>
</html>