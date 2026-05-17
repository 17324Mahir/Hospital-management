<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Availability - Doctor Portal</title>
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
            border-left: 5px solid #16a085;
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
            background: #16a085;
            margin-top: 20px;
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
        }

        th {
            color: #7f8c8d;
            font-size: 13px;
            text-transform: uppercase;
        }

        input[type="time"],
        input[type="number"] {
            width: 100%;
            padding: 9px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        input[type="checkbox"] {
            transform: scale(1.2);
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
            background: #eaf6ff;
            color: #2c3e50;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 14px;
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
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Weekly Availability</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Weekly Availability</h1>
        <p>Set your weekly working hours and appointment slot duration.</p>

        <div class="note">
            Example: If you set 10:00 AM to 2:00 PM with 30-minute slots, patients will see 10:00, 10:30, 11:00, etc.
        </div>
    </div>

    <div class="card">

        <?php if (!empty($message)): ?>
            <div class="success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">

            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Available?</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Slot Duration</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($days as $day): ?>
                        <?php
                            $row = $availability[$day] ?? null;

                            $isAvailable = $row ? (int)$row['is_available'] : 0;
                            $startTime = $row ? substr($row['start_time'], 0, 5) : '10:00';
                            $endTime = $row ? substr($row['end_time'], 0, 5) : '14:00';
                            $slotDuration = $row ? $row['slot_duration_minutes'] : 30;
                        ?>

                        <tr>
                            <td><strong><?= htmlspecialchars($day); ?></strong></td>

                            <td>
                                <input 
                                    type="checkbox" 
                                    name="is_available[<?= htmlspecialchars($day); ?>]" 
                                    value="1"
                                    <?= $isAvailable ? 'checked' : ''; ?>
                                >
                            </td>

                            <td>
                                <input 
                                    type="time" 
                                    name="start_time[<?= htmlspecialchars($day); ?>]" 
                                    value="<?= htmlspecialchars($startTime); ?>"
                                >
                            </td>

                            <td>
                                <input 
                                    type="time" 
                                    name="end_time[<?= htmlspecialchars($day); ?>]" 
                                    value="<?= htmlspecialchars($endTime); ?>"
                                >
                            </td>

                            <td>
                                <input 
                                    type="number" 
                                    name="slot_duration[<?= htmlspecialchars($day); ?>]" 
                                    value="<?= htmlspecialchars($slotDuration); ?>"
                                    min="5"
                                    step="5"
                                >
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn submit-btn">Save Availability</button>

        </form>
    </div>

</div>

</body>
</html>