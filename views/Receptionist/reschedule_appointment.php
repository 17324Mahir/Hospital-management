<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Reschedule Appointment</h1>
    <p>
        Change the doctor, appointment date, and available time slot for an active appointment.
    </p>
</div>

<?php if (!empty($error)): ?>
    <div class="error">
        <?= htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="page-card">

    <h2>Current Appointment</h2>

    <div class="grid-2">

        <div>
            <p>
                <strong>Appointment ID:</strong>
                #<?= htmlspecialchars($appointment['id']); ?>
            </p>

            <p>
                <strong>Patient:</strong>
                <?= htmlspecialchars($appointment['patient_name']); ?>
            </p>

            <p>
                <strong>Phone:</strong>
                <?= htmlspecialchars($appointment['patient_mobile']); ?>
            </p>
        </div>

        <div>
            <p>
                <strong>Current Doctor:</strong>
                Dr. <?= htmlspecialchars($appointment['doctor_name']); ?>
            </p>

            <p>
                <strong>Current Date:</strong>
                <?= htmlspecialchars($appointment['appointment_date']); ?>
            </p>

            <p>
                <strong>Current Time:</strong>
                <?= htmlspecialchars(date('h:i A', strtotime($appointment['appointment_time']))); ?>
            </p>
        </div>

    </div>

    <div class="note">
        <strong>Reason:</strong>
        <?= htmlspecialchars($appointment['reason']); ?>
        <br>
        <strong>Status:</strong>
        <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $appointment['status']))); ?>
    </div>

</div>

<div class="page-card">

    <h2>New Schedule</h2>

    <form method="POST" action="">

        <div class="grid-2">

            <div>
                <label for="doctor_id">Select Doctor *</label>
                <select name="doctor_id" id="doctor_id" required>
                    <option value="">Select doctor</option>

                    <?php if ($doctors && $doctors->num_rows > 0): ?>
                        <?php while ($doctor = $doctors->fetch_assoc()): ?>
                            <option 
                                value="<?= htmlspecialchars($doctor['doctor_id']); ?>"
                                <?= $doctor['doctor_id'] == $appointment['doctor_id'] ? 'selected' : ''; ?>
                            >
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
                <label for="new_date">New Appointment Date *</label>
                <input 
                    type="date" 
                    name="new_date" 
                    id="new_date"
                    min="<?= date('Y-m-d'); ?>"
                    value="<?= htmlspecialchars($appointment['appointment_date']); ?>"
                    required
                >
            </div>

            <div>
                <label for="new_time">Available Time Slot *</label>
                <select name="new_time" id="new_time" required>
                    <option value="">Select doctor and date first</option>
                </select>

                <p id="slotMessage" class="small" style="margin-top:8px;"></p>
            </div>

        </div>

        <div style="margin-top:22px;">
            <button 
                type="submit" 
                class="btn btn-orange"
                onclick="return confirm('Are you sure you want to reschedule this appointment?');"
            >
                 Reschedule Appointment
            </button>

            <a href="Dashboard.php?action=manage_appointments" class="btn btn-gray">
                Cancel
            </a>
        </div>

    </form>

</div>

<div class="page-card">
    <div class="note">
        Time slots will load automatically after selecting doctor and date.
        Already booked slots will not be shown.
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const doctorSelect = document.getElementById("doctor_id");
    const dateInput = document.getElementById("new_date");
    const timeSelect = document.getElementById("new_time");
    const slotMessage = document.getElementById("slotMessage");

    const currentTime = "<?= htmlspecialchars($appointment['appointment_time']); ?>";

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

                    if (slot.value === currentTime) {
                        option.selected = true;
                    }

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

    loadSlots();
});
</script>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>