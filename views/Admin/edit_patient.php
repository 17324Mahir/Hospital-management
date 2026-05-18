<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Patient - Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/Admineditpatient.css">


    
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Edit Patient</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php?action=manage_patients" class="btn">⬅ Back to Manage Patients</a>

    <div class="card top-section">
        <h1>Edit Patient</h1>
        <p>Update patient account and medical profile information.</p>
    </div>

    <div class="card">

        <div class="info-box">
            <strong>Patient ID:</strong> #<?= htmlspecialchars($patient['patient_id']); ?><br>
            <strong>Email:</strong> <?= htmlspecialchars($patient['email']); ?><br>
            <strong>Account Status:</strong> <?= $patient['is_active'] == 1 ? 'Active' : 'Inactive'; ?>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">

            <div class="form-grid">

                <div>
                    <label for="name">Patient Name *</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="<?= htmlspecialchars($patient['name']); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email"
                        value="<?= htmlspecialchars($patient['email']); ?>"
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
                        value="<?= htmlspecialchars($patient['mobile']); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="date_of_birth">Date of Birth *</label>
                    <input 
                        type="date" 
                        name="date_of_birth" 
                        id="date_of_birth"
                        max="<?= date('Y-m-d'); ?>"
                        value="<?= htmlspecialchars($patient['date_of_birth']); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="gender">Gender *</label>
                    <select name="gender" id="gender" required>
                        <option value="">Select gender</option>
                        <option value="Male" <?= $patient['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?= $patient['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?= $patient['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div>
                    <label for="blood_group">Blood Group *</label>
                    <select name="blood_group" id="blood_group" required>
                        <?php
                            $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                        ?>
                        <option value="">Select blood group</option>

                        <?php foreach ($bloodGroups as $bg): ?>
                            <option value="<?= $bg; ?>" <?= $patient['blood_group'] === $bg ? 'selected' : ''; ?>>
                                <?= $bg; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="emergency_contact_phone">Emergency Contact Phone *</label>
                    <input 
                        type="text" 
                        name="emergency_contact_phone" 
                        id="emergency_contact_phone"
                        value="<?= htmlspecialchars($patient['emergency_contact_phone'] ?? $patient['emergency_contact'] ?? ''); ?>"
                        required
                    >
                </div>

                <div>
                    <label for="emergency_contact_name">Emergency Contact Name</label>
                    <input 
                        type="text" 
                        name="emergency_contact_name" 
                        id="emergency_contact_name"
                        value="<?= htmlspecialchars($patient['emergency_contact_name'] ?? ''); ?>"
                    >
                </div>

                <div class="full">
                    <label for="address">Address *</label>
                    <textarea 
                        name="address" 
                        id="address"
                        required
                    ><?= htmlspecialchars($patient['address'] ?? ''); ?></textarea>
                </div>

                <div class="full">
                    <label for="medical_history_notes">Medical History Notes</label>
                    <textarea 
                        name="medical_history_notes" 
                        id="medical_history_notes"
                    ><?= htmlspecialchars($patient['medical_history_notes'] ?? ''); ?></textarea>
                </div>

            </div>

            <button type="submit" class="btn submit-btn">Update Patient</button>

        </form>
    </div>

</div>

</body>
</html>