<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access."
    ]);
    exit();
}

require_once '../config/database.php';

$doctor_id = $_GET['doctor_id'] ?? '';
$appointment_date = $_GET['appointment_date'] ?? '';

if (empty($doctor_id) || empty($appointment_date)) {
    echo json_encode([
        "success" => false,
        "message" => "Doctor ID and appointment date are required."
    ]);
    exit();
}

if ($appointment_date < date('Y-m-d')) {
    echo json_encode([
        "success" => false,
        "message" => "Past dates are not allowed."
    ]);
    exit();
}

$day_of_week = date('l', strtotime($appointment_date));

/*
    1. Check doctor leave date
*/
$leave_sql = "SELECT id FROM leave_dates WHERE doctor_id = ? AND leave_date = ?";
$leave_stmt = $conn->prepare($leave_sql);

if (!$leave_stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Leave query failed: " . $conn->error
    ]);
    exit();
}

$leave_stmt->bind_param("is", $doctor_id, $appointment_date);
$leave_stmt->execute();
$leave_result = $leave_stmt->get_result();

if ($leave_result->num_rows > 0) {
    echo json_encode([
        "success" => true,
        "slots" => [],
        "message" => "Doctor is on leave on this date."
    ]);
    exit();
}

/*
    2. Get doctor availability for selected day
*/
$availability_sql = "SELECT start_time, end_time, slot_duration_minutes 
                     FROM doctor_availability 
                     WHERE doctor_id = ? 
                     AND day_of_week = ? 
                     AND is_available = 1 
                     LIMIT 1";

$availability_stmt = $conn->prepare($availability_sql);

if (!$availability_stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Availability query failed: " . $conn->error
    ]);
    exit();
}

$availability_stmt->bind_param("is", $doctor_id, $day_of_week);
$availability_stmt->execute();
$availability_result = $availability_stmt->get_result();

if ($availability_result->num_rows === 0) {
    echo json_encode([
        "success" => true,
        "slots" => [],
        "message" => "Doctor is not available on this day."
    ]);
    exit();
}

$availability = $availability_result->fetch_assoc();

$start_time = $availability['start_time'];
$end_time = $availability['end_time'];
$slot_duration = (int)$availability['slot_duration_minutes'];

/*
    3. Get already booked slots
*/
$booked_sql = "SELECT appointment_time 
               FROM appointments 
               WHERE doctor_id = ? 
               AND appointment_date = ? 
               AND status != 'cancelled'";

$booked_stmt = $conn->prepare($booked_sql);

if (!$booked_stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Booked slots query failed: " . $conn->error
    ]);
    exit();
}

$booked_stmt->bind_param("is", $doctor_id, $appointment_date);
$booked_stmt->execute();
$booked_result = $booked_stmt->get_result();

$booked_slots = [];

while ($row = $booked_result->fetch_assoc()) {
    $booked_slots[] = $row['appointment_time'];
}

/*
    4. Generate slots
*/
$slots = [];

$current = strtotime($start_time);
$end = strtotime($end_time);

while ($current < $end) {
    $slot = date('H:i:s', $current);

    if (!in_array($slot, $booked_slots)) {
        $slots[] = [
            "value" => $slot,
            "label" => date('h:i A', $current)
        ];
    }

    $current = strtotime("+{$slot_duration} minutes", $current);
}

echo json_encode([
    "success" => true,
    "slots" => $slots,
    "message" => count($slots) > 0 ? "Available slots loaded." : "No available slots for this date."
]);
exit();
?>