<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reschedule Appointment - Patient Portal</title>
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
            max-width: 850px;
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
            border-left: 5px solid #e67e22;
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
            background: #e67e22;
            margin-top: 15px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 12px;
        }

        .info-box p {
            margin: 8px 0;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
            font-weight: bold;
            color: #2c3e50;
        }

        input, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
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
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Reschedule Appointment</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="Dashboard.php" class="btn">⬅ Back to Dashboard</a>

    <div class="card top-section">
        <h1>Reschedule Appointment</h1>
        <p>Select a new available date and time for your appointment.</p>
    </div>

    <div class="card">
        <h2>Current Appointment</h2>

        <div class="info-box">
            <p>
                <strong>Doctor:</strong>
                Dr. <?= htmlspecialchars($appointment['doctor_name']); ?>
            </p>

            <p>
                <strong>Specialization:</strong>
                <?= htmlspecialchars($appointment['specialization']); ?>
            </p>

            <p>
                <strong>Current Date:</strong>
                <?= htmlspecialchars($appointment['appointment_date']); ?>
            </p>

            <p>
                <strong>Current Time:</strong>
                <?= htmlspecialchars($appointment['appointment_time']); ?>
            </p>

            <p>
                <strong>For:</strong>
                <?php if (!empty($appointment['dependent_name'])): ?>
                    <?= htmlspecialchars($appointment['dependent_name']); ?>
                    -
                    <?= htmlspecialchars($appointment['relationship']); ?>
                <?php else: ?>
                    Self
                <?php endif; ?>
            </p>

            <p>
                <strong>Reason:</strong>
                <?= htmlspecialchars($appointment['reason']); ?>
            </p>

            <p>
                <strong>Status:</strong>
                <?= htmlspecialchars(ucfirst($appointment['status'])); ?>
            </p>
        </div>
    </div>

    <div class="card">
        <h2>Choose New Slot</h2>

        <?php if (!empty($message)): ?>
            <div class="success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" id="doctor_id" value="<?= htmlspecialchars($appointment['doctor_id']); ?>">

            <label for="new_date">New Appointment Date</label>
            <input 
                type="date" 
                name="new_date" 
                id="new_date" 
                min="<?= date('Y-m-d'); ?>"
                required
            >

            <label for="new_time">New Appointment Time</label>
            <select name="new_time" id="new_time" required>
                <option value="">Select date first</option>
            </select>

            <p id="slotMessage" style="font-size:14px; color:#7f8c8d; margin-top:8px;"></p>

            <button type="submit" class="btn submit-btn">Confirm Reschedule</button>
        </form>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const newDate = document.getElementById("new_date");
    const newTime = document.getElementById("new_time");
    const doctorIdInput = document.getElementById("doctor_id");
    const slotMessage = document.getElementById("slotMessage");

    if (!newDate || !newTime || !doctorIdInput || !slotMessage) {
        console.error("Required reschedule elements are missing.");
        return;
    }

    const doctorId = doctorIdInput.value;

    newDate.addEventListener("change", function () {
        const selectedDate = this.value;

        newTime.innerHTML = '<option value="">Loading slots...</option>';
        slotMessage.textContent = "";

        if (!selectedDate) {
            newTime.innerHTML = '<option value="">Select date first</option>';
            return;
        }

        fetch(`./api/get_available_slots.php?doctor_id=${doctorId}&appointment_date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                newTime.innerHTML = "";

                if (!data.success) {
                    newTime.innerHTML = '<option value="">No slots available</option>';
                    slotMessage.style.color = "#c0392b";
                    slotMessage.textContent = data.message;
                    return;
                }

                if (data.slots.length === 0) {
                    newTime.innerHTML = '<option value="">No slots available</option>';
                    slotMessage.style.color = "#c0392b";
                    slotMessage.textContent = data.message;
                    return;
                }

                newTime.innerHTML = '<option value="">Select time</option>';

                data.slots.forEach(slot => {
                    const option = document.createElement("option");
                    option.value = slot.value;
                    option.textContent = slot.label;
                    newTime.appendChild(option);
                });

                slotMessage.style.color = "#27ae60";
                slotMessage.textContent = data.message;
            })
            .catch(error => {
                newTime.innerHTML = '<option value="">Error loading slots</option>';
                slotMessage.style.color = "#c0392b";
                slotMessage.textContent = "AJAX error. Check API path or PHP error.";
                console.error("AJAX Error:", error);
            });
    });
});
</script>

</body>
</html>