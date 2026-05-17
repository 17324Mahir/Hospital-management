<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Respond Complaint - Admin Portal</title>
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

        textarea, select {
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

        .readonly-box {
            background: #f8f9fa;
            padding: 14px;
            border-radius: 8px;
            line-height: 1.7;
            margin-bottom: 18px;
            white-space: pre-line;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .open {
            background: #fff3cd;
            color: #856404;
        }

        .resolved {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Respond Complaint</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php?action=complaints" class="btn">⬅ Back to Complaints</a>

    <div class="card top-section">
        <h1>Respond Complaint</h1>
        <p>Review patient complaint and save admin response.</p>
    </div>

    <div class="card">

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <h2><?= htmlspecialchars($complaint['subject']); ?></h2>

        <p>
            <span class="badge <?= htmlspecialchars($complaint['status']); ?>">
                <?= htmlspecialchars(ucfirst($complaint['status'])); ?>
            </span>
        </p>

        <div class="readonly-box">
            <strong>Patient:</strong>
            <?= htmlspecialchars($complaint['patient_name']); ?>

            <strong>Email:</strong>
            <?= htmlspecialchars($complaint['patient_email']); ?>

            <strong>Phone:</strong>
            <?= htmlspecialchars($complaint['patient_mobile']); ?>

            <strong>Created At:</strong>
            <?= htmlspecialchars($complaint['created_at']); ?>
        </div>

        <label>Complaint Message</label>
        <div class="readonly-box">
            <?= htmlspecialchars($complaint['message']); ?>
        </div>

        <form method="POST" action="">

            <label for="admin_response">Admin Response *</label>
            <textarea 
                name="admin_response" 
                id="admin_response"
                required
            ><?= htmlspecialchars($complaint['admin_response'] ?? ''); ?></textarea>

            <label for="status">Complaint Status *</label>
            <select name="status" id="status" required>
                <option value="open" <?= $complaint['status'] === 'open' ? 'selected' : ''; ?>>
                    Open
                </option>

                <option value="resolved" <?= $complaint['status'] === 'resolved' ? 'selected' : ''; ?>>
                    Resolved
                </option>
            </select>

            <button type="submit" class="btn submit-btn">
                Save Response
            </button>

        </form>
    </div>

</div>

</body>
</html>