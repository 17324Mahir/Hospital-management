<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Manage Appointments</h1>
    <p>
        Receptionist can cancel or reschedule active appointments from this page.
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
                placeholder="Search by patient, doctor, phone, appointment ID, status..."
            >
        </div>

        <div style="display:flex; align-items:end;">
            <a href="Dashboard.php?action=walkin_booking" class="btn btn-orange" style="width:100%; text-align:center;">
                Book New Appointment
            </a>
        </div>
    </div>

</div>

<div class="page-card">

    <h2>Active Appointments</h2>

    <?php if ($appointments && $appointments->num_rows > 0): ?>

        <table id="appointmentTable">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Date & Time</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Booked By</th>
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
                            <strong><?= htmlspecialchars($appointment['appointment_date']); ?></strong>
                            <br>
                            <span class="small">
                                <?= htmlspecialchars(date('h:i A', strtotime($appointment['appointment_time']))); ?>
                            </span>
                        </td>

                        <td>
                            <strong><?= htmlspecialchars($appointment['patient_name']); ?></strong>
                            <br>

                            <span class="small">
                                Patient ID:
                                <?= htmlspecialchars($appointment['patient_id']); ?>
                            </span>
                            <br>

                            <span class="small">
                                Phone:
                                <?= htmlspecialchars($appointment['patient_mobile']); ?>
                            </span>

                            <?php if (!empty($appointment['dependent_name'])): ?>
                                <br>
                                <span class="small">
                                    For:
                                    <?= htmlspecialchars($appointment['dependent_name']); ?>
                                    |
                                    <?= htmlspecialchars($appointment['relationship']); ?>
                                </span>
                            <?php endif; ?>
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
                            <span class="status <?= htmlspecialchars($appointment['status']); ?>">
                                <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $appointment['status']))); ?>
                            </span>

                            <?php if (!empty($appointment['checkin_time'])): ?>
                                <br>
                                <span class="small">
                                    Checked in:
                                    <?= htmlspecialchars(date('h:i A', strtotime($appointment['checkin_time']))); ?>
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(ucfirst($appointment['booked_by'])); ?>
                        </td>

                        <td>
                            <?php if (in_array($appointment['status'], ['pending', 'confirmed'])): ?>

                                <a 
                                    href="Dashboard.php?action=reschedule_appointment&id=<?= htmlspecialchars($appointment['appointment_id']); ?>"
                                    class="btn btn-orange"
                                    style="margin-bottom:6px;"
                                >
                                    Reschedule
                                </a>

                                <br>

                                <a 
                                    href="Dashboard.php?action=cancel_appointment&id=<?= htmlspecialchars($appointment['appointment_id']); ?>"
                                    class="btn btn-red"
                                    onclick="return confirm('Are you sure you want to cancel this appointment?');"
                                >
                                    Cancel
                                </a>

                            <?php else: ?>

                                <span class="small">
                                    No action available
                                </span>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="empty">
            No active appointments found.
            <br><br>

            <a href="Dashboard.php?action=walkin_booking" class="btn btn-orange">
                Book Walk-in Appointment
            </a>
        </div>

    <?php endif; ?>

</div>

<div class="page-card">
    <a href="Dashboard.php" class="btn btn-gray">
        ⬅ Back to Dashboard
    </a>

    <a href="Dashboard.php?action=daily_schedule" class="btn">
        View Daily Schedule
    </a>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("appointmentSearch");
    const table = document.getElementById("appointmentTable");

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