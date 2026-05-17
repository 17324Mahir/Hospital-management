<?php

require_once 'models/ReceptionistModel.php';

class ReceptionistController {
    private $model;

    public function __construct($conn) {
        $this->model = new ReceptionistModel($conn);
    }

    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
            header("Location: index.php");
            exit();
        }

        $receptionist = $this->model->getReceptionistByUserId($_SESSION['user_id']);

        if (!$receptionist) {
            die("Receptionist profile not found.");
        }

        $todayTotal = $this->model->getTodayTotalAppointments();
        $todayCheckedIn = $this->model->getTodayCheckedInAppointments();
        $todayCompleted = $this->model->getTodayCompletedAppointments();
        $todayCancelled = $this->model->getTodayCancelledAppointments();
        $todayRevenue = $this->model->getTodayRevenue();
        $pendingBills = $this->model->getPendingBillsCount();

        include 'views/Receptionist/Receptionistdashboard.php';
    }
    public function daily_schedule() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $receptionist = $this->model->getReceptionistByUserId($_SESSION['user_id']);

    if (!$receptionist) {
        die("Receptionist profile not found.");
    }

    $appointmentsResult = $this->model->getTodayAppointmentsGrouped();

    $groupedAppointments = [];

    while ($row = $appointmentsResult->fetch_assoc()) {
        $doctorKey = $row['doctor_id'] . '_' . $row['doctor_name'];

        if (!isset($groupedAppointments[$doctorKey])) {
            $groupedAppointments[$doctorKey] = [
                'doctor_name' => $row['doctor_name'],
                'specialization' => $row['specialization'],
                'appointments' => []
            ];
        }

        $groupedAppointments[$doctorKey]['appointments'][] = $row;
    }

    include 'views/Receptionist/daily_schedule.php';
}
public function patient_search() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $keyword = trim($_GET['keyword'] ?? '');
    $patients = null;

    if (!empty($keyword)) {
        $patients = $this->model->searchPatients($keyword);
    }

    include 'views/Receptionist/patient_search.php';
}
public function patient_details() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $patient_id = $_GET['id'] ?? null;

    if (!$patient_id) {
        die("Patient ID missing.");
    }

    $patient = $this->model->getPatientById($patient_id);

    if (!$patient) {
        die("Patient not found.");
    }

    $upcomingAppointments = $this->model->getPatientUpcomingAppointments($patient_id);
    $billingSummary = $this->model->getPatientBillingSummary($patient_id);

    include 'views/Receptionist/patient_details.php';
}
public function register_patient() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $date_of_birth = $_POST['date_of_birth'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $blood_group = $_POST['blood_group'] ?? '';
        $address = trim($_POST['address'] ?? '');
        $emergency_contact_name = trim($_POST['emergency_contact_name'] ?? '');
        $emergency_contact_phone = trim($_POST['emergency_contact_phone'] ?? '');
        $medical_history_notes = trim($_POST['medical_history_notes'] ?? '');

        if (
            empty($name) ||
            empty($email) ||
            empty($mobile) ||
            empty($password) ||
            empty($date_of_birth) ||
            empty($gender) ||
            empty($blood_group) ||
            empty($address) ||
            empty($emergency_contact_phone)
        ) {
            $error = "Please fill all required fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } elseif ($date_of_birth > date('Y-m-d')) {
            $error = "Date of birth cannot be in the future.";
        } elseif ($this->model->emailRoleExists($email, 'patient')) {
            $error = "This email is already registered as a patient.";
        } elseif ($this->model->mobileRoleExists($mobile, 'patient')) {
            $error = "This mobile number is already registered as a patient.";
        } else {
            $success = $this->model->registerPatient(
                $name,
                $email,
                $mobile,
                $password,
                $date_of_birth,
                $gender,
                $blood_group,
                $address,
                $emergency_contact_name,
                $emergency_contact_phone,
                $medical_history_notes
            );

            if ($success) {
                $message = "Patient registered successfully.";
            } else {
                $error = "Failed to register patient.";
            }
        }
    }

    include 'views/Receptionist/register_patient.php';
}
public function walkin_booking() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $message = "";
    $error = "";

    $selected_patient_id = $_GET['patient_id'] ?? '';

    $selectedPatient = null;

    if (!empty($selected_patient_id)) {
        $selectedPatient = $this->model->getPatientById($selected_patient_id);
    }

    $doctors = $this->model->getAllApprovedDoctors();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $patient_id = $_POST['patient_id'] ?? '';
        $doctor_id = $_POST['doctor_id'] ?? '';
        $appointment_date = $_POST['appointment_date'] ?? '';
        $appointment_time = $_POST['appointment_time'] ?? '';
        $reason = trim($_POST['reason'] ?? '');

        if (
            empty($patient_id) ||
            empty($doctor_id) ||
            empty($appointment_date) ||
            empty($appointment_time) ||
            empty($reason)
        ) {
            $error = "Please fill all required fields.";
        } elseif ($appointment_date < date('Y-m-d')) {
            $error = "Appointment date cannot be in the past.";
        } else {
            $patient = $this->model->getPatientById($patient_id);
            $doctor = $this->model->getDoctorById($doctor_id);

            if (!$patient) {
                $error = "Invalid patient selected.";
            } elseif (!$doctor) {
                $error = "Invalid doctor selected.";
            } elseif ($this->model->isSlotAlreadyBooked($doctor_id, $appointment_date, $appointment_time)) {
                $error = "This slot is already booked. Please choose another time.";
            } else {
                $success = $this->model->bookWalkinAppointment(
                    $patient_id,
                    $doctor_id,
                    $appointment_date,
                    $appointment_time,
                    $reason
                );

                if ($success) {
                    $message = "Walk-in appointment booked successfully.";
                } else {
                    $error = "Failed to book walk-in appointment.";
                }
            }
        }
    }

    include 'views/Receptionist/walkin_booking.php';
}
public function checkin_patient() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $message = "";
    $error = "";

    if (isset($_GET['checkedin']) && $_GET['checkedin'] == 1) {
        $message = "Patient checked in successfully.";
    }

    $appointments = $this->model->getTodayConfirmedAppointments();

    include 'views/Receptionist/checkin_patient.php';
}
public function mark_checked_in() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $appointment_id = $_GET['id'] ?? null;

    if (!$appointment_id) {
        die("Appointment ID missing.");
    }

    $appointment = $this->model->getAppointmentById($appointment_id);

    if (!$appointment) {
        die("Appointment not found.");
    }

    if ($appointment['appointment_date'] !== date('Y-m-d')) {
        die("Only today's appointments can be checked in.");
    }

    if ($appointment['status'] !== 'confirmed') {
        die("Only confirmed appointments can be checked in.");
    }

    $success = $this->model->checkInAppointment($appointment_id);

    if ($success) {
        header("Location: Dashboard.php?action=checkin_patient&checkedin=1");
        exit();
    } else {
        die("Failed to check in patient.");
    }
}
public function waiting_queue() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $queueResult = $this->model->getWaitingRoomQueue();

    $groupedQueue = [];

    while ($row = $queueResult->fetch_assoc()) {
        $doctorKey = $row['doctor_id'] . '_' . $row['doctor_name'];

        if (!isset($groupedQueue[$doctorKey])) {
            $groupedQueue[$doctorKey] = [
                'doctor_name' => $row['doctor_name'],
                'specialization' => $row['specialization'],
                'patients' => []
            ];
        }

        $groupedQueue[$doctorKey]['patients'][] = $row;
    }

    include 'views/Receptionist/waiting_queue.php';
}
public function payments() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $message = "";
    $error = "";

    if (isset($_GET['paid']) && $_GET['paid'] == 1) {
        $message = "Payment processed successfully.";
    }

    $payments = $this->model->getPendingPayments();

    include 'views/Receptionist/payments.php';
}
public function process_payment() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $billing_id = $_GET['id'] ?? null;

    if (!$billing_id) {
        die("Billing ID missing.");
    }

    $bill = $this->model->getBillingById($billing_id);

    if (!$bill) {
        die("Billing record not found.");
    }

    if ($bill['payment_status'] !== 'pending') {
        die("This bill is already paid.");
    }

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $payment_method = $_POST['payment_method'] ?? '';

        if (empty($payment_method)) {
            $error = "Please select payment method.";
        } else {
            $success = $this->model->markBillAsPaid($billing_id, $payment_method);

          if ($success) {
            header("Location: Dashboard.php?action=receipt&id=" . $billing_id);
               exit();
             } else {
             $error = "Failed to process payment.";
              }
        }
    }

    include 'views/Receptionist/process_payment.php';
}

