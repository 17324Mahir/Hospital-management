<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Receptionist Dashboard</h1>
    <p>
        Welcome back, <?= htmlspecialchars($receptionist['name']); ?>.
        Manage front desk work, patient check-in, appointments, billing, and reports from one place.
    </p>
</div>

<div class="grid-3">

    <div class="stat-card">
        <h3>Today's Appointments</h3>
        <div class="number"><?= htmlspecialchars($todayTotal); ?></div>
    </div>

    <div class="stat-card">
        <h3>Checked In</h3>
        <div class="number"><?= htmlspecialchars($todayCheckedIn); ?></div>
    </div>

    <div class="stat-card">
        <h3>Completed</h3>
        <div class="number"><?= htmlspecialchars($todayCompleted); ?></div>
    </div>

    <div class="stat-card">
        <h3>Cancelled</h3>
        <div class="number"><?= htmlspecialchars($todayCancelled); ?></div>
    </div>

    <div class="stat-card">
        <h3>Today's Revenue</h3>
        <div class="number"><?= htmlspecialchars(number_format((float)$todayRevenue, 2)); ?> BDT</div>
    </div>

    <div class="stat-card">
        <h3>Pending Bills</h3>
        <div class="number"><?= htmlspecialchars($pendingBills); ?></div>
    </div>

</div>

<div class="page-card">
    <h2>Front Desk Work Flow</h2>

    <div class="grid-2">

        <div class="note">
            <strong>Step 1:</strong> Check today's schedule and doctor availability.
        </div>

        <div class="note">
            <strong>Step 2:</strong> Search or register patient.
        </div>

        <div class="note">
            <strong>Step 3:</strong> Book walk-in appointment if needed.
        </div>

        <div class="note">
            <strong>Step 4:</strong> Check-in arrived patients.
        </div>

        <div class="note">
            <strong>Step 5:</strong> Collect payments and print receipt.
        </div>

        <div class="note">
            <strong>Step 6:</strong> Generate daily report.
        </div>

    </div>
</div>

<div class="page-card">
    <h2>Receptionist Information</h2>

    <p><strong>Name:</strong> <?= htmlspecialchars($receptionist['name']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($receptionist['email']); ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($receptionist['mobile']); ?></p>
    <p><strong>Role:</strong> Receptionist</p>
</div>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>