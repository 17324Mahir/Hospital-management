<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Doctors - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Adminmanagedoctors.css">


    
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Manage Doctors</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Manage Doctors</h1>
        <p>View, edit, approve/reject, and activate/deactivate doctor accounts.</p>

        <div class="search-box">
            <input 
                type="text" 
                id="doctorSearch" 
                placeholder="Search by doctor name, email, specialization, license, status..."
            >
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
                    <th>Doctor</th>
                    <th>Contact</th>
                    <th>Specialization</th>
                    <th>License</th>
                    <th>Experience</th>
                    <th>Fee</th>
                    <th>Approval</th>
                    <th>Account</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="doctorTable">
                <?php if ($doctors && $doctors->num_rows > 0): ?>
                    <?php while ($doctor = $doctors->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong>Dr. <?= htmlspecialchars($doctor['name']); ?></strong>
                                <br>
                                <span class="small">Doctor ID: #<?= htmlspecialchars($doctor['doctor_id']); ?></span>
                            </td>

                            <td>
                                <?= htmlspecialchars($doctor['email']); ?>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($doctor['mobile'] ?? $doctor['phone'] ?? '-'); ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($doctor['specialization'] ?? '-'); ?></td>

                            <td><?= htmlspecialchars($doctor['license_number'] ?? '-'); ?></td>

                            <td><?= htmlspecialchars($doctor['experience_years'] ?? 0); ?> years</td>

                            <td><?= htmlspecialchars(number_format((float)($doctor['consultation_fee'] ?? 0), 2)); ?> BDT</td>

                            <td>
                                <span class="badge <?= htmlspecialchars($doctor['doctor_status']); ?>">
                                    <?= htmlspecialchars(ucfirst($doctor['doctor_status'])); ?>
                                </span>
                                <br>
                                <span class="small">
                                    Approved: <?= $doctor['is_approved'] == 1 ? 'Yes' : 'No'; ?>
                                </span>
                            </td>

                            <td>
                                <?php if ($doctor['is_active'] == 1): ?>
                                    <span class="badge active">Active</span>
                                <?php else: ?>
                                    <span class="badge inactive">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($doctor['created_at']); ?></td>

                            <td>
                                <div class="actions">

                                    <a 
                                        href="Dashboard.php?action=edit_doctor&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                        class="btn edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <?php if ($doctor['is_active'] == 1): ?>
                                        <a 
                                            href="Dashboard.php?action=toggle_doctor_user_status&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                            onclick="return confirm('Deactivate this doctor account?');"
                                            class="btn inactive-btn"
                                        >
                                            Deactivate
                                        </a>
                                    <?php else: ?>
                                        <a 
                                            href="Dashboard.php?action=toggle_doctor_user_status&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                            onclick="return confirm('Activate this doctor account?');"
                                            class="btn active-btn"
                                        >
                                            Activate
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($doctor['is_approved'] != 1 || $doctor['doctor_status'] !== 'approved'): ?>
                                        <a 
                                            href="Dashboard.php?action=manage_approve_doctor&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                            onclick="return confirm('Approve this doctor?');"
                                            class="btn approve-btn"
                                        >
                                            Approve
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($doctor['doctor_status'] !== 'rejected'): ?>
                                        <a 
                                            href="Dashboard.php?action=manage_reject_doctor&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                            onclick="return confirm('Reject this doctor?');"
                                            class="btn reject-btn"
                                        >
                                            Reject
                                        </a>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="empty">
                            No doctors found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("doctorSearch");
    const rows = document.querySelectorAll("#doctorTable tr");

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