<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Specialization - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Admineditsepecialization.css">

    
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