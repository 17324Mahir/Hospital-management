<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard - Hospital System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Patientdashboard.css">

</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Patient Portal</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <?php if (isset($_GET['cancelled']) && $_GET['cancelled'] == 1): ?>
    <div style="background:#d4edda; color:#155724; padding:12px; border-radius:6px; margin-bottom:15px;">
        Appointment cancelled successfully.
    </div>
<?php endif; ?>

     <?php if (isset($_GET['rescheduled']) && $_GET['rescheduled'] == 1): ?>
    <div style="background:#d4edda; color:#155724; padding:12px; border-radius:6px; margin-bottom:15px;">
        Appointment rescheduled successfully. Status changed to Pending.
    </div>
<?php endif; ?>

    <div class="welcome-section">
        <h1>Welcome back, <?= htmlspecialchars($_SESSION['name']); ?>!</h1>
        <p>View your appointment history and manage your patient services.</p>
    </div>

    <div class="grid">

        <div class="card">
            <h2>My Appointment History</h2>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>For</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Reason</th>
                        <th>Status</th>
                         <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($appointments && $appointments->num_rows > 0): ?>
                        <?php while ($row = $appointments->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['appointment_date']); ?></td>
                                <td><?= htmlspecialchars($row['appointment_time']); ?></td>
                                <td>
    <?php if (!empty($row['dependent_name'])): ?>
        <?= htmlspecialchars($row['dependent_name']); ?>
        <br>
        <small><?= htmlspecialchars($row['relationship']); ?></small>
    <?php else: ?>
        Self
    <?php endif; ?>
</td>
                                <td>Dr. <?= htmlspecialchars($row['doctor_name']); ?></td>
                                <td><?= htmlspecialchars($row['specialization']); ?></td>
                                <td><?= htmlspecialchars($row['reason']); ?></td>
                                <td>
                                    <span class="status-pill <?= htmlspecialchars($row['status']); ?>">
                                        <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $row['status']))); ?>
                                    </span>
                                </td>
                               <td>
    <?php if ($row['status'] === 'pending' || $row['status'] === 'confirmed'): ?>

        <a 
            href="Dashboard.php?action=reschedule_appointment&id=<?= $row['id']; ?>"
            style="background:#e67e22; color:white; padding:6px 10px; border-radius:5px; text-decoration:none; font-size:12px; display:inline-block; margin-bottom:5px;"
        >
            Reschedule
        </a>

        <a 
            href="Dashboard.php?action=cancel_appointment&id=<?= $row['id']; ?>"
            onclick="return confirm('Are you sure you want to cancel this appointment?');"
            style="background:#e74c3c; color:white; padding:6px 10px; border-radius:5px; text-decoration:none; font-size:12px; display:inline-block;"
        >
            Cancel
        </a>

    <?php else: ?>
        -
    <?php endif; ?>
            </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="empty">
                                No appointment history found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Quick Actions</h2>

            <a href="Dashboard.php?action=doctors" class="btn btn-booking">
                    📅 Book New Appointment
             </a>

            <a href="Dashboard.php?action=profile" class="btn btn-profile">
                👤 My Profile
            </a>

            <a href="Dashboard.php?action=billing" class="btn btn-billing">
                💳 Billing History
            </a>

            <a href="Dashboard.php?action=reviews" class="btn" style="background:#f1c40f; width:100%; text-align:center; box-sizing:border-box;">
                   ⭐ Doctor Reviews
            </a>

             <a href="Dashboard.php?action=announcements" class="btn" style="background:#9b59b6; width:100%; text-align:center; box-sizing:border-box;">
             📢 Announcements
              </a>
            <a href="Dashboard.php?action=dependents" class="btn" style="background:#16a085; width:100%; text-align:center; box-sizing:border-box;">
                 👨‍👩‍👧 Family Dependents
              </a>
              <a href="Dashboard.php?action=consultation_notes" class="btn" style="background:#2ecc71; width:100%; text-align:center; box-sizing:border-box;">
               📝 Consultation Notes
               </a>
        </div>

    </div>
</div>

</body>
</html>