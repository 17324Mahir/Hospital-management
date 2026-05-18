<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="assets/css/Adminannouncment.css">
    
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