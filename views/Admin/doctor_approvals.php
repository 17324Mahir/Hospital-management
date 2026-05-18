<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Approvals - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="assets/css/Admindoctor_approvals.css">
    
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Doctor Approvals</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Doctor Approvals</h1>
        <p>Review pending doctor accounts and approve or reject them.</p>

        <div class="search-box">
            <input 
                type="text" 
                id="doctorSearch" 
                placeholder="Search by name, email, specialization, license..."
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
                    <th>Doctor ID</th>
                    <th>Name / Contact</th>
                    <th>Specialization</th>
                    <th>License</th>
                    <th>Experience</th>
                    <th>Fee</th>
                    <th>Bio</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="doctorTable">
                <?php if ($pendingDoctors && $pendingDoctors->num_rows > 0): ?>
                    <?php while ($doctor = $pendingDoctors->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($doctor['doctor_id']); ?></td>

                            <td>
                                Dr. <?= htmlspecialchars($doctor['name']); ?>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($doctor['email']); ?><br>
                                    <?= htmlspecialchars($doctor['mobile']); ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($doctor['specialization'] ?? '-'); ?></td>

                            <td><?= htmlspecialchars($doctor['license_number'] ?? '-'); ?></td>

                            <td>
                                <?= htmlspecialchars($doctor['experience_years'] ?? 0); ?> years
                            </td>

                            <td>
                                <?= htmlspecialchars(number_format((float)($doctor['consultation_fee'] ?? 0), 2)); ?> BDT
                            </td>

                            <td>
                                <?= htmlspecialchars(substr($doctor['bio'] ?? 'No bio', 0, 80)); ?>
                                <?= strlen($doctor['bio'] ?? '') > 80 ? '...' : ''; ?>
                            </td>

                            <td>
                                <span class="badge <?= htmlspecialchars($doctor['status']); ?>">
                                    <?= htmlspecialchars(ucfirst($doctor['status'])); ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($doctor['created_at']); ?></td>

                            <td>
                                <div class="actions">
                                    <a 
                                        href="Dashboard.php?action=approve_doctor&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                        onclick="return confirm('Approve this doctor?');"
                                        class="btn approve-btn"
                                    >
                                        Approve
                                    </a>

                                    <a 
                                        href="Dashboard.php?action=reject_doctor&id=<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                        onclick="return confirm('Reject this doctor?');"
                                        class="btn reject-btn"
                                    >
                                        Reject
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="empty">
                            No pending doctor approvals found.
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