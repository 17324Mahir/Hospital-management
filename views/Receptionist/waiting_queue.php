<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Waiting Room Queue</h1>
    <p>
        View all checked-in patients who are waiting for doctors.
        Queue is grouped doctor-wise for easy front desk management.
    </p>
</div>

<?php
$totalDoctors = 0;
$totalWaiting = 0;

if (!empty($groupedQueue)) {
    $totalDoctors = count($groupedQueue);

    foreach ($groupedQueue as $group) {
        $totalWaiting += count($group['patients']);
    }
}
?>

<div class="grid-3">

    <div class="stat-card">
        <h3>Total Doctors</h3>
        <div class="number">
            <?= htmlspecialchars($totalDoctors); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Total Waiting</h3>
        <div class="number">
            <?= htmlspecialchars($totalWaiting); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Current Time</h3>
        <div class="number" style="font-size:22px;">
            <?= htmlspecialchars(date('h:i A')); ?>
        </div>
    </div>

</div>

<div class="page-card">
    <a href="Dashboard.php?action=waiting_queue" class="btn">
         Refresh Queue
    </a>

    <a href="Dashboard.php?action=checkin_patient" class="btn btn-green">
        Check-in Patient
    </a>

    <button onclick="window.print()" class="btn btn-gray">
         Print Queue
    </button>
</div>

<?php if (!empty($groupedQueue)): ?>

    <?php foreach ($groupedQueue as $group): ?>

        <div class="page-card">

            <h2>
                Dr. <?= htmlspecialchars($group['doctor_name']); ?>
            </h2>

            <p>
                <strong>Specialization:</strong>
                <?= htmlspecialchars($group['specialization']); ?>
                |
                <strong>Waiting:</strong>
                <?= htmlspecialchars(count($group['patients'])); ?>
                patient(s)
            </p>

            <table>
                <thead>
                    <tr>
                        <th>Queue No.</th>
                        <th>Appointment Time</th>
                        <th>Patient</th>
                        <th>For</th>
                        <th>Phone</th>
                        <th>Reason</th>
                        <th>Check-in Time</th>
                        <th>Waiting</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $queueNo = 1; ?>

                    <?php foreach ($group['patients'] as $patient): ?>
                        <?php
                            $waitingMinutes = 0;

                            if (!empty($patient['checkin_time'])) {
                                $checkinTimestamp = strtotime($patient['checkin_time']);
                                $currentTimestamp = time();
                                $waitingMinutes = floor(($currentTimestamp - $checkinTimestamp) / 60);

                                if ($waitingMinutes < 0) {
                                    $waitingMinutes = 0;
                                }
                            }
                        ?>

                        <tr>
                            <td>
                                <strong>#<?= htmlspecialchars($queueNo); ?></strong>
                            </td>

                            <td>
                                <?= htmlspecialchars(date('h:i A', strtotime($patient['appointment_time']))); ?>
                            </td>

                            <td>
                                <strong><?= htmlspecialchars($patient['patient_name']); ?></strong>
                                <br>
                                <span class="small">
                                    Patient ID:
                                    <?= htmlspecialchars($patient['patient_id']); ?>
                                </span>
                            </td>

                            <td>
                                <?php if (!empty($patient['dependent_name'])): ?>
                                    <?= htmlspecialchars($patient['dependent_name']); ?>
                                    <br>
                                    <span class="small">
                                        <?= htmlspecialchars($patient['relationship']); ?>
                                    </span>
                                <?php else: ?>
                                    Self
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($patient['patient_mobile']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($patient['reason']); ?>
                            </td>

                            <td>
                                <?php if (!empty($patient['checkin_time'])): ?>
                                    <?= htmlspecialchars(date('h:i A', strtotime($patient['checkin_time']))); ?>
                                    <br>
                                    <span class="small">
                                        <?= htmlspecialchars(date('d M Y', strtotime($patient['checkin_time']))); ?>
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="status pending">
                                    <?= htmlspecialchars($waitingMinutes); ?> min
                                </span>
                            </td>

                            <td>
                                <span class="status checked_in">
                                    Checked In
                                </span>
                            </td>
                        </tr>

                        <?php $queueNo++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="page-card">
        <div class="empty">
            No checked-in patients are waiting right now.
            <br><br>

            <a href="Dashboard.php?action=checkin_patient" class="btn btn-green">
                Check-in Patient
            </a>
        </div>
    </div>

<?php endif; ?>

<div class="page-card">
    <a href="Dashboard.php" class="btn btn-gray">
         Back to Dashboard
    </a>
</div>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>