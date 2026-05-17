<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Walk-in Appointment Booking</h1>
    <p>
        Book appointments for patients who come directly to the hospital reception.
    </p>
</div>

<div class="page-card">

    <div class="note">
        First search the patient. If the patient is not registered, register the patient before booking.
        Then select doctor, date, and available time slot.
    </div>

    <a href="Dashboard.php?action=patient_search" class="btn">
       Search Patient
    </a>

    <a href="Dashboard.php?action=register_patient" class="btn btn-purple">
         Register Patient
    </a>

</div>

<?php if (!empty($message)): ?>
    <div class="success">
        <?= htmlspecialchars($message); ?>
        <br><br>

        <a href="Dashboard.php?action=daily_schedule" class="btn">
            View Daily Schedule
        </a>

        <a href="Dashboard.php?action=checkin_patient" class="btn btn-green">
            Check-in Patient
        </a>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="error">
        <?= htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<?php if ($selectedPatient): ?>
    <div class="page-card">
        <h2>Selected Patient</h2>

        <div class="grid-2">
            <div>
                <p>
                    <strong>Name:</strong>
                    <?= htmlspecialchars($selectedPatient['name']); ?>
                </p>

                <p>
                    <strong>Patient ID:</strong>
                    #<?= htmlspecialchars($selectedPatient['patient_id']); ?>
                </p>
            </div>

            <div>
                <p>
                    <strong>Phone:</strong>
                    <?= htmlspecialchars($selectedPatient['mobile']); ?>
                </p>

                <p>
                    <strong>Blood Group:</strong>
                    <?= !empty($selectedPatient['blood_group']) ? htmlspecialchars($selectedPatient['blood_group']) : '-'; ?>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="page-card">

    <h2>Appointment Information</h2>

    <form method="POST" action="">

        <div class="grid-2">

            <div>
                <label for="patient_id">Patient ID *</label>
                <input 
                    type="number" 
                    name="patient_id" 
                    id="patient_id"
                    value="<?= htmlspecialchars($selected_patient_id); ?>"
                    placeholder="Enter patient ID"
                    required
                >
            </div>

            <div>
                <label for="doctor_id">Doctor *</label>
                <select name="doctor_id" id="doctor_id" required>
                    <option value="">Select doctor</option>

                    <?php if ($doctors && $doctors->num_rows > 0): ?>
                        <?php while ($doctor = $doctors->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($doctor['doctor_id']); ?>">
                                Dr. <?= htmlspecialchars($doctor['doctor_name']); ?>
                                |
                                <?= htmlspecialchars($doctor['specialization']); ?>
                                |
                                Fee: <?= htmlspecialchars($doctor['consultation_fee']); ?> BDT
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label for="appointment_date">Appointment Date *</label>
                <input 
                    type="date" 
                    name="appointment_date" 
                    id="appointment_date"
                    min="<?= date('Y-m-d'); ?>"
                    required
                >
            </div>

            <div>
                <label for="appointment_time">Available Time Slot *</label>
                <select name="appointment_time" id="appointment_time" required>
                    <option value="">Select doctor and date first</option>
                </select>

                <p id="slotMessage" class="small" style="margin-top:8px;"></p>
            </div>

        </div>

        <div style="margin-top:18px;">
            <label for="reason">Reason for Visit *</label>
            <textarea 
                name="reason" 
                id="reason"
                placeholder="Write the reason for appointment"
                required
            ></textarea>
        </div>

        <div style="margin-top:22px;">
            <button type="submit" class="btn btn-orange">
                 Book Walk-in Appointment
            </button>

            <a href="Dashboard.php" class="btn btn-gray">
                Cancel
            </a>
        </div>

    </form>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const doctorSelect = document.getElementById("doctor_id");
    const dateInput = document.getElementById("appointment_date");
    const timeSelect = document.getElementById("appointment_time");
    const slotMessage = document.getElementById("slotMessage");

    function loadSlots() {
        const doctorId = doctorSelect.value;
        const appointmentDate = dateInput.value;

        timeSelect.innerHTML = '<option value="">Select doctor and date first</option>';
        slotMessage.textContent = "";

        if (!doctorId || !appointmentDate) {
            return;
        }

        timeSelect.innerHTML = '<option value="">Loading slots...</option>';

        fetch("./api/receptionist_available_slots.php?doctor_id=" + doctorId + "&appointment_date=" + appointmentDate)
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                timeSelect.innerHTML = "";

                if (!data.success || data.slots.length === 0) {
                    timeSelect.innerHTML = '<option value="">No slots available</option>';
                    slotMessage.style.color = "#c0392b";
                    slotMessage.textContent = data.message || "No available slot found.";
                    return;
                }

                timeSelect.innerHTML = '<option value="">Select time</option>';

                data.slots.forEach(function (slot) {
                    const option = document.createElement("option");
                    option.value = slot.value;
                    option.textContent = slot.label;
                    timeSelect.appendChild(option);
                });

                slotMessage.style.color = "#27ae60";
                slotMessage.textContent = data.message;
            })
            .catch(function () {
                timeSelect.innerHTML = '<option value="">Error loading slots</option>';
                slotMessage.style.color = "#c0392b";
                slotMessage.textContent = "Could not load slots. Check API file path.";
            });
    }

    doctorSelect.addEventListener("change", loadSlots);
    dateInput.addEventListener("change", loadSlots);
});
</script>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>