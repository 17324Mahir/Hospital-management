<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Doctor - Admin Portal</title>
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
            border-left: 5px solid #3498db;
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
            background: #3498db;
            margin-top: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .full {
            grid-column: 1 / -1;
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

        .readonly {
            background: #f1f2f6;
            color: #636e72;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 14px;
            border-radius: 8px;
            line-height: 1.7;
            margin-bottom: 18px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: auto;
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