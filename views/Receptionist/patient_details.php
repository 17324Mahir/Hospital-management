<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Patient Details</h1>
    <p>
        View patient profile, contact information, upcoming appointments, and billing summary.
    </p>
</div>

<div class="page-card">

    <h2><?= htmlspecialchars($patient['name']); ?></h2>

    <p>
        <strong>Patient ID:</strong>
        #<?= htmlspecialchars($patient['patient_id']); ?>
    </p>

    <a 
        href="Dashboard.php?action=patient_search" 
        class="btn btn-gray"
    >
        Back to Search
    </a>

    <a 
        href="Dashboard.php?action=walkin_booking&patient_id=<?= htmlspecialchars($patient['patient_id']); ?>" 
        class="btn btn-orange"
    >
         Book Walk-in Appointment
    </a>

</div>

<div class="grid-3">

    <div class="stat-card">
        <h3>Total Bills</h3>
        <div class="number">
            <?= htmlspecialchars($billingSummary['total_bills'] ?? 0); ?>
        </div>
    </div>

    <div class="stat-card">
        <h3>Total Paid</h3>
        <div class="number">
            <?= htmlspecialchars(number_format((float)($billingSummary['total_paid'] ?? 0), 2)); ?> BDT
        </div>
    </div>

    <div class="stat-card">
        <h3>Total Pending</h3>
        <div class="number">
            <?= htmlspecialchars(number_format((float)($billingSummary['total_pending'] ?? 0), 2)); ?> BDT
        </div>
    </div>

</div>

<div class="grid-2">

    <div class="page-card">
        <h2>Basic Information</h2>

        <p>
            <strong>Name:</strong>
            <?= htmlspecialchars($patient['name']); ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?= htmlspecialchars($patient['email']); ?>
        </p>

        <p>
            <strong>Mobile:</strong>
            <?= htmlspecialchars($patient['mobile']); ?>
        </p>

        <p>
            <strong>Phone:</strong>
            <?= !empty($patient['phone']) ? htmlspecialchars($patient['phone']) : '-'; ?>
        </p>

        <p>
            <strong>Status:</strong>
            <?php if ($patient['is_active'] == 1): ?>
                <span class="status active">Active</span>
            <?php else: ?>
                <span class="status inactive">Inactive</span>
            <?php endif; ?>
        </p>
    </div>

    <div class="page-card">
        <h2>Medical Information</h2>

        <p>
            <strong>Date of Birth:</strong>
            <?= !empty($patient['date_of_birth']) ? htmlspecialchars($patient['date_of_birth']) : '-'; ?>
        </p>

        <p>
            <strong>Gender:</strong>
            <?= !empty($patient['gender']) ? htmlspecialchars($patient['gender']) : '-'; ?>
        </p>

        <p>
            <strong>Blood Group:</strong>
            <?= !empty($patient['blood_group']) ? htmlspecialchars($patient['blood_group']) : '-'; ?>
        </p>

        <p>
            <strong>Address:</strong>
            <?= !empty($patient['address']) ? htmlspecialchars($patient['address']) : '-'; ?>
        </p>
    </div>

</div>

<div class="page-card">
    <h2>Emergency Contact</h2>

    <div class="grid-2">
        <div>
            <p>
                <strong>Contact Name:</strong>
                <?= !empty($patient['emergency_contact_name']) ? htmlspecialchars($patient['emergency_contact_name']) : '-'; ?>
            </p>
        </div>

        <div>
            <p>
                <strong>Contact Phone:</strong>
                <?= !empty($patient['emergency_contact_phone']) ? htmlspecialchars($patient['emergency_contact_phone']) : '-'; ?>
            </p>
        </div>
    </div>
</div>

<div class="page-card">
    <h2>Medical History Notes</h2>

    <?php if (!empty($patient['medical_history_notes'])): ?>
        <p><?= nl2br(htmlspecialchars($patient['medical_history_notes'])); ?></p>
    <?php else: ?>
        <div class="empty">No medical history notes added.</div>
    <?php endif; ?>
</div>

<div class="page-card">

    <h2>Upcoming Appointments</h2>

    <?php if ($upcomingAppointments && $upcomingAppointments->num_rows > 0): ?>

        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Doctor</th>
                    <th>For</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($appointment = $upcomingAppointments->fetch_assoc()): ?>
                    <tr>
                        <td>
                            #<?= htmlspecialchars($appointment['appointment_id']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment['appointment_date']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment['appointment_time']); ?>
                        </td>

                        <td>
                            Dr. <?= htmlspecialchars($appointment['doctor_name']); ?>
                            <br>
                            <span class="small">
                                <?= htmlspecialchars($appointment['specialization']); ?>
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
                            <?= htmlspecialchars($appointment['reason']); ?>
                        </td>

                        <td>
                            <span class="status <?= htmlspecialchars($appointment['status']); ?>">
                                <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $appointment['status']))); ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="empty">
            No upcoming appointment found.
            <br><br>
            <a 
                href="Dashboard.php?action=walkin_booking&patient_id=<?= htmlspecialchars($patient['patient_id']); ?>" 
                class="btn btn-orange"
            >
                Book Appointment
            </a>
        </div>

    <?php endif; ?>

</div>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>