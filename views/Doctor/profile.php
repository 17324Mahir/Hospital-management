<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professional Profile - Doctor Portal</title>
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

        input, textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .full {
            grid-column: 1 / -1;
        }

        .readonly {
            background: #f1f2f6;
            color: #636e72;
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

        .status-box {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 18px;
            color: #2c3e50;
        }

        .approved {
            color: #27ae60;
            font-weight: bold;
        }

        .pending {
            color: #e67e22;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .navbar {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Professional Profile</div>

    <div>
        <span>Hello, Dr. <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Professional Profile</h1>
        <p>Update your doctor information, specialization, consultation fee, and experience.</p>
    </div>

    <div class="card">

        <?php if (!empty($message)): ?>
            <div class="success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="status-box">
            <strong>Account Status:</strong>

            <?php if ($doctor['is_approved'] == 1 && $doctor['status'] === 'approved'): ?>
                <span class="approved">Approved</span>
            <?php else: ?>
                <span class="pending"><?= htmlspecialchars(ucfirst($doctor['status'])); ?></span>
            <?php endif; ?>
        </div>

        <form method="POST" action="">

            <div class="form-grid">

                <div>
                    <label for="name">Full Name *</label>
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
                    <label for="mobile">Phone / Mobile *</label>
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
                        placeholder="Example: General Physician"
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
                    <label for="bio">Professional Bio</label>
                    <textarea 
                        name="bio" 
                        id="bio" 
                        placeholder="Write your professional summary, experience, treatment areas..."
                    ><?= htmlspecialchars($doctor['bio'] ?? ''); ?></textarea>
                </div>

            </div>

            <button type="submit" class="btn submit-btn">Update Profile</button>

        </form>
    </div>

</div>

</body>
</html>