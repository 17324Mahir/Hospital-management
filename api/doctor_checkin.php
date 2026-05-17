<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access."
    ]);
    exit();
}

require_once '../config/database.php';
require_once '../models/DoctorModel.php';

$model = new DoctorModel($conn);

$doctor = $model->getDoctorByUserId($_SESSION['user_id']);

if (!$doctor) {
    echo json_encode([
        "success" => false,
        "message" => "Doctor profile not found."
    ]);
    exit();
}

$appointment_id = $_POST['appointment_id'] ?? '';

if (empty($appointment_id)) {
    echo json_encode([
        "success" => false,
        "message" => "Appointment ID missing."
    ]);
    exit();
}

$appointment = $model->getDoctorAppointmentById($appointment_id, $doctor['doctor_id']);

if (!$appointment) {
    echo json_encode([
        "success" => false,
        "message" => "Appointment not found or access denied."
    ]);
    exit();
}

if ($appointment['status'] !== 'confirmed') {
    echo json_encode([
        "success" => false,
        "message" => "Only confirmed appointments can be checked in."
    ]);
    exit();
}

$success = $model->checkInAppointment($appointment_id, $doctor['doctor_id']);

if ($success) {
    echo json_encode([
        "success" => true,
        "message" => "Patient checked in successfully.",
        "status" => "checked_in",
        "checkin_time" => date('Y-m-d H:i:s')
    ]);
    exit();
}

echo json_encode([
    "success" => false,
    "message" => "Failed to check in patient."
]);
exit();
?>