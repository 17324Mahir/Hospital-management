<?php

require_once 'models/PatientModel.php';

class PatientController {
    private $model;

    public function __construct($conn) {
        $this->model = new PatientModel($conn);
    }

    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
            header("Location: index.php");
            exit();
        }

        $user_id = $_SESSION['user_id'];

        $patient = $this->model->getPatientByUserId($user_id);

        if (!$patient) {
            die("Patient profile not found. Please complete your patient profile first.");
        }

        $patient_id = $patient['id'];

        $appointments = $this->model->getMyHistory($patient_id);

        // CHANGED: folder name is Patient
        include 'views/Patient/Patientdashboard.php';
    }

    public function doctors() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
            header("Location: index.php");
            exit();
        }

        $doctors = $this->model->getApprovedDoctors();

        // CHANGED: folder name is Patient
        include 'views/Patient/doctors.php';
    }

    public function book_appointment() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        header("Location: index.php");
        exit();
    }

    $doctor_id = $_GET['doctor_id'] ?? null;

    if (!$doctor_id) {
        die("Doctor ID missing.");
    }

    $doctor = $this->model->getDoctorById($doctor_id);

    if (!$doctor) {
        die("Doctor not found or not approved.");
    }

    $patient = $this->model->getPatientByUserId($_SESSION['user_id']);

    if (!$patient) {
        die("Patient profile not found.");
    }

    $dependents = $this->model->getDependents($patient['id']);

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $booking_for = $_POST['booking_for'] ?? 'self';
        $dependent_id = $_POST['dependent_id'] ?? null;
        $appointment_date = $_POST['appointment_date'] ?? '';
        $appointment_time = $_POST['appointment_time'] ?? '';
        $reason = trim($_POST['reason'] ?? '');

        if ($booking_for === 'self') {
            $dependent_id = null;
        }

        if (empty($appointment_date) || empty($appointment_time) || empty($reason)) {
            $error = "All fields are required.";
        } elseif ($appointment_date < date('Y-m-d')) {
            $error = "Appointment date cannot be in the past.";
        } elseif ($booking_for === 'dependent' && empty($dependent_id)) {
            $error = "Please select a dependent.";
        } elseif ($booking_for === 'dependent' && !$this->model->isMyDependent($dependent_id, $patient['id'])) {
            $error = "Invalid dependent selected.";
        } elseif ($this->model->isSlotAlreadyBooked($doctor_id, $appointment_date, $appointment_time)) {
            $error = "This time slot is already booked. Please choose another time.";
        } else {
            $success = $this->model->bookAppointment(
                $patient['id'],
                $dependent_id,
                $doctor_id,
                $appointment_date,
                $appointment_time,
                $reason
            );

            if ($success) {
                $message = "Appointment booked successfully. Status: Pending.";
            } else {
                $error = "Failed to book appointment.";
            }
        }
    }

    include 'views/Patient/book_appointment.php';
}
      
    public function cancel_appointment() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        header("Location: index.php");
        exit();
    }

    $appointment_id = $_GET['id'] ?? null;

    if (!$appointment_id) {
        die("Appointment ID missing.");
    }

    $patient = $this->model->getPatientByUserId($_SESSION['user_id']);

    if (!$patient) {
        die("Patient profile not found.");
    }

    $appointment = $this->model->getAppointmentById($appointment_id);

    if (!$appointment) {
        die("Appointment not found.");
    }

    if ($appointment['patient_id'] != $patient['id']) {
        die("You are not allowed to cancel this appointment.");
    }

    if (!in_array($appointment['status'], ['pending', 'confirmed'])) {
        die("Only pending or confirmed appointments can be cancelled.");
    }

    $success = $this->model->cancelAppointment($appointment_id, $patient['id']);

    if ($success) {
        header("Location: Dashboard.php?cancelled=1");
        exit();
    } else {
        die("Failed to cancel appointment.");
    }
}

   public function billing() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        header("Location: index.php");
        exit();
    }

    $patient = $this->model->getPatientByUserId($_SESSION['user_id']);

    if (!$patient) {
        die("Patient profile not found.");
    }

    $billings = $this->model->getBillingHistory($patient['id']);

    include 'views/Patient/billing.php';
}

public function reviews() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        header("Location: index.php");
        exit();
    }

    $patient = $this->model->getPatientByUserId($_SESSION['user_id']);

    if (!$patient) {
        die("Patient profile not found.");
    }

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $appointment_id = $_POST['appointment_id'] ?? '';
        $rating = $_POST['rating'] ?? '';
        $review_text = trim($_POST['review_text'] ?? '');

        if (empty($appointment_id) || empty($rating) || empty($review_text)) {
            $error = "All fields are required.";
        } elseif ($rating < 1 || $rating > 5) {
            $error = "Rating must be between 1 and 5.";
        } else {
            $appointment = $this->model->getCompletedAppointmentById($appointment_id, $patient['id']);

            if (!$appointment) {
                $error = "Invalid appointment selected.";
            } else {
                $success = $this->model->addDoctorReview(
                    $appointment['appointment_id'],
                    $patient['id'],
                    $appointment['doctor_id'],
                    $rating,
                    $review_text
                );

                if ($success) {
                    $message = "Review submitted successfully.";
                } else {
                    $error = "Failed to submit review.";
                }
            }
        }
    }

    $completedAppointments = $this->model->getCompletedAppointmentsForReview($patient['id']);
    $myReviews = $this->model->getMyReviews($patient['id']);

    include 'views/Patient/reviews.php';
}

   public function announcements() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        header("Location: index.php");
        exit();
    }

    $announcements = $this->model->getPatientAnnouncements();

    include 'views/Patient/announcements.php';
}
   public function profile() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        header("Location: index.php");
        exit();
    }

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $date_of_birth = $_POST['date_of_birth'] ?? '';
        $blood_group = trim($_POST['blood_group'] ?? '');
        $gender = $_POST['gender'] ?? '';
        $address = trim($_POST['address'] ?? '');
        $emergency_contact_name = trim($_POST['emergency_contact_name'] ?? '');
        $emergency_contact_phone = trim($_POST['emergency_contact_phone'] ?? '');
        $medical_history_notes = trim($_POST['medical_history_notes'] ?? '');

        if (
            empty($name) ||
            empty($mobile) ||
            empty($date_of_birth) ||
            empty($blood_group) ||
            empty($gender) ||
            empty($address) ||
            empty($emergency_contact_phone)
        ) {
            $error = "Please fill all required fields.";
        } else {
            $success = $this->model->updatePatientProfile(
                $_SESSION['user_id'],
                $name,
                $mobile,
                $date_of_birth,
                $blood_group,
                $gender,
                $address,
                $emergency_contact_name,
                $emergency_contact_phone,
                $medical_history_notes
            );

            if ($success) {
                $_SESSION['name'] = $name;
                $message = "Profile updated successfully.";
            } else {
                $error = "Failed to update profile.";
            }
        }
    }

    $profile = $this->model->getFullPatientProfile($_SESSION['user_id']);

    if (!$profile) {
        die("Patient profile not found.");
    }

    include 'views/Patient/profile.php';
}  
 
