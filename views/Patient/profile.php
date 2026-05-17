<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Patient Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f6;
            margin: 0;
        }

        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: #ff7675;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            max-width: 950px;
            margin: auto;
            padding: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .top-section {
            border-left: 5px solid #8e44ad;
        }

        .btn {
            display: inline-block;
            background: #7f8c8d;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .submit-btn {
            background: #8e44ad;
            margin-top: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #2c3e50;
        }

        input, select, textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .full {
            grid-column: 1 / -1;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .readonly {
            background: #f1f2f6;
            color: #636e72;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | My Profile</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>My Profile</h1>
        <p>Update your personal information, emergency contact, and medical history.</p>
    </div>

    <div class="card">

        <?php if (!empty($message)): ?>
            <div class="success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">

            <div class="form-grid">

                <div>
                    <label for="name">Full Name *</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="<?= htmlspecialchars($profile['name']); ?>" 
                        required
                    >
                </div>

                <div>
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        value="<?= htmlspecialchars($profile['email']); ?>" 
                        class="readonly"
                        readonly
                    >
                </div>

                <div>
                    <label for="mobile">Phone / Mobile *</label>
                    <input 
                        type="text" 
                        name="mobile" 
                        id="mobile" 
                        value="<?= htmlspecialchars($profile['mobile']); ?>" 
                        required
                    >
                </div>

                <div>
                    <label for="date_of_birth">Date of Birth *</label>
                    <input 
                        type="date" 
                        name="date_of_birth" 
                        id="date_of_birth" 
                        value="<?= htmlspecialchars($profile['date_of_birth'] ?? $profile['dob']); ?>" 
                        required
                    >
                </div>

                <div>
                    <label for="gender">Gender *</label>
                    <select name="gender" id="gender" required>
                        <option value="">Select gender</option>
                        <option value="Male" <?= ($profile['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?= ($profile['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?= ($profile['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div>
                    <label for="blood_group">Blood Group *</label>
                    <select name="blood_group" id="blood_group" required>
                        <option value="">Select blood group</option>

                        <?php
                            $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                        ?>

                        <?php foreach ($bloodGroups as $group): ?>
                            <option value="<?= $group; ?>" <?= ($profile['blood_group'] === $group) ? 'selected' : ''; ?>>
                                <?= $group; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="full">
                    <label for="address">Address *</label>
                    <textarea name="address" id="address" required><?= htmlspecialchars($profile['address']); ?></textarea>
                </div>

                <div>
                    <label for="emergency_contact_name">Emergency Contact Name</label>
                    <input 
                        type="text" 
                        name="emergency_contact_name" 
                        id="emergency_contact_name" 
                        value="<?= htmlspecialchars($profile['emergency_contact_name'] ?? ''); ?>"
                    >
                </div>

                <div>
                    <label for="emergency_contact_phone">Emergency Contact Phone *</label>
                    <input 
                        type="text" 
                        name="emergency_contact_phone" 
                        id="emergency_contact_phone" 
                        value="<?= htmlspecialchars($profile['emergency_contact_phone'] ?? $profile['emergency_contact']); ?>" 
                        required
                    >
                </div>

                <div class="full">
                    <label for="medical_history_notes">Medical History Notes</label>
                    <textarea 
                        name="medical_history_notes" 
                        id="medical_history_notes" 
                        placeholder="Write allergies, previous diseases, regular medicines, or important health notes..."
                    ><?= htmlspecialchars($profile['medical_history_notes'] ?? ''); ?></textarea>
                </div>

            </div>

            <button type="submit" class="btn submit-btn">Update Profile</button>

        </form>
    </div>

</div>

</body>
</html>