<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Logs - Admin Portal</title>
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
            border-left: 5px solid #7f8c8d;
        }

        .btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 9px 13px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .sample-btn {
            background: #3498db;
            margin-left: 8px;
        }

        .clear-btn {
            background: #e74c3c;
            margin-left: 8px;
        }

        .print-btn {
            background: #34495e;
            margin-left: 8px;
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

        .summary {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .summary-box {
            background: #f8f9fa;
            padding: 12px 16px;
            border-radius: 8px;
            color: #2c3e50;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            vertical-align: top;
        }

        th {
            color: #7f8c8d;
            font-size: 13px;
            text-transform: uppercase;
        }

        .log-id {
            font-weight: bold;
            color: #2c3e50;
        }

        .actor {
            font-weight: bold;
            color: #34495e;
        }

        .action-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            line-height: 1.6;
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

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .sample-btn, .clear-btn, .print-btn {
                margin-left: 0;
            }
        }

        @media print {
            .navbar, .btn-area, .search-box {
                display: none;
            }

            body {
                background: white;
            }

            .container {
                padding: 0;
                max-width: 100%;
            }

            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
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