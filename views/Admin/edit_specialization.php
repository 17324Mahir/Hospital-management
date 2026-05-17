<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Specialization - Admin Portal</title>
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
            min-height: 110px;
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
    <div class="logo">🏥 HospitalCare | Edit Specialization</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php?action=specializations" class="btn">⬅ Back to Specializations</a>

    <div class="card top-section">
        <h1>Edit Specialization</h1>
        <p>Update specialization name, description, and status.</p>
    </div>

    <div class="card">

        <div class="info-box">
            <strong>Specialization ID:</strong> #<?= htmlspecialchars($specialization['id']); ?><br>
            <strong>Created At:</strong> <?= htmlspecialchars($specialization['created_at']); ?>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">

            <label for="name">Name *</label>
            <input 
                type="text" 
                name="name" 
                id="name"
                value="<?= htmlspecialchars($specialization['name']); ?>"
                required
            >

            <label for="description">Description</label>
            <textarea 
                name="description" 
                id="description"
            ><?= htmlspecialchars($specialization['description'] ?? ''); ?></textarea>

            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="active" <?= $specialization['status'] === 'active' ? 'selected' : ''; ?>>
                    Active
                </option>

                <option value="inactive" <?= $specialization['status'] === 'inactive' ? 'selected' : ''; ?>>
                    Inactive
                </option>
            </select>

            <button type="submit" class="btn submit-btn">Update Specialization</button>

        </form>
    </div>

</div>

</body>
</html>