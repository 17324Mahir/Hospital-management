<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Process Payment</h1>
    <p>
        Confirm the payment method and complete the pending bill.
    </p>
</div>

<?php if (!empty($error)): ?>
    <div class="error">
        <?= htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="page-card">

    <h2>Billing Information</h2>

    <div class="grid-2">

        <div>
            <p>
                <strong>Billing ID:</strong>
                #<?= htmlspecialchars($bill['id']); ?>
            </p>

            <p>
                <strong>Appointment ID:</strong>
                #<?= htmlspecialchars($bill['appointment_id']); ?>
            </p>

            <p>
                <strong>Patient:</strong>
                <?= htmlspecialchars($bill['patient_name']); ?>
            </p>

            <p>
                <strong>Phone:</strong>
                <?= htmlspecialchars($bill['patient_mobile']); ?>
            </p>
        </div>

        <div>
            <p>
                <strong>Doctor:</strong>
                Dr. <?= htmlspecialchars($bill['doctor_name']); ?>
            </p>

            <p>
                <strong>Specialization:</strong>
                <?= htmlspecialchars($bill['specialization']); ?>
            </p>

            <p>
                <strong>Appointment Date:</strong>
                <?= htmlspecialchars($bill['appointment_date']); ?>
            </p>

            <p>
                <strong>Appointment Time:</strong>
                <?= htmlspecialchars(date('h:i A', strtotime($bill['appointment_time']))); ?>
            </p>
        </div>

    </div>

</div>

<div class="page-card">

    <h2>Payment Amount</h2>

    <div class="stat-card">
        <h3>Total Payable</h3>
        <div class="number">
            <?= htmlspecialchars(number_format((float)$bill['amount'], 2)); ?> BDT
        </div>
    </div>

</div>

<div class="page-card">

    <h2>Confirm Payment</h2>

    <form method="POST" action="">

        <label for="payment_method">Payment Method *</label>
        <select name="payment_method" id="payment_method" required>
            <option value="">Select payment method</option>
            <option value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="bkash">bKash</option>
            <option value="nagad">Nagad</option>
            <option value="rocket">Rocket</option>
        </select>

        <div style="margin-top:22px;">
            <button 
                type="submit" 
                class="btn btn-orange"
                onclick="return confirm('Are you sure you want to mark this bill as paid?');"
            >
                 Confirm Payment
            </button>

            <a href="Dashboard.php?action=payments" class="btn btn-gray">
                Cancel
            </a>
        </div>

    </form>

</div>

<div class="page-card">
    <h2>Payment Note</h2>

    <div class="note">
        After confirming payment, the system will mark this bill as paid and automatically open the printable receipt page.
    </div>
</div>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>