public function dependents() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        header("Location: index.php");
        exit();
    }

    $patient = $this->model->getPatientByUserId($_SESSION['user_id']);

    if (!$patient) {
        die("Patient profile not found.");
    }

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $date_of_birth = $_POST['date_of_birth'] ?? '';
        $relationship = trim($_POST['relationship'] ?? '');
        $blood_group = trim($_POST['blood_group'] ?? '');

        if (
            empty($name) ||
            empty($date_of_birth) ||
            empty($relationship) ||
            empty($blood_group)
        ) {
            $error = "Please fill all required fields.";
        } elseif ($date_of_birth > date('Y-m-d')) {
            $error = "Date of birth cannot be in the future.";
        } else {
            $success = $this->model->addDependent(
                $patient['id'],
                $name,
                $date_of_birth,
                $relationship,
                $blood_group
            );

            if ($success) {
                $message = "Dependent added successfully.";
            } else {
                $error = "Failed to add dependent.";
            }
        }
    }

    $dependents = $this->model->getDependents($patient['id']);

    include 'views/Patient/dependents.php';
}

public function delete_dependent() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        header("Location: index.php");
        exit();
    }

    $dependent_id = $_GET['id'] ?? null;

    if (!$dependent_id) {
        die("Dependent ID missing.");
    }

    $patient = $this->model->getPatientByUserId($_SESSION['user_id']);

    if (!$patient) {
        die("Patient profile not found.");
    }

    $dependent = $this->model->getDependentById($dependent_id);

    if (!$dependent) {
        die("Dependent not found.");
    }

    if ($dependent['primary_patient_id'] != $patient['id']) {
        die("You are not allowed to delete this dependent.");
    }

    $success = $this->model->deleteDependent($dependent_id, $patient['id']);

    if ($success) {
        header("Location: Dashboard.php?action=dependents&deleted=1");
        exit();
    } else {
        die("Failed to delete dependent.");
    }
}

  public function consultation_notes() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        header("Location: index.php");
        exit();
    }

    $patient = $this->model->getPatientByUserId($_SESSION['user_id']);

    if (!$patient) {
        die("Patient profile not found.");
    }

    $notes = $this->model->getConsultationNotes($patient['id']);

    include 'views/Patient/consultation_notes.php';
}
  public function view_note() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        header("Location: index.php");
        exit();
    }

    $note_id = $_GET['id'] ?? null;

    if (!$note_id) {
        die("Note ID missing.");
    }

    $patient = $this->model->getPatientByUserId($_SESSION['user_id']);

    if (!$patient) {
        die("Patient profile not found.");
    }

    $note = $this->model->getConsultationNoteById($note_id, $patient['id']);

    if (!$note) {
        die("Consultation note not found or access denied.");
    }

    include 'views/Patient/view_note.php';
}

public function reschedule_appointment() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
        header("Location: index.php");
        exit();
    }

    $appointment_id = $_GET['id'] ?? null;

    if (!$appointment_id) {
        die("Appointment ID missing.");
    }

    $patient = $this->model->getPatientByUserId($_SESSION['user_id']);

    if (!$patient) {
        die("Patient profile not found.");
    }

    $appointment = $this->model->getPatientAppointmentById($appointment_id, $patient['id']);

    if (!$appointment) {
        die("Appointment not found or access denied.");
    }

    if (!in_array($appointment['status'], ['pending', 'confirmed'])) {
        die("Only pending or confirmed appointments can be rescheduled.");
    }

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_date = $_POST['new_date'] ?? '';
        $new_time = $_POST['new_time'] ?? '';

        if (empty($new_date) || empty($new_time)) {
            $error = "Please select new date and time.";
        } elseif ($new_date < date('Y-m-d')) {
            $error = "New appointment date cannot be in the past.";
        } elseif ($this->model->isSlotAlreadyBooked($appointment['doctor_id'], $new_date, $new_time)) {
            $error = "This time slot is already booked. Please choose another time.";
        } else {
            $success = $this->model->rescheduleAppointment(
                $appointment_id,
                $patient['id'],
                $new_date,
                $new_time
            );

            if ($success) {
                header("Location: Dashboard.php?rescheduled=1");
                exit();
            } else {
                $error = "Failed to reschedule appointment.";
            }
        }
    }

    include 'views/Patient/reschedule_appointment.php';
}
  
}

?>