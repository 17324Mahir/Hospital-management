<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Daily Appointment Schedule</h1>
    <p>
        View today's appointments grouped by doctor. Receptionist can easily check patient time, status, and booking details.
    </p>
</div>

<div class="page-card">

    <h2>Today's Schedule</h2>

    <?php
        $totalAppointments = 0;

        if (!empty($groupedAppointments)) {
            foreach ($groupedAppointments as $group) {
                $totalAppointments += count($group['appointments']);
            }
        }
    ?>

    <div class="grid-3">

        <div class="stat-card">
            <h3>Total Doctors</h3>
            <div class="number">
                <?= htmlspecialchars(count($groupedAppointments)); ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>Total Appointments</h3>
            <div class="number">
                <?= htmlspecialchars($totalAppointments); ?>
            </div>
        </div>

        <div class="stat-card">
            <h3>Date</h3>
            <div class="number" style="font-size:22px;">
                <?= htmlspecialchars(date('d M Y')); ?>
            </div>
        </div>

    </div>

</div>

<?php if (!empty($groupedAppointments)): ?>

    <?php foreach ($groupedAppointments as $group): ?>

        <div class="page-card">

            <h2>
                Dr. <?= htmlspecialchars($group['doctor_name']); ?>
            </h2>

            <p>
                <strong>Specialization:</strong>
                <?= htmlspecialchars($group['specialization']); ?>
                |
                <strong>Total Appointments:</strong>
                <?= htmlspecialchars(count($group['appointments'])); ?>
            </p>

            <table>
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>For</th>
                        <th>Phone</th>
                        <th>Reason</th>
                        <th>Booked By</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($group['appointments'] as $appointment): ?>
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
                                <?= htmlspecialchars($appointment['reason']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(ucfirst($appointment['booked_by'])); ?>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="page-card">
        <div class="empty">
            No appointments found for today.
            <br><br>

            <a href="Dashboard.php?action=walkin_booking" class="btn btn-orange">
                Book Walk-in Appointment
            </a>
        </div>
    </div>

<?php endif; ?>

<div class="page-card">
    <a href="Dashboard.php" class="btn btn-gray">
       Back to Dashboard
    </a>

    <a href="Dashboard.php?action=checkin_patient" class="btn btn-green">
        Check-in Patient
    </a>

    <button onclick="window.print()" class="btn">
      Print Schedule
    </button>
</div>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>