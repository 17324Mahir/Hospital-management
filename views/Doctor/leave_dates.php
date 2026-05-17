<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leave Dates - Doctor Portal</title>
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
            max-width: 1000px;
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
            border-left: 5px solid #e67e22;
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
            background: #e67e22;
            margin-top: 15px;
        }

        .delete-btn {
            background: #e74c3c;
            color: white;
            padding: 6px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 12px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #2c3e50;
        }

        input, textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
        }

        textarea {
            min-height: 80px;
            resize: vertical;
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

        .note {
            background: #fff3cd;
            color: #856404;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 14px;
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

        .empty {
            text-align: center;
            padding: 30px;
            color: #95a5a6;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .navbar {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Leave Dates</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Leave Dates</h1>
        <p>Mark specific dates when you are unavailable. Patients cannot book appointments on leave dates.</p>

        <div class="note">
            This page connects with the patient AJAX slot system. If you add a leave date, that date will show no available slots to patients.
        </div>
    </div>

    <div class="card">

        <h2>Add Leave Date</h2>

        <?php if (!empty($message)): ?>
            <div class="success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
            <div class="success">Leave date deleted successfully.</div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-grid">
                <div>
                    <label for="leave_date">Leave Date *</label>
                    <input 
                        type="date" 
                        name="leave_date" 
                        id="leave_date" 
                        min="<?= date('Y-m-d'); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="reason">Reason</label>
                    <textarea 
                        name="reason" 
                        id="reason" 
                        placeholder="Example: Personal leave, conference, emergency..."
                    ></textarea>
                </div>
            </div>

            <button type="submit" class="btn submit-btn">Add Leave Date</button>
        </form>
    </div>

    <div class="card">
        <h2>My Leave Dates</h2>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Reason</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($leaveDates && $leaveDates->num_rows > 0): ?>
                    <?php while ($leave = $leaveDates->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($leave['leave_date']); ?></td>

                            <td><?= htmlspecialchars(date('l', strtotime($leave['leave_date']))); ?></td>

                            <td>
                                <?= !empty($leave['reason']) 
                                    ? htmlspecialchars($leave['reason']) 
                                    : '-'; ?>
                            </td>

                            <td>
                                <a 
                                    href="Dashboard.php?action=delete_leave_date&id=<?= htmlspecialchars($leave['id']); ?>"
                                    onclick="return confirm('Are you sure you want to delete this leave date?');"
                                    class="delete-btn"
                                >
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="empty">
                            No leave dates added yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>