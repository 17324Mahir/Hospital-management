<?php

require_once 'models/DoctorModel.php';

class DoctorController {
    private $model;

    public function __construct($conn) {
        $this->model = new DoctorModel($conn);
    }

    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
            header("Location: index.php");
            exit();
        }

        $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

        if (!$doctor) {
            die("Doctor profile not found.");
        }

        $doctor_id = $doctor['doctor_id'];

        $todayAppointments = $this->model->getTodayAppointmentsCount($doctor_id);
        $pendingAppointments = $this->model->getPendingAppointmentsCount($doctor_id);
        $completedAppointments = $this->model->getCompletedAppointmentsCount($doctor_id);
        $totalEarnings = $this->model->getTotalEarnings($doctor_id);

        include 'views/Doctor/Doctordashboard.php';
    }

    public function profile() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $specialization = trim($_POST['specialization'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $consultation_fee = $_POST['consultation_fee'] ?? '';
        $license_number = trim($_POST['license_number'] ?? '');
        $experience_years = $_POST['experience_years'] ?? '';

        if (
            empty($name) ||
            empty($mobile) ||
            empty($specialization) ||
            empty($consultation_fee) ||
            empty($license_number) ||
            $experience_years === ''
        ) {
            $error = "Please fill all required fields.";
        } elseif (!is_numeric($consultation_fee) || $consultation_fee < 0) {
            $error = "Consultation fee must be a valid positive number.";
        } elseif (!is_numeric($experience_years) || $experience_years < 0) {
            $error = "Experience years must be a valid positive number.";
        } else {
            $success = $this->model->updateDoctorProfile(
                $_SESSION['user_id'],
                $name,
                $mobile,
                $specialization,
                $bio,
                $consultation_fee,
                $license_number,
                $experience_years
            );

            if ($success) {
                $_SESSION['name'] = $name;
                $message = "Professional profile updated successfully.";
                $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);
            } else {
                $error = "Failed to update profile.";
            }
        }
    }

    include 'views/Doctor/profile.php';
}

 public function availability() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $doctor_id = $doctor['doctor_id'];

    $message = "";
    $error = "";

    $days = [
        'Saturday',
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday'
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $successCount = 0;

        foreach ($days as $day) {
            $is_available = isset($_POST['is_available'][$day]) ? 1 : 0;
            $start_time = $_POST['start_time'][$day] ?? '10:00';
            $end_time = $_POST['end_time'][$day] ?? '14:00';
            $slot_duration = $_POST['slot_duration'][$day] ?? 30;

            if ($is_available == 1) {
                if (empty($start_time) || empty($end_time) || empty($slot_duration)) {
                    $error = "Please fill start time, end time, and slot duration for available days.";
                    break;
                }

                if ($start_time >= $end_time) {
                    $error = "$day: Start time must be before end time.";
                    break;
                }

                if (!is_numeric($slot_duration) || $slot_duration <= 0) {
                    $error = "$day: Slot duration must be a positive number.";
                    break;
                }
            }

            $saved = $this->model->saveAvailability(
                $doctor_id,
                $day,
                $start_time,
                $end_time,
                $slot_duration,
                $is_available
            );

            if ($saved) {
                $successCount++;
            }
        }

        if (empty($error) && $successCount > 0) {
            $message = "Weekly availability updated successfully.";
        }
    }

    $availabilityResult = $this->model->getAvailability($doctor_id);

    $availability = [];

    while ($row = $availabilityResult->fetch_assoc()) {
        $availability[$row['day_of_week']] = $row;
    }

    include 'views/Doctor/availability.php';
}

  public function leave_dates() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $doctor_id = $doctor['doctor_id'];

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $leave_date = $_POST['leave_date'] ?? '';
        $reason = trim($_POST['reason'] ?? '');

        if (empty($leave_date)) {
            $error = "Please select a leave date.";
        } elseif ($leave_date < date('Y-m-d')) {
            $error = "Leave date cannot be in the past.";
        } else {
            try {
                $success = $this->model->addLeaveDate($doctor_id, $leave_date, $reason);

                if ($success) {
                    $message = "Leave date added successfully.";
                } else {
                    $error = "Failed to add leave date.";
                }
            } catch (mysqli_sql_exception $e) {
                if (strpos($e->getMessage(), 'Duplicate') !== false) {
                    $error = "This leave date already exists.";
                } else {
                    $error = "Database error: " . $e->getMessage();
                }
            }
        }
    }

    $leaveDates = $this->model->getLeaveDates($doctor_id);

    include 'views/Doctor/leave_dates.php';
}

public function delete_leave_date() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $leave_id = $_GET['id'] ?? null;

    if (!$leave_id) {
        die("Leave ID missing.");
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $leave = $this->model->getLeaveDateById($leave_id);

    if (!$leave) {
        die("Leave date not found.");
    }

    if ($leave['doctor_id'] != $doctor['doctor_id']) {
        die("You are not allowed to delete this leave date.");
    }

    $success = $this->model->deleteLeaveDate($leave_id, $doctor['doctor_id']);

    if ($success) {
        header("Location: Dashboard.php?action=leave_dates&deleted=1");
        exit();
    } else {
        die("Failed to delete leave date.");
    }
}
 public function today_appointments() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $doctor_id = $doctor['doctor_id'];

    $appointments = $this->model->getTodayAppointments($doctor_id);

    include 'views/Doctor/today_appointments.php';
}

