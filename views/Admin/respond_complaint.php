<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Respond Complaint - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="assets/css/Adminrespondcomplaint.css">

    
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