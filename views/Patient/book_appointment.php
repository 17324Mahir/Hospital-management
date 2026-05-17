<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment - Patient Portal</title>
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

        .doctor-card {
            border-left: 5px solid #3498db;
        }

        h1, h2, h3 {
            color: #2c3e50;
        }

        .info {
            margin: 8px 0;
            color: #555;
        }

        .fee {
            color: #27ae60;
            font-weight: bold;
        }

        label {
            display: block;
            margin-top: 15px;
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
            min-height: 100px;
            resize: vertical;
        }

        .btn {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 11px 18px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 18px;
            cursor: pointer;
            font-size: 15px;
        }

        .btn:hover {
            background: #2980b9;
        }

        .back-btn {
            background: #7f8c8d;
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
<script>
document.addEventListener("DOMContentLoaded", function () {
    const appointmentDate = document.getElementById("appointment_date");
    const appointmentTime = document.getElementById("appointment_time");
    const doctorIdInput = document.getElementById("doctor_id");
    const slotMessage = document.getElementById("slotMessage");
    const bookingFor = document.getElementById("booking_for");
    const dependentBox = document.getElementById("dependentBox");
    const dependentId = document.getElementById("dependent_id");

if (bookingFor && dependentBox && dependentId) {
    bookingFor.addEventListener("change", function () {
        if (this.value === "dependent") {
            dependentBox.style.display = "block";
            dependentId.setAttribute("required", "required");
        } else {
            dependentBox.style.display = "none";
            dependentId.removeAttribute("required");
            dependentId.value = "";
        }
    });
}

    if (!appointmentDate || !appointmentTime || !doctorIdInput || !slotMessage) {
        console.error("Required booking elements are missing.");
        return;
    }

    const doctorId = doctorIdInput.value;

    appointmentDate.addEventListener("change", function () {
        const selectedDate = this.value;

        appointmentTime.innerHTML = '<option value="">Loading slots...</option>';
        slotMessage.textContent = "";

        if (!selectedDate) {
            appointmentTime.innerHTML = '<option value="">Select date first</option>';
            return;
        }

        fetch(`./api/get_available_slots.php?doctor_id=${doctorId}&appointment_date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                appointmentTime.innerHTML = "";

                if (!data.success) {
                    appointmentTime.innerHTML = '<option value="">No slots available</option>';
                    slotMessage.style.color = "#c0392b";
                    slotMessage.textContent = data.message;
                    return;
                }

                if (data.slots.length === 0) {
                    appointmentTime.innerHTML = '<option value="">No slots available</option>';
                    slotMessage.style.color = "#c0392b";
                    slotMessage.textContent = data.message;
                    return;
                }

                appointmentTime.innerHTML = '<option value="">Select time</option>';

                data.slots.forEach(slot => {
                    const option = document.createElement("option");
                    option.value = slot.value;
                    option.textContent = slot.label;
                    appointmentTime.appendChild(option);
                });

                slotMessage.style.color = "#27ae60";
                slotMessage.textContent = data.message;
            })
            .catch(error => {
                appointmentTime.innerHTML = '<option value="">Error loading slots</option>';
                slotMessage.style.color = "#c0392b";
                slotMessage.textContent = "AJAX error. Check API path or PHP error.";
                console.error("AJAX Error:", error);
            });
    });
});
</script>
<body>

<div class="navbar">
    <div class="logo">🏥 HospitalCare | Book Appointment</div>

    <div>
        <span>Hello, <?= htmlspecialchars($_SESSION['name']); ?></span> |
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <a href="dashboard.php?action=doctors" class="btn back-btn">⬅ Back to Doctors</a>

    <div class="card doctor-card">
        <h2>Dr. <?= htmlspecialchars($doctor['doctor_name']); ?></h2>

        <p class="info">
            <strong>Specialization:</strong>
            <?= htmlspecialchars($doctor['specialization']); ?>
        </p>

        <p class="info">
            <strong>Experience:</strong>
            <?= htmlspecialchars($doctor['experience_years'] ?? '0'); ?> years
        </p>

        <p class="info fee">
            Consultation Fee: <?= htmlspecialchars($doctor['consultation_fee']); ?> BDT
        </p>

        <p class="info">
            <?= htmlspecialchars($doctor['bio'] ?? ''); ?>
        </p>
    </div>

    <div class="card">
        <h2>Appointment Form</h2>

        <?php if (!empty($message)): ?>
            <div class="success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

       <form method="POST" action="">
    <input type="hidden" id="doctor_id" value="<?= htmlspecialchars($doctor['doctor_id']); ?>">

    <label for="booking_for">Booking For</label>
    <select name="booking_for" id="booking_for" required>
        <option value="self">Self</option>
        <option value="dependent">Family Dependent</option>
    </select>

    <div id="dependentBox" style="display:none;">
        <label for="dependent_id">Select Dependent</label>
        <select name="dependent_id" id="dependent_id">
            <option value="">Choose dependent</option>

            <?php if ($dependents && $dependents->num_rows > 0): ?>
                <?php while ($dependent = $dependents->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($dependent['id']); ?>">
                        <?= htmlspecialchars($dependent['name']); ?>
                        -
                        <?= htmlspecialchars($dependent['relationship']); ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>
    </div>
            <label for="appointment_date">Appointment Date</label>
            <input 
                type="date" 
                name="appointment_date" 
                id="appointment_date" 
                min="<?= date('Y-m-d'); ?>"
                required
            >

            <label for="appointment_time">Appointment Time</label>
<select name="appointment_time" id="appointment_time" required>
    <option value="">Select date first</option>
</select>

<p id="slotMessage" style="font-size: 14px; color: #7f8c8d; margin-top: 8px;"></p>

            <label for="reason">Reason for Visit</label>
            <textarea 
                name="reason" 
                id="reason" 
                placeholder="Write your reason for appointment..."
                required
            ></textarea>

            <button type="submit" class="btn">Confirm Booking</button>
        </form>
    </div>

</div>

</body>
</html>