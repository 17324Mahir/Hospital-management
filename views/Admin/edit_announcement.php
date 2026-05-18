<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Announcement - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Admineditannouncement.css">

    
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Edit Announcement</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php?action=announcements" class="btn">⬅ Back to Announcements</a>

    <div class="card top-section">
        <h1>Edit Announcement</h1>
        <p>Update announcement title, message, and target role.</p>
    </div>

    <div class="card">

        <div class="info-box">
            <strong>Announcement ID:</strong> #<?= htmlspecialchars($announcement['id']); ?><br>
            <strong>Published At:</strong> <?= htmlspecialchars($announcement['published_at']); ?>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">

            <label for="title">Title *</label>
            <input 
                type="text" 
                name="title" 
                id="title"
                value="<?= htmlspecialchars($announcement['title']); ?>"
                required
            >

            <label for="target_role">Target Role *</label>
            <select name="target_role" id="target_role" required>
                <option value="all" <?= $announcement['target_role'] === 'all' ? 'selected' : ''; ?>>
                    All Users
                </option>

                <option value="patient" <?= $announcement['target_role'] === 'patient' ? 'selected' : ''; ?>>
                    Patients
                </option>

                <option value="doctor" <?= $announcement['target_role'] === 'doctor' ? 'selected' : ''; ?>>
                    Doctors
                </option>
            </select>

            <label for="body">Message *</label>
            <textarea 
                name="body" 
                id="body"
                required
            ><?= htmlspecialchars($announcement['body']); ?></textarea>

            <button type="submit" class="btn submit-btn">Update Announcement</button>

        </form>
    </div>

</div>

</body>
</html>