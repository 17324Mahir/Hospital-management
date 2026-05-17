<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Daily Report</h1>
    <p>
        View appointment summary, billing summary, doctor-wise activity, and detailed appointment list for a selected date.
    </p>
</div>

<div class="page-card">

    <h2>Select Report Date</h2>

    <form method="GET" action="Dashboard.php">
        <input type="hidden" name="action" value="daily_report">

        <div class="grid-2">
            <div>
                <label for="selected_date">Report Date</label>
                <input 
                    type="date" 
                    name="selected_date" 
                    id="selected_date"
                    value="<?= htmlspecialchars($selected_date); ?>"
                    required
                >
            </div>

            <div style="display:flex; align-items:end;">
                <button type="submit" class="btn" style="width:100%;">
                    Generate Report
                </button>
            </div>
        </div>
    </form>

</div>

<div class="page-card">
    <h2>
        Report for <?= htmlspecialchars(date('d M Y', strtotime($selected_date))); ?>
    </h2>

    <p>
        <strong>Day:</strong>
        <?= htmlspecialchars(date('l', strtotime($selected_date))); ?>
    </p>
</div>

<div class="grid-3">

    <div class="stat-card">
        <h3>Total Appointments</h3>
        <div class="number">
            <?= htmlspecialchars($appointmentSummary['total_appointments'] ?? 0); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Pending</h3>
        <div class="number">
            <?= htmlspecialchars($appointmentSummary['pending_count'] ?? 0); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Confirmed</h3>
        <div class="number">
            <?= htmlspecialchars($appointmentSummary['confirmed_count'] ?? 0); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Checked In</h3>
        <div class="number">
            <?= htmlspecialchars($appointmentSummary['checked_in_count'] ?? 0); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Completed</h3>
        <div class="number">
            <?= htmlspecialchars($appointmentSummary['completed_count'] ?? 0); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Cancelled</h3>
        <div class="number">
            <?= htmlspecialchars($appointmentSummary['cancelled_count'] ?? 0); ?>
        </div>
    </div>

</div>

<div class="grid-3">

    <div class="stat-card">
        <h3>Total Bills</h3>
        <div class="number">
            <?= htmlspecialchars($billingSummary['total_bills'] ?? 0); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Paid Bills</h3>
        <div class="number">
            <?= htmlspecialchars($billingSummary['paid_bills'] ?? 0); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Pending Bills</h3>
        <div class="number">
            <?= htmlspecialchars($billingSummary['pending_bills'] ?? 0); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Total Revenue</h3>
        <div class="number">
            <?= htmlspecialchars(number_format((float)($billingSummary['total_revenue'] ?? 0), 2)); ?> BDT
        </div>
    </div>

    <div class="stat-card">
        <h3>Pending Amount</h3>
        <div class="number">
            <?= htmlspecialchars(number_format((float)($billingSummary['pending_amount'] ?? 0), 2)); ?> BDT
        </div>
    </div>

    <div class="stat-card">
        <h3>No Show</h3>
        <div class="number">
            <?= htmlspecialchars($appointmentSummary['no_show_count'] ?? 0); ?>
        </div>
    </div>

</div>

<div class="page-card">

    <h2>Payment Method Summary</h2>

    <?php if ($paymentMethodSummary && $paymentMethodSummary->num_rows > 0): ?>

        <table>
            <thead>
                <tr>
                    <th>Payment Method</th>
                    <th>Total Payments</th>
                    <th>Total Amount</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($payment = $paymentMethodSummary->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?= !empty($payment['payment_method']) 
                                ? htmlspecialchars(ucfirst($payment['payment_method'])) 
                                : 'Not Set'; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($payment['total_payments']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(number_format((float)$payment['total_amount'], 2)); ?> BDT
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="empty">
            No paid payment record found for this date.
        </div>

    <?php endif; ?>

</div>

<div class="page-card">

    <h2>Doctor-wise Summary</h2>

    <?php if ($doctorWiseSummary && $doctorWiseSummary->num_rows > 0): ?>

        <table>
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Total</th>
                    <th>Pending</th>
                    <th>Confirmed</th>
                    <th>Checked In</th>
                    <th>Completed</th>
                    <th>Cancelled</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($doctor = $doctorWiseSummary->fetch_assoc()): ?>
                    <tr>
                        <td>
                            Dr. <?= htmlspecialchars($doctor['doctor_name']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($doctor['specialization']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($doctor['total_appointments']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($doctor['pending_count']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($doctor['confirmed_count']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($doctor['checked_in_count']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($doctor['completed_count']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($doctor['cancelled_count']); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="empty">
            No doctor-wise appointment record found for this date.
        </div>

    <?php endif; ?>

</div>

<div class="page-card">

    <h2>Detailed Appointment List</h2>

    <?php if ($detailedAppointments && $detailedAppointments->num_rows > 0): ?>

        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>For</th>
                    <th>Doctor</th>
                    <th>Reason</th>
                    <th>Booked By</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($appointment = $detailedAppointments->fetch_assoc()): ?>
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
                                <?= htmlspecialchars($appointment['patient_mobile']); ?>
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
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="empty">
            No appointment details found for this date.
        </div>

    <?php endif; ?>

</div>

<div class="page-card">
    <a href="Dashboard.php" class="btn btn-gray">
        ⬅ Back to Dashboard
    </a>

    <button onclick="window.print()" class="btn">
        🖨 Print Report
    </button>
</div>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>