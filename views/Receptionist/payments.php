<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Pending Payments</h1>
    <p>
        View all pending bills, search payment records, and process patient payments from the reception desk.
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

    <h2>Search Payment</h2>

    <div class="grid-2">
        <div>
            <label for="paymentSearch">Search</label>
            <input 
                type="text" 
                id="paymentSearch"
                placeholder="Search by patient, doctor, billing ID, appointment ID..."
            >
        </div>

        <div style="display:flex; align-items:end;">
            <a href="Dashboard.php?action=daily_report" class="btn" style="width:100%; text-align:center;">
                View Daily Report
            </a>
        </div>
    </div>

</div>

<div class="page-card">

    <h2>Pending Billing List</h2>

    <?php if ($payments && $payments->num_rows > 0): ?>

        <table id="paymentTable">
            <thead>
                <tr>
                    <th>Billing ID</th>
                    <th>Appointment</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($payment = $payments->fetch_assoc()): ?>
                    <tr>
                        <td>
                            #<?= htmlspecialchars($payment['billing_id']); ?>
                        </td>

                        <td>
                            <strong>ID:</strong>
                            #<?= htmlspecialchars($payment['appointment_id']); ?>
                            <br>

                            <span class="small">
                                <?= htmlspecialchars($payment['appointment_date']); ?>
                                |
                                <?= htmlspecialchars(date('h:i A', strtotime($payment['appointment_time']))); ?>
                            </span>
                            <br>

                            <span class="small">
                                <?= htmlspecialchars($payment['reason']); ?>
                            </span>
                        </td>

                        <td>
                            <strong><?= htmlspecialchars($payment['patient_name']); ?></strong>
                            <br>

                            <span class="small">
                                Patient ID:
                                <?= htmlspecialchars($payment['patient_id']); ?>
                            </span>
                            <br>

                            <span class="small">
                                Phone:
                                <?= htmlspecialchars($payment['patient_mobile']); ?>
                            </span>

                            <?php if (!empty($payment['dependent_name'])): ?>
                                <br>
                                <span class="small">
                                    For:
                                    <?= htmlspecialchars($payment['dependent_name']); ?>
                                    |
                                    <?= htmlspecialchars($payment['relationship']); ?>
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            Dr. <?= htmlspecialchars($payment['doctor_name']); ?>
                            <br>
                            <span class="small">
                                <?= htmlspecialchars($payment['specialization']); ?>
                            </span>
                        </td>

                        <td>
                            <strong>
                                <?= htmlspecialchars(number_format((float)$payment['amount'], 2)); ?> BDT
                            </strong>
                        </td>

                        <td>
                            <span class="status pending">
                                Pending
                            </span>
                        </td>

                        <td>
                            <a 
                                href="Dashboard.php?action=process_payment&id=<?= htmlspecialchars($payment['billing_id']); ?>"
                                class="btn btn-orange"
                            >
                                Process Payment
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="empty">
            No pending payment found.
            <br><br>

            <a href="Dashboard.php?action=daily_schedule" class="btn">
                View Daily Schedule
            </a>
        </div>

    <?php endif; ?>

</div>

<div class="page-card">
    <a href="Dashboard.php" class="btn btn-gray">
        ⬅ Back to Dashboard
    </a>

    <button onclick="window.print()" class="btn">
        🖨 Print Pending Payments
    </button>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("paymentSearch");
    const table = document.getElementById("paymentTable");

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