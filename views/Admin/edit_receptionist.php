<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Receptionist - Admin Portal</title>
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
            max-width: 800px;
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
            border-left: 5px solid #8e44ad;
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
            background: #8e44ad;
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            margin-top: 15px;
            font-weight: bold;
            color: #2c3e50;
        }

        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
        }

        .readonly {
            background: #f1f2f6;
            color: #636e72;
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
    <div class="logo">🏥 HospitalCare | Edit Receptionist</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php?action=manage_receptionists" class="btn">⬅ Back to Manage Receptionists</a>

    <div class="card top-section">
        <h1>Edit Receptionist</h1>
        <p>Update receptionist name and phone number.</p>
    </div>

    <div class="card">

        <div class="info-box">
            <strong>User ID:</strong> #<?= htmlspecialchars($receptionist['id']); ?><br>
            <strong>Email:</strong> <?= htmlspecialchars($receptionist['email']); ?><br>
            <strong>Account Status:</strong> <?= $receptionist['is_active'] == 1 ? 'Active' : 'Inactive'; ?>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">

            <label for="name">Receptionist Name *</label>
            <input 
                type="text" 
                name="name" 
                id="name"
                value="<?= htmlspecialchars($receptionist['name']); ?>"
                required
            >

            <label for="email">Email</label>
            <input 
                type="email" 
                id="email"
                value="<?= htmlspecialchars($receptionist['email']); ?>"
                class="readonly"
                readonly
            >

            <label for="mobile">Mobile *</label>
            <input 
                type="text" 
                name="mobile" 
                id="mobile"
                value="<?= htmlspecialchars($receptionist['mobile']); ?>"
                required
            >

            <button type="submit" class="btn submit-btn">Update Receptionist</button>

        </form>
    </div>

</div>

</body>
</html>