<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Payment Receipt</h1>
    <p>
        Printable payment receipt for completed patient billing.
    </p>
</div>

<div class="page-card">
    <a href="Dashboard.php?action=payments" class="btn btn-gray">
         Back to Payments
    </a>

    <button onclick="window.print()" class="btn btn-orange">
         Print Receipt
    </button>

    <a href="Dashboard.php" class="btn">
        Dashboard
    </a>
</div>

<div class="page-card" style="max-width:850px; margin:auto;">

    <div style="text-align:center; border-bottom:2px solid #eee; padding-bottom:18px; margin-bottom:22px;">
        <h1 style="margin:0; color:#123f42;">CareConnect Hospital</h1>
        <p style="margin:6px 0; color:#667;">Hospital Appointment & Billing System</p>
        <p style="margin:6px 0; color:#667;">Dhaka, Bangladesh</p>
    </div>

    <h2 style="text-align:center; text-transform:uppercase; letter-spacing:1px;">
        Payment Receipt
    </h2>

    <div class="grid-2" style="margin-top:25px;">

        <div class="note">
            <p>
                <strong>Receipt No:</strong>
                #<?= htmlspecialchars($bill['id']); ?>
            </p>

            <p>
                <strong>Billing ID:</strong>
                #<?= htmlspecialchars($bill['id']); ?>
            </p>

            <p>
                <strong>Appointment ID:</strong>
                #<?= htmlspecialchars($bill['appointment_id']); ?>
            </p>

            <p>
                <strong>Payment Status:</strong>
                <span class="status paid">
                    <?= htmlspecialchars(ucfirst($bill['payment_status'])); ?>
                </span>
            </p>
        </div>

        <div class="note">
            <p>
                <strong>Paid At:</strong>
                <?php if (!empty($bill['paid_at'])): ?>
                    <?= htmlspecialchars(date('d M Y, h:i A', strtotime($bill['paid_at']))); ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </p>

            <p>
                <strong>Payment Method:</strong>
                <?= !empty($bill['payment_method']) ? htmlspecialchars(ucfirst($bill['payment_method'])) : '-'; ?>
            </p>

            <p>
                <strong>Printed By:</strong>
                <?= htmlspecialchars($_SESSION['name'] ?? 'Receptionist'); ?>
            </p>

            <p>
                <strong>Printed On:</strong>
                <?= htmlspecialchars(date('d M Y, h:i A')); ?>
            </p>
        </div>

    </div>

    <div class="grid-2" style="margin-top:20px;">

        <div class="page-card" style="box-shadow:none; border:1px solid #eee;">
            <h2>Patient Information</h2>

            <p>
                <strong>Name:</strong>
                <?= htmlspecialchars($bill['patient_name']); ?>
            </p>

            <p>
                <strong>Mobile:</strong>
                <?= htmlspecialchars($bill['patient_mobile']); ?>
            </p>
        </div>

        <div class="page-card" style="box-shadow:none; border:1px solid #eee;">
            <h2>Doctor Information</h2>

            <p>
                <strong>Doctor:</strong>
                Dr. <?= htmlspecialchars($bill['doctor_name']); ?>
            </p>

            <p>
                <strong>Specialization:</strong>
                <?= htmlspecialchars($bill['specialization']); ?>
            </p>
        </div>

    </div>

    <div class="page-card" style="box-shadow:none; border:1px solid #eee; margin-top:20px;">
        <h2>Appointment Details</h2>

        <div class="grid-2">
            <div>
                <p>
                    <strong>Date:</strong>
                    <?= htmlspecialchars($bill['appointment_date']); ?>
                </p>

                <p>
                    <strong>Time:</strong>
                    <?= htmlspecialchars(date('h:i A', strtotime($bill['appointment_time']))); ?>
                </p>
            </div>

            <div>
                <p>
                    <strong>Reason:</strong>
                    <?= htmlspecialchars($bill['reason']); ?>
                </p>
            </div>
        </div>
    </div>

    <div style="text-align:center; background:#f8f9fa; border:2px dashed #e67e22; padding:24px; border-radius:12px; margin-top:25px;">
        <h2 style="margin:0 0 10px; color:#123f42;">Total Paid</h2>

        <div style="font-size:36px; font-weight:bold; color:#27ae60;">
            <?= htmlspecialchars(number_format((float)$bill['amount'], 2)); ?> BDT
        </div>

        <p style="margin-top:10px;">
            <span class="status paid">Paid</span>
        </p>
    </div>

    <div class="grid-2" style="margin-top:45px; text-align:center;">

        <div>
            <div style="border-top:1px solid #333; padding-top:8px;">
                Patient Signature
            </div>
        </div>

        <div>
            <div style="border-top:1px solid #333; padding-top:8px;">
                Receptionist Signature
            </div>
        </div>

    </div>

    <p style="text-align:center; margin-top:35px; color:#667; font-size:13px;">
        Thank you for choosing CareConnect Hospital.
    </p>

</div>

<style>
@media print {
    .topbar,
    .sidebar,
    .page-title,
    .page-card:first-of-type {
        display: none !important;
    }

    .layout {
        display: block !important;
    }

    .content {
        padding: 0 !important;
    }

    body {
        background: white !important;
    }

    .page-card {
        box-shadow: none !important;
    }
}
</style>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>