<?php
session_start();

require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login_btn'])) {
    header("Location: index.php");
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

$allowed_roles = ['patient', 'doctor', 'receptionist', 'admin'];

if (empty($email) || empty($password) || empty($role)) {
    header("Location: login.php?role=" . urlencode($role) . "&error=" . urlencode("Please fill all fields."));
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php?role=" . urlencode($role) . "&error=" . urlencode("Invalid email address."));
    exit();
}

if (!in_array($role, $allowed_roles)) {
    header("Location: login.php?error=" . urlencode("Invalid role selected."));
    exit();
}

$sql = "SELECT 
            id,
            name,
            email,
            password_hash,
            role,
            is_active,
            status
        FROM users
        WHERE email = ?
        AND role = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ss", $email, $role);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: login.php?role=" . urlencode($role) . "&error=" . urlencode("Invalid email, password, or role."));
    exit();
}

$user = $result->fetch_assoc();

$passwordOk = false;

if ($password === $user['password_hash']) {
    $passwordOk = true;
} elseif (password_verify($password, $user['password_hash'])) {
    $passwordOk = true;
}

if (!$passwordOk) {
    header("Location: login.php?role=" . urlencode($role) . "&error=" . urlencode("Invalid email, password, or role."));
    exit();
}

if ((int)$user['is_active'] !== 1) {
    header("Location: login.php?role=" . urlencode($role) . "&error=" . urlencode("Your account is inactive. Please contact admin."));
    exit();
}

if ($role === 'doctor') {
    $doctorSql = "SELECT is_approved, status 
                  FROM doctors 
                  WHERE user_id = ?
                  LIMIT 1";

    $doctorStmt = $conn->prepare($doctorSql);

    if (!$doctorStmt) {
        die("Prepare failed: " . $conn->error);
    }

    $doctorStmt->bind_param("i", $user['id']);
    $doctorStmt->execute();

    $doctorResult = $doctorStmt->get_result();
    $doctor = $doctorResult->fetch_assoc();

    if (!$doctor) {
        header("Location: login.php?role=doctor&error=" . urlencode("Doctor profile not found."));
        exit();
    }

    if ((int)$doctor['is_approved'] !== 1 || $doctor['status'] !== 'approved') {
        header("Location: login.php?role=doctor&error=" . urlencode("Your doctor account is pending admin approval."));
        exit();
    }
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['name'] = $user['name'];
$_SESSION['email'] = $user['email'];
$_SESSION['role'] = $user['role'];

header("Location: Dashboard.php");
exit();
?>