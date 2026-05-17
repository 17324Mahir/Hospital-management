<?php
session_start();

require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php?error=" . urlencode("Invalid request method."));
    exit();
}

$firstName = trim($_POST['firstName'] ?? '');
$lastName = trim($_POST['lastName'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$dob = $_POST['dob'] ?? '';
$newRole = $_POST['role'] ?? '';

$allowedRoles = ['patient', 'doctor'];
$fullName = trim($firstName . ' ' . $lastName);

if (
    empty($firstName) ||
    empty($lastName) ||
    empty($email) ||
    empty($phone) ||
    empty($password) ||
    empty($confirmPassword) ||
    empty($dob) ||
    empty($newRole)
) {
    header("Location: register.php?error=" . urlencode("Please fill in all required fields."));
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?error=" . urlencode("Please enter a valid email address."));
    exit();
}

if (!in_array($newRole, $allowedRoles)) {
    header("Location: register.php?error=" . urlencode("Invalid role selected."));
    exit();
}

if ($dob > date('Y-m-d')) {
    header("Location: register.php?error=" . urlencode("Date of birth cannot be in the future."));
    exit();
}

if ($password !== $confirmPassword) {
    header("Location: register.php?error=" . urlencode("Passwords do not match."));
    exit();
}

if (strlen($password) < 6) {
    header("Location: register.php?error=" . urlencode("Password must be at least 6 characters."));
    exit();
}

/*
    Your project currently uses plain text passwords in sample data.
    If your login_operations.php uses password_verify(), change this line to:
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
*/
$password_hash = $password;

/*
    Check existing account rules.
    Same email/mobile + same role = duplicate.
    Patient cannot create doctor with same email/mobile.
    Doctor cannot create patient with same email/mobile is allowed? 
    For your earlier rule, patient + doctor same details was restricted.
*/
$checkSql = "SELECT role 
             FROM users 
             WHERE email = ? 
             OR mobile = ?";

$stmtCheck = $conn->prepare($checkSql);

if (!$stmtCheck) {
    die("Prepare failed: " . $conn->error);
}

$stmtCheck->bind_param("ss", $email, $phone);
$stmtCheck->execute();

$resultCheck = $stmtCheck->get_result();

$existingRoles = [];

while ($row = $resultCheck->fetch_assoc()) {
    $existingRoles[] = $row['role'];
}

if (!empty($existingRoles)) {
    if (in_array($newRole, $existingRoles)) {
        header("Location: register.php?error=" . urlencode("Duplicate account. You are already registered as a " . ucfirst($newRole) . "."));
        exit();
    }

    if (in_array('patient', $existingRoles) && $newRole === 'doctor') {
        header("Location: register.php?error=" . urlencode("A registered patient cannot create a doctor account with the same email or mobile."));
        exit();
    }

    if (in_array('doctor', $existingRoles) && $newRole === 'patient') {
        header("Location: register.php?error=" . urlencode("A registered doctor cannot create a patient account with the same email or mobile."));
        exit();
    }
}

$userStatus = ($newRole === 'doctor') ? 'pending' : 'active';
$isActive = 1;

$conn->begin_transaction();

try {
    $insertUserSql = "INSERT INTO users
                      (name, email, password_hash, mobile, phone, role, dob, is_active, status)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmtInsert = $conn->prepare($insertUserSql);

    if (!$stmtInsert) {
        throw new Exception("User insert prepare failed: " . $conn->error);
    }

    $stmtInsert->bind_param(
        "sssssssis",
        $fullName,
        $email,
        $password_hash,
        $phone,
        $phone,
        $newRole,
        $dob,
        $isActive,
        $userStatus
    );

    $stmtInsert->execute();

    $newUserId = $conn->insert_id;

    if ($newRole === 'patient') {
        /*
            Create a basic patient profile immediately.
            Patient can edit details later from patient profile.
        */
        $blood_group = "O+";
        $gender = "Other";
        $address = "Not provided";
        $emergency_contact = $phone;
        $emergency_contact_name = "Not provided";
        $emergency_contact_phone = $phone;
        $medical_history_notes = "No medical history added.";

        $insertPatientSql = "INSERT INTO patients
                             (user_id, date_of_birth, blood_group, gender, address, emergency_contact, emergency_contact_name, emergency_contact_phone, medical_history_notes)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmtPatient = $conn->prepare($insertPatientSql);

        if (!$stmtPatient) {
            throw new Exception("Patient insert prepare failed: " . $conn->error);
        }

        $stmtPatient->bind_param(
            "issssssss",
            $newUserId,
            $dob,
            $blood_group,
            $gender,
            $address,
            $emergency_contact,
            $emergency_contact_name,
            $emergency_contact_phone,
            $medical_history_notes
        );

        $stmtPatient->execute();
    }

    if ($newRole === 'doctor') {
        /*
            Create a basic pending doctor profile.
            Admin will approve later.
        */
        $specialization = "General Physician";
        $bio = "New doctor profile pending update.";
        $consultation_fee = 500.00;
        $license_number = "PENDING-" . $newUserId;
        $experience_years = 0;
        $is_approved = 0;
        $doctor_status = "pending";

        $insertDoctorSql = "INSERT INTO doctors
                            (user_id, specialization, bio, consultation_fee, license_number, experience_years, is_approved, status)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmtDoctor = $conn->prepare($insertDoctorSql);

        if (!$stmtDoctor) {
            throw new Exception("Doctor insert prepare failed: " . $conn->error);
        }

        $stmtDoctor->bind_param(
            "issdsiis",
            $newUserId,
            $specialization,
            $bio,
            $consultation_fee,
            $license_number,
            $experience_years,
            $is_approved,
            $doctor_status
        );

        $stmtDoctor->execute();
    }

    $conn->commit();

    if ($newRole === 'doctor') {
        header("Location: login.php?role=doctor&success=" . urlencode("Registration successful. Your doctor account is pending admin approval."));
        exit();
    }

    header("Location: login.php?role=patient&success=" . urlencode("Registration successful. Please login."));
    exit();

} catch (Exception $e) {
    $conn->rollback();

    header("Location: register.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>