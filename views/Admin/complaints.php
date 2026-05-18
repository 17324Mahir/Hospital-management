<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaints / Feedback - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/Admincomplaints.css">
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Complaints / Feedback</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Complaints / Feedback</h1>
        <p>Review patient complaints and send admin responses.</p>

        <div class="search-box">
            <input 
                type="text" 
                id="complaintSearch" 
                placeholder="Search by patient, subject, status, message..."
            >
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="success"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div id="complaintList">
        <?php if ($complaints && $complaints->num_rows > 0): ?>
            <?php while ($complaint = $complaints->fetch_assoc()): ?>
                <div class="complaint-card <?= $complaint['status'] === 'resolved' ? 'resolved-card' : ''; ?>">
                    <span class="badge <?= htmlspecialchars($complaint['status']); ?>">
                        <?= htmlspecialchars(ucfirst($complaint['status'])); ?>
                    </span>

                    <h3><?= htmlspecialchars($complaint['subject']); ?></h3>

                    <div class="meta">
                        <strong>Patient:</strong>
                        <?= htmlspecialchars($complaint['patient_name']); ?>
                        |
                        <?= htmlspecialchars($complaint['patient_email']); ?>
                        |
                        <?= htmlspecialchars($complaint['patient_mobile']); ?>
                        <br>

                        <strong>Created:</strong>
                        <?= htmlspecialchars($complaint['created_at']); ?>

                        <?php if (!empty($complaint['resolved_at'])): ?>
                            |
                            <strong>Resolved:</strong>
                            <?= htmlspecialchars($complaint['resolved_at']); ?>
                        <?php endif; ?>
                    </div>

                    <strong>Complaint Message:</strong>
                    <div class="message-box">
                        <?= htmlspecialchars($complaint['message']); ?>
                    </div>

                    <?php if (!empty($complaint['admin_response'])): ?>
                        <br>
                        <strong>Admin Response:</strong>
                        <div class="message-box">
                            <?= htmlspecialchars($complaint['admin_response']); ?>
                        </div>
                    <?php endif; ?>

                    <br>

                    <a 
                        href="Dashboard.php?action=respond_complaint&id=<?= htmlspecialchars($complaint['id']); ?>"
                        class="btn respond-btn"
                    >
                        Respond / Update
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="card empty">
                No complaints found.
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("complaintSearch");
    const cards = document.querySelectorAll(".complaint-card");

    searchInput.addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();

        cards.forEach(function (card) {
            const text = card.innerText.toLowerCase();

            if (text.includes(keyword)) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    });
});
</script>

</body>
</html>