public function receipt() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $billing_id = $_GET['id'] ?? null;

    if (!$billing_id) {
        die("Billing ID missing.");
    }

    $bill = $this->model->getBillingById($billing_id);

    if (!$bill) {
        die("Billing record not found.");
    }

    include 'views/Receptionist/receipt.php';
}
public function manage_appointments() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $message = "";
    $error = "";

    if (isset($_GET['cancelled']) && $_GET['cancelled'] == 1) {
        $message = "Appointment cancelled successfully.";
    }

    if (isset($_GET['rescheduled']) && $_GET['rescheduled'] == 1) {
        $message = "Appointment rescheduled successfully.";
    }

    $appointments = $this->model->getAllActiveAppointments();

    include 'views/Receptionist/manage_appointments.php';
}
public function cancel_appointment() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $appointment_id = $_GET['id'] ?? null;

    if (!$appointment_id) {
        die("Appointment ID missing.");
    }

    $appointment = $this->model->getAppointmentById($appointment_id);

    if (!$appointment) {
        die("Appointment not found.");
    }

    if (!in_array($appointment['status'], ['pending', 'confirmed'])) {
        die("Only pending or confirmed appointments can be cancelled.");
    }

    $success = $this->model->cancelAppointmentByReceptionist($appointment_id);

    if ($success) {
        header("Location: Dashboard.php?action=manage_appointments&cancelled=1");
        exit();
    } else {
        die("Failed to cancel appointment.");
    }
}
public function reschedule_appointment() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $appointment_id = $_GET['id'] ?? null;

    if (!$appointment_id) {
        die("Appointment ID missing.");
    }

    $appointment = $this->model->getAppointmentById($appointment_id);

    if (!$appointment) {
        die("Appointment not found.");
    }

    if (!in_array($appointment['status'], ['pending', 'confirmed'])) {
        die("Only pending or confirmed appointments can be rescheduled.");
    }

    $doctors = $this->model->getAllApprovedDoctors();

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $doctor_id = $_POST['doctor_id'] ?? '';
        $new_date = $_POST['new_date'] ?? '';
        $new_time = $_POST['new_time'] ?? '';

        if (empty($doctor_id) || empty($new_date) || empty($new_time)) {
            $error = "Please select doctor, date, and time.";
        } elseif ($new_date < date('Y-m-d')) {
            $error = "Appointment date cannot be in the past.";
        } elseif (!$this->model->getDoctorById($doctor_id)) {
            $error = "Invalid doctor selected.";
        } elseif ($this->model->isSlotAlreadyBooked($doctor_id, $new_date, $new_time)) {
            $error = "This slot is already booked. Please choose another time.";
        } else {
            $success = $this->model->rescheduleAppointmentByReceptionist(
                $appointment_id,
                $doctor_id,
                $new_date,
                $new_time
            );

            if ($success) {
                header("Location: Dashboard.php?action=manage_appointments&rescheduled=1");
                exit();
            } else {
                $error = "Failed to reschedule appointment.";
            }
        }
    }

    include 'views/Receptionist/reschedule_appointment.php';
}
public function doctor_availability() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $selected_date = $_GET['selected_date'] ?? date('Y-m-d');

    if ($selected_date < date('Y-m-d')) {
        $selected_date = date('Y-m-d');
    }

    $availabilityResult = $this->model->getDoctorAvailabilityByDate($selected_date);

    $doctorAvailability = [];

    while ($row = $availabilityResult->fetch_assoc()) {
        $bookedCount = $this->model->countBookedSlotsForDoctorDate($row['doctor_id'], $selected_date);

        $totalSlots = 0;
        $availableSlots = 0;

        if (
            !empty($row['start_time']) &&
            !empty($row['end_time']) &&
            !empty($row['slot_duration_minutes']) &&
            $row['is_available'] == 1 &&
            empty($row['leave_id'])
        ) {
            $start = strtotime($row['start_time']);
            $end = strtotime($row['end_time']);
            $duration = (int)$row['slot_duration_minutes'];

            if ($duration > 0 && $end > $start) {
                $totalSlots = floor(($end - $start) / 60 / $duration);
                $availableSlots = max(0, $totalSlots - $bookedCount);
            }
        }

        $row['booked_count'] = $bookedCount;
        $row['total_slots'] = $totalSlots;
        $row['available_slots'] = $availableSlots;

        $doctorAvailability[] = $row;
    }

    include 'views/Receptionist/doctor_availability.php';
}
public function daily_report() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receptionist') {
        header("Location: index.php");
        exit();
    }

    $selected_date = $_GET['selected_date'] ?? date('Y-m-d');

    $appointmentSummary = $this->model->getDailyAppointmentSummary($selected_date);
    $billingSummary = $this->model->getDailyBillingSummary($selected_date);
    $paymentMethodSummary = $this->model->getDailyPaymentMethodSummary($selected_date);
    $doctorWiseSummary = $this->model->getDoctorWiseDailySummary($selected_date);
    $detailedAppointments = $this->model->getDailyDetailedAppointments($selected_date);

    include 'views/Receptionist/daily_report.php';
}
}

?>