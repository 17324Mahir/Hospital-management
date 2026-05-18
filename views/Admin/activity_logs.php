<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Logs - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Adminactivity_logs.css">


</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Activity Logs</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="btn-area">
        <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

        <a 
            href="Dashboard.php?action=add_sample_log"
            class="btn sample-btn"
        >
            ➕ Add Sample Log
        </a>

        <a 
            href="Dashboard.php?action=clear_activity_logs"
            onclick="return confirm('Are you sure you want to clear all activity logs?');"
            class="btn clear-btn"
        >
            🗑 Clear Logs
        </a>

        <button onclick="window.print()" class="btn print-btn">
            🖨 Print Logs
        </button>
    </div>

    <div class="card top-section">
        <h1>Activity Logs</h1>
        <p>View important system actions performed by receptionists and admins.</p>

        <div class="search-box">
            <input 
                type="text" 
                id="logSearch" 
                placeholder="Search by user name, action, date, log ID..."
            >
        </div>

        <div class="summary">
            <div class="summary-box">
                Total Logs:
                <?php
                    $totalLogs = 0;
                    if ($logs) {
                        $totalLogs = $logs->num_rows;
                    }
                    echo htmlspecialchars($totalLogs);
                ?>
            </div>

            <div class="summary-box">
                Generated At:
                <?= htmlspecialchars(date('Y-m-d H:i:s')); ?>
            </div>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="success"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Performed By</th>
                    <th>Action</th>
                    <th>Date / Time</th>
                </tr>
            </thead>

            <tbody id="logTable">
                <?php if ($logs && $logs->num_rows > 0): ?>
                    <?php while ($log = $logs->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <span class="log-id">
                                    #<?= htmlspecialchars($log['id']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="actor">
                                    <?= htmlspecialchars($log['receptionist_name'] ?? 'Unknown'); ?>
                                </span>
                            </td>

                            <td>
                                <div class="action-box">
                                    <?= htmlspecialchars($log['action_performed'] ?? '-'); ?>
                                </div>
                            </td>

                            <td>
                                <?= htmlspecialchars($log['created_at']); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="empty">
                            No activity logs found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("logSearch");
    const rows = document.querySelectorAll("#logTable tr");

    searchInput.addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();

        rows.forEach(function (row) {
            const text = row.innerText.toLowerCase();

            if (text.includes(keyword)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});
</script>

</body>
</html>