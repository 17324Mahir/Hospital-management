<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Receptionist - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Admineditreceptionist.css">


    
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