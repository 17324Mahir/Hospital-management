<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Announcement - Admin Portal</title>
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
            max-width: 850px;
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
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .submit-btn {
            background: #3498db;
            margin-top: 20px;
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
            min-height: 140px;
            resize: vertical;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 14px;
            border-radius: 8px;
            line-height: 1.7;
            margin-bottom: 18px;
        }
    </style>
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