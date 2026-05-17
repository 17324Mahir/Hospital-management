<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements - Patient Portal</title>
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
            max-width: 1000px;
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

        .announcement {
            border-left: 5px solid #3498db;
        }

        .announcement h2 {
            margin-top: 0;
            color: #2c3e50;
        }

        .meta {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .body-text {
            color: #333;
            line-height: 1.6;
            white-space: pre-line;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 8px;
        }

        .patient {
            background: #dff9fb;
            color: #130f40;
        }

        .all {
            background: #d4edda;
            color: #155724;
        }

        .btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #95a5a6;
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