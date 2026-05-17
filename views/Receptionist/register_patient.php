<?php
include 'views/Receptionist/partials/receptionist_header.php';
?>

<div class="page-card page-title">
    <h1>Register New Patient</h1>
    <p>
        Create a new patient account from the reception desk. After registration,
        the patient can log in using the email and password entered here.
    </p>
</div>

<div class="page-card">

    <?php if (!empty($message)): ?>
        <div class="success">
            <?= htmlspecialchars($message); ?>
            <br><br>

            <a href="Dashboard.php?action=patient_search" class="btn">
                🔍 Search Patient
            </a>

            <a href="Dashboard.php?action=walkin_booking" class="btn btn-orange">
                🚶 Book Walk-in Appointment
            </a>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="error">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="note">
        Fields marked with * are required.
    </div>

    <form method="POST" action="">

        <div class="grid-2">

            <div>
                <label for="name">Full Name *</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name"
                    placeholder="Enter patient full name"
                    required
                >
            </div>

            <div>
                <label for="email">Email *</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    placeholder="Enter patient email"
                    required
                >
            </div>

            <div>
                <label for="mobile">Mobile Number *</label>
                <input 
                    type="text" 
                    name="mobile" 
                    id="mobile"
                    placeholder="Enter mobile number"
                    required
                >
            </div>

            <div>
                <label for="password">Password *</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    placeholder="Create password"
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
                    required
                >
            </div>

            <div>
                <label for="gender">Gender *</label>
                <select name="gender" id="gender" required>
                    <option value="">Select gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label for="blood_group">Blood Group *</label>
                <select name="blood_group" id="blood_group" required>
                    <option value="">Select blood group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>

            <div>
                <label for="emergency_contact_name">Emergency Contact Name</label>
                <input 
                    type="text" 
                    name="emergency_contact_name" 
                    id="emergency_contact_name"
                    placeholder="Enter emergency contact name"
                >
            </div>

            <div>
                <label for="emergency_contact_phone">Emergency Contact Phone *</label>
                <input 
                    type="text" 
                    name="emergency_contact_phone" 
                    id="emergency_contact_phone"
                    placeholder="Enter emergency contact phone"
                    required
                >
            </div>

            <div>
                <label for="address">Address *</label>
                <textarea 
                    name="address" 
                    id="address"
                    placeholder="Enter patient address"
                    required
                ></textarea>
            </div>

        </div>

        <div style="margin-top:18px;">
            <label for="medical_history_notes">Medical History Notes</label>
            <textarea 
                name="medical_history_notes" 
                id="medical_history_notes"
                placeholder="Write any important medical history, allergy, previous disease, etc."
            ></textarea>
        </div>

        <div style="margin-top:22px;">
            <button type="submit" class="btn btn-purple">
                 Register Patient
            </button>

            <a href="Dashboard.php?action=patient_search" class="btn btn-gray">
                Cancel
            </a>
        </div>

    </form>

</div>

<?php
include 'views/Receptionist/partials/receptionist_footer.php';
?>