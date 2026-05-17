<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Doctor Availability</h1>
    <p>
        Check doctor schedule, available slots, booked slots, and leave status for a selected date.
    </p>
</div>

<div class="page-card">

    <h2>Select Date</h2>

    <form method="GET" action="Dashboard.php">
        <input type="hidden" name="action" value="doctor_availability">

        <div class="grid-2">
            <div>
                <label for="selected_date">Date</label>
                <input 
                    type="date" 
                    name="selected_date" 
                    id="selected_date"
                    value="<?= htmlspecialchars($selected_date); ?>"
                    min="<?= date('Y-m-d'); ?>"
                    required
                >
            </div>

            <div style="display:flex; align-items:end;">
                <button type="submit" class="btn" style="width:100%;">
                    Check Availability
                </button>
            </div>
        </div>
    </form>

</div>

<?php
$totalDoctors = 0;
$availableDoctors = 0;
$unavailableDoctors = 0;

if (!empty($doctorAvailability)) {
    $totalDoctors = count($doctorAvailability);

    foreach ($doctorAvailability as $doctor) {
        if (
            !empty($doctor['start_time']) &&
            !empty($doctor['end_time']) &&
            $doctor['is_available'] == 1 &&
            empty($doctor['leave_id'])
        ) {
            $availableDoctors++;
        } else {
            $unavailableDoctors++;
        }
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
        <h3>Available Doctors</h3>
        <div class="number">
            <?= htmlspecialchars($availableDoctors); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Unavailable Doctors</h3>
        <div class="number">
            <?= htmlspecialchars($unavailableDoctors); ?>
        </div>
    </div>

</div>

<div class="page-card">

    <h2>
        Availability for 
        <?= htmlspecialchars(date('d M Y', strtotime($selected_date))); ?>
    </h2>

    <p>
        <strong>Day:</strong>
        <?= htmlspecialchars(date('l', strtotime($selected_date))); ?>
    </p>

    <?php if (!empty($doctorAvailability)): ?>

        <table>
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Contact</th>
                    <th>Time</th>
                    <th>Slots</th>
                    <th>Fee</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($doctorAvailability as $doctor): ?>
                    <tr>
                        <td>
                            <strong>
                                Dr. <?= htmlspecialchars($doctor['doctor_name']); ?>
                            </strong>
                            <br>
                            <span class="small">
                                Doctor ID:
                                <?= htmlspecialchars($doctor['doctor_id']); ?>
                            </span>
                        </td>

                        <td>
                            <?= htmlspecialchars($doctor['specialization']); ?>
                            <br>
                            <span class="small">
                                Experience:
                                <?= htmlspecialchars($doctor['experience_years'] ?? 0); ?> years
                            </span>
                        </td>

                        <td>
                            <?= !empty($doctor['doctor_mobile']) ? htmlspecialchars($doctor['doctor_mobile']) : '-'; ?>
                        </td>

                        <td>
                            <?php if (!empty($doctor['start_time']) && !empty($doctor['end_time'])): ?>
                                <?= htmlspecialchars(date('h:i A', strtotime($doctor['start_time']))); ?>
                                -
                                <?= htmlspecialchars(date('h:i A', strtotime($doctor['end_time']))); ?>
                                <br>
                                <span class="small">
                                    Slot:
                                    <?= htmlspecialchars($doctor['slot_duration_minutes']); ?> min
                                </span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>

                        <td>
                            <strong>Total:</strong>
                            <?= htmlspecialchars($doctor['total_slots']); ?>
                            <br>

                            <strong>Booked:</strong>
                            <?= htmlspecialchars($doctor['booked_count']); ?>
                            <br>

                            <strong>Available:</strong>
                            <?= htmlspecialchars($doctor['available_slots']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(number_format((float)$doctor['consultation_fee'], 2)); ?> BDT
                        </td>

                        <td>
                            <?php if (!empty($doctor['leave_id'])): ?>

                                <span class="status pending">
                                    On Leave
                                </span>
                                <br>
                                <span class="small">
                                    <?= htmlspecialchars($doctor['leave_reason']); ?>
                                </span>

                            <?php elseif (
                                !empty($doctor['start_time']) &&
                                !empty($doctor['end_time']) &&
                                $doctor['is_available'] == 1
                            ): ?>

                                <?php if ($doctor['available_slots'] > 0): ?>
                                    <span class="status active">
                                        Available
                                    </span>
                                <?php else: ?>
                                    <span class="status inactive">
                                        Fully Booked
                                    </span>
                                <?php endif; ?>

                            <?php else: ?>

                                <span class="status inactive">
                                    Not Available
                                </span>

                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (
                                empty($doctor['leave_id']) &&
                                !empty($doctor['start_time']) &&
                                !empty($doctor['end_time']) &&
                                $doctor['is_available'] == 1 &&
                                $doctor['available_slots'] > 0
                            ): ?>

                                <a 
                                    href="Dashboard.php?action=walkin_booking"
                                    class="btn btn-orange"
                                >
                                    Book
                                </a>

                            <?php else: ?>

                                <span class="small">
                                    Booking not available
                                </span>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="empty">
            No doctor availability data found for this date.
        </div>

    <?php endif; ?>

</div>

<div class="page-card">
    <a href="Dashboard.php" class="btn btn-gray">
        ⬅ Back to Dashboard
    </a>

    <a href="Dashboard.php?action=walkin_booking" class="btn btn-orange">
        Walk-in Booking
    </a>

    <button onclick="window.print()" class="btn">
        🖨 Print Availability
    </button>
</div>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>