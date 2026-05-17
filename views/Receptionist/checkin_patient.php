<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Check-in Patient</h1>
    <p>
        Mark today's confirmed appointments as checked in when patients arrive at the reception desk.
    </p>
</div>

<?php if (!empty($message)): ?>
    <div class="success">
        <?= htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="error">
        <?= htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="page-card">

    <h2>Search Appointment</h2>

    <div class="grid-2">
        <div>
            <label for="appointmentSearch">Search</label>
            <input 
                type="text" 
                id="appointmentSearch"
                placeholder="Search by patient name, phone, doctor, appointment ID..."
            >
        </div>

        <div style="display:flex; align-items:end;">
            <a href="Dashboard.php?action=daily_schedule" class="btn" style="width:100%; text-align:center;">
                View Daily Schedule
            </a>
        </div>
    </div>

</div>

<div class="page-card">

    <h2>Today's Confirmed Appointments</h2>

    <?php if ($appointments && $appointments->num_rows > 0): ?>

        <table id="checkinTable">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>For</th>
                    <th>Phone</th>
                    <th>Doctor</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($appointment = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td>
                            #<?= htmlspecialchars($appointment['appointment_id']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(date('h:i A', strtotime($appointment['appointment_time']))); ?>
                        </td>

                        <td>
                            <strong><?= htmlspecialchars($appointment['patient_name']); ?></strong>
                            <br>
                            <span class="small">
                                Patient ID:
                                <?= htmlspecialchars($appointment['patient_id']); ?>
                            </span>
                        </td>

                        <td>
                            <?php if (!empty($appointment['dependent_name'])): ?>
                                <?= htmlspecialchars($appointment['dependent_name']); ?>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($appointment['relationship']); ?>
                                </span>
                            <?php else: ?>
                                Self
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment['patient_mobile']); ?>
                        </td>

                        <td>
                            Dr. <?= htmlspecialchars($appointment['doctor_name']); ?>
                            <br>
                            <span class="small">
                                <?= htmlspecialchars($appointment['specialization']); ?>
                            </span>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment['reason']); ?>
                        </td>

                        <td>
                            <span class="status confirmed">
                                Confirmed
                            </span>
                        </td>

                        <td>
                            <a 
                                href="Dashboard.php?action=mark_checked_in&id=<?= htmlspecialchars($appointment['appointment_id']); ?>"
                                class="btn btn-green"
                                onclick="return confirm('Are you sure you want to check in this patient?');"
                            >
                                Check In
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="empty">
            No confirmed appointments available for check-in today.
            <br><br>

            <a href="Dashboard.php?action=walkin_booking" class="btn btn-orange">
                Book Walk-in Appointment
            </a>
        </div>

    <?php endif; ?>

</div>

<div class="page-card">
    <a href="Dashboard.php" class="btn btn-gray">
         Back to Dashboard
    </a>

    <a href="Dashboard.php?action=waiting_queue" class="btn">
        View Waiting Queue
    </a>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("appointmentSearch");
    const table = document.getElementById("checkinTable");

    if (!searchInput || !table) {
        return;
    }

    searchInput.addEventListener("keyup", function () {
        const keyword = searchInput.value.toLowerCase();
        const rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            const rowText = rows[i].innerText.toLowerCase();

            if (rowText.includes(keyword)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });
});
</script>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>