<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaints / Feedback - Admin Portal</title>
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
            max-width: 1150px;
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
            border-left: 5px solid #e74c3c;
        }

        .btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 9px 13px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .respond-btn {
            background: #3498db;
            margin-bottom: 0;
            padding: 7px 12px;
            font-size: 13px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .search-box {
            margin-top: 15px;
        }

        .search-box input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
        }

        .complaint-card {
            background: #f8f9fa;
            border-left: 5px solid #e74c3c;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .complaint-card.resolved-card {
            border-left-color: #27ae60;
        }

        .complaint-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .meta {
            color: #7f8c8d;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 10px;
        }

        .message-box {
            background: white;
            border: 1px solid #eee;
            padding: 12px;
            border-radius: 6px;
            white-space: pre-line;
            line-height: 1.6;
            margin-top: 8px;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 8px;
        }

        .open {
            background: #fff3cd;
            color: #856404;
        }

        .resolved {
            background: #d4edda;
            color: #155724;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #95a5a6;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
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