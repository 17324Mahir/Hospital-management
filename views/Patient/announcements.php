<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements - Patient Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/announcements.css">
    
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
        <h1>Hospital Announcements</h1>
        <p>Read hospital notices and updates for patients.</p>
    </div>

    <?php if ($announcements && $announcements->num_rows > 0): ?>
        <?php while ($announcement = $announcements->fetch_assoc()): ?>
            <div class="card announcement">
                <h2>
                    <?= htmlspecialchars($announcement['title']); ?>

                    <span class="badge <?= htmlspecialchars($announcement['target_role']); ?>">
                        <?= htmlspecialchars(ucfirst($announcement['target_role'])); ?>
                    </span>
                </h2>

                <div class="meta">
                    Posted by:
                    <?= htmlspecialchars($announcement['author_name'] ?? 'Admin'); ?>
                    |
                    Published:
                    <?= htmlspecialchars($announcement['published_at']); ?>
                </div>

                <div class="body-text">
                    <?= htmlspecialchars($announcement['body']); ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="card empty">
            No announcements found.
        </div>
    <?php endif; ?>

</div>

</body>
</html>