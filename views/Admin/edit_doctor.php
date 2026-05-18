<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Doctor - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Admineditdoctor.css">

</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Edit Doctor</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php?action=manage_doctors" class="btn">⬅ Back to Manage Doctors</a>

    <div class="card top-section">
        <h1>Edit Doctor</h1>
        <p>Update doctor profile and professional information.</p>
    </div>

    <div class="card">

        <div class="info-box">
            <strong>Doctor ID:</strong> #<?= htmlspecialchars($doctor['doctor_id']); ?><br>
            <strong>Email:</strong> <?= htmlspecialchars($doctor['email']); ?><br>
            <strong>Current Status:</strong> <?= htmlspecialchars(ucfirst($doctor['status'])); ?><br>
            <strong>Approved:</strong> <?= $doctor['is_approved'] == 1 ? 'Yes' : 'No'; ?>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">

            <div class="form-grid">

                <div>
                    <label for="name">Doctor Name *</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="<?= htmlspecialchars($doctor['name']); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email"
                        value="<?= htmlspecialchars($doctor['email']); ?>"
                        class="readonly"
                        readonly
                    >
                </div>

                <div>
                    <label for="mobile">Mobile *</label>
                    <input 
                        type="text" 
                        name="mobile" 
                        id="mobile"
                        value="<?= htmlspecialchars($doctor['mobile']); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="specialization">Specialization *</label>
                    <input 
                        type="text" 
                        name="specialization" 
                        id="specialization"
                        value="<?= htmlspecialchars($doctor['specialization'] ?? ''); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="consultation_fee">Consultation Fee *</label>
                    <input 
                        type="number" 
                        step="0.01"
                        min="0"
                        name="consultation_fee" 
                        id="consultation_fee"
                        value="<?= htmlspecialchars($doctor['consultation_fee']); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="experience_years">Experience Years *</label>
                    <input 
                        type="number" 
                        min="0"
                        name="experience_years" 
                        id="experience_years"
                        value="<?= htmlspecialchars($doctor['experience_years']); ?>"
                        required
                    >
                </div>

                <div class="full">
                    <label for="license_number">License Number *</label>
                    <input 
                        type="text" 
                        name="license_number" 
                        id="license_number"
                        value="<?= htmlspecialchars($doctor['license_number']); ?>"
                        required
                    >
                </div>

                <div class="full">
                    <label for="bio">Bio</label>
                    <textarea 
                        name="bio" 
                        id="bio"
                    ><?= htmlspecialchars($doctor['bio'] ?? ''); ?></textarea>
                </div>

            </div>

            <button type="submit" class="btn submit-btn">Update Doctor</button>

        </form>
    </div>

</div>

</body>
</html>