public function pending_appointments() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $appointments = $this->model->getPendingAppointments($doctor['doctor_id']);

    include 'views/Doctor/pending_appointments.php';
}


  public function update_appointment_status() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $appointment_id = $_GET['id'] ?? null;
    $new_status = $_GET['status'] ?? null;

    if (!$appointment_id || !$new_status) {
        die("Appointment ID or status missing.");
    }

    if (!in_array($new_status, ['confirmed', 'cancelled'])) {
        die("Invalid status.");
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $appointment = $this->model->getDoctorAppointmentById($appointment_id, $doctor['doctor_id']);

    if (!$appointment) {
        die("Appointment not found or access denied.");
    }

    if ($appointment['status'] !== 'pending') {
        die("Only pending appointments can be updated.");
    }

    $success = $this->model->updateAppointmentStatus(
        $appointment_id,
        $doctor['doctor_id'],
        $new_status
    );

    if ($success) {
        header("Location: Dashboard.php?action=pending_appointments&updated=1");
        exit();
    } else {
        die("Failed to update appointment status.");
    }
}

public function complete_appointment() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $appointment_id = $_POST['appointment_id'] ?? '';
        $patient_id = $_POST['patient_id'] ?? '';
        $symptoms = trim($_POST['symptoms'] ?? '');
        $diagnosis = trim($_POST['diagnosis'] ?? '');
        $prescription = trim($_POST['prescription'] ?? '');
        $follow_up_date = $_POST['follow_up_date'] ?? '';

        if (
            empty($appointment_id) ||
            empty($patient_id) ||
            empty($symptoms) ||
            empty($diagnosis) ||
            empty($prescription)
        ) {
            $error = "Please fill appointment, symptoms, diagnosis, and prescription.";
        } elseif (!empty($follow_up_date) && $follow_up_date < date('Y-m-d')) {
            $error = "Follow-up date cannot be in the past.";
        } else {
            $appointment = $this->model->getDoctorAppointmentById($appointment_id, $doctor['doctor_id']);

            if (!$appointment) {
                $error = "Appointment not found or access denied.";
            } elseif ($appointment['status'] !== 'checked_in') {
                $error = "Only checked-in appointments can be completed.";
            } else {
                $success = $this->model->addConsultationNote(
                    $appointment_id,
                    $doctor['doctor_id'],
                    $patient_id,
                    $symptoms,
                    $diagnosis,
                    $prescription,
                    $follow_up_date
                );

                if ($success) {
                    $message = "Appointment completed and consultation note saved.";
                } else {
                    $error = "Failed to complete appointment.";
                }
            }
        }
    }

    $appointments = $this->model->getCheckedInAppointments($doctor['doctor_id']);

    include 'views/Doctor/complete_appointment.php';
}

public function patient_history() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $doctor_id = $doctor['doctor_id'];

    $selected_patient_id = $_GET['patient_id'] ?? null;
    $selectedPatient = null;
    $history = null;

    if ($selected_patient_id) {
        $selectedPatient = $this->model->getPatientBasicInfo($selected_patient_id);
        $history = $this->model->getPatientConsultationHistory($doctor_id, $selected_patient_id);
    }

    $patients = $this->model->getPatientsWithConsultationNotes($doctor_id);

    include 'views/Doctor/patient_history.php';
}

public function reviews() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $reviews = $this->model->getDoctorReviews($doctor['doctor_id']);
    $reviewStats = $this->model->getDoctorReviewStats($doctor['doctor_id']);

    include 'views/Doctor/reviews.php';
}

public function earnings() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $doctor_id = $doctor['doctor_id'];

    $summary = $this->model->getEarningsSummary($doctor_id);
    $dailyEarnings = $this->model->getDailyEarnings($doctor_id);
    $monthlyEarnings = $this->model->getMonthlyEarnings($doctor_id);
    $completedAppointments = $this->model->getCompletedAppointmentEarnings($doctor_id);

    include 'views/Doctor/earnings.php';
}
public function statistics() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $doctor_id = $doctor['doctor_id'];

    $totalAppointments = $this->model->getTotalAppointments($doctor_id);

    $statusResult = $this->model->getAppointmentStatusStats($doctor_id);

    $statusStats = [
        'pending' => 0,
        'confirmed' => 0,
        'checked_in' => 0,
        'completed' => 0,
        'cancelled' => 0,
        'no_show' => 0
    ];

    while ($row = $statusResult->fetch_assoc()) {
        $statusStats[$row['status']] = $row['total'];
    }

    $completed = $statusStats['completed'];
    $cancelled = $statusStats['cancelled'];
    $noShow = $statusStats['no_show'];

    $completionRate = 0;
    $noShowRate = 0;
    $cancelRate = 0;

    if ($totalAppointments > 0) {
        $completionRate = ($completed / $totalAppointments) * 100;
        $noShowRate = ($noShow / $totalAppointments) * 100;
        $cancelRate = ($cancelled / $totalAppointments) * 100;
    }

    $busiestDays = $this->model->getBusiestDays($doctor_id);
    $busiestTimes = $this->model->getBusiestTimes($doctor_id);
    $monthlyStats = $this->model->getMonthlyAppointmentStats($doctor_id);

    include 'views/Doctor/statistics.php';
}
public function followups() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
        header("Location: index.php");
        exit();
    }

    $doctor = $this->model->getDoctorByUserId($_SESSION['user_id']);

    if (!$doctor) {
        die("Doctor profile not found.");
    }

    $followups = $this->model->getUpcomingFollowUps($doctor['doctor_id']);

    include 'views/Doctor/followups.php';
}

}

?>