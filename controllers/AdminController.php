<?php

require_once 'models/AdminModel.php';

class AdminController {
    private $model;

    public function __construct($conn) {
        $this->model = new AdminModel($conn);
    }

    private function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php");
            exit();
        }
    }

    public function dashboard() {
        $this->checkAdmin();

        $admin = $this->model->getAdminByUserId($_SESSION['user_id']);

        if (!$admin) {
            die("Admin profile not found.");
        }

        $totalAdmins = $this->model->countUsersByRole('admin');
        $totalDoctors = $this->model->countUsersByRole('doctor');
        $totalPatients = $this->model->countUsersByRole('patient');
        $totalReceptionists = $this->model->countUsersByRole('receptionist');

        $totalAppointments = $this->model->getTotalAppointments();
        $todayAppointments = $this->model->getTodayAppointments();
        $pendingDoctors = $this->model->getPendingDoctorCount();
        $totalRevenue = $this->model->getTotalRevenue();
        $pendingBills = $this->model->getPendingBillsCount();

        $recentAppointments = $this->model->getRecentAppointments(5);

        include 'views/Admin/Admindashboard.php';
    }

    public function manage_users() {
        $this->checkAdmin();

        $message = "";
        $error = "";

        if (isset($_GET['updated']) && $_GET['updated'] == 1) {
            $message = "User status updated successfully.";
        }

        $users = $this->model->getAllUsers();

        include 'views/Admin/manage_users.php';
    }

    public function toggle_user_status() {
        $this->checkAdmin();

        $user_id = $_GET['id'] ?? null;

        if (!$user_id) {
            die("User ID missing.");
        }

        if ($user_id == $_SESSION['user_id']) {
            die("You cannot deactivate your own admin account.");
        }

        $user = $this->model->getUserById($user_id);

        if (!$user) {
            die("User not found.");
        }

        $newStatus = $user['is_active'] == 1 ? 0 : 1;

        $success = $this->model->updateUserActiveStatus($user_id, $newStatus);

        if ($success) {
            header("Location: Dashboard.php?action=manage_users&updated=1");
            exit();
        }

        die("Failed to update user status.");
    }

    public function doctor_approvals() {
        $this->checkAdmin();

        $message = "";
        $error = "";

        if (isset($_GET['approved']) && $_GET['approved'] == 1) {
            $message = "Doctor approved successfully.";
        }

        if (isset($_GET['rejected']) && $_GET['rejected'] == 1) {
            $message = "Doctor rejected successfully.";
        }

        $pendingDoctors = $this->model->getPendingDoctors();

        include 'views/Admin/doctor_approvals.php';
    }

    public function approve_doctor() {
        $this->checkAdmin();

        $doctor_id = $_GET['id'] ?? null;

        if (!$doctor_id) {
            die("Doctor ID missing.");
        }

        $doctor = $this->model->getDoctorById($doctor_id);

        if (!$doctor) {
            die("Doctor not found.");
        }

        $success = $this->model->approveDoctor($doctor_id);

        if ($success) {
            header("Location: Dashboard.php?action=doctor_approvals&approved=1");
            exit();
        }

        die("Failed to approve doctor.");
    }

    public function reject_doctor() {
        $this->checkAdmin();

        $doctor_id = $_GET['id'] ?? null;

        if (!$doctor_id) {
            die("Doctor ID missing.");
        }

        $doctor = $this->model->getDoctorById($doctor_id);

        if (!$doctor) {
            die("Doctor not found.");
        }

        $success = $this->model->rejectDoctor($doctor_id);

        if ($success) {
            header("Location: Dashboard.php?action=doctor_approvals&rejected=1");
            exit();
        }

        die("Failed to reject doctor.");
    }

    public function manage_doctors() {
        $this->checkAdmin();

        $message = "";
        $error = "";

        if (isset($_GET['updated']) && $_GET['updated'] == 1) {
            $message = "Doctor updated successfully.";
        }

        if (isset($_GET['status_updated']) && $_GET['status_updated'] == 1) {
            $message = "Doctor account status updated successfully.";
        }

        if (isset($_GET['approved']) && $_GET['approved'] == 1) {
            $message = "Doctor approved successfully.";
        }

        if (isset($_GET['rejected']) && $_GET['rejected'] == 1) {
            $message = "Doctor rejected successfully.";
        }

        $doctors = $this->model->getAllDoctors();

        include 'views/Admin/manage_doctors.php';
    }

    public function toggle_doctor_user_status() {
        $this->checkAdmin();

        $doctor_id = $_GET['id'] ?? null;

        if (!$doctor_id) {
            die("Doctor ID missing.");
        }

        $doctor = $this->model->getDoctorById($doctor_id);

        if (!$doctor) {
            die("Doctor not found.");
        }

        $newStatus = $doctor['is_active'] == 1 ? 0 : 1;

        $success = $this->model->updateDoctorUserStatus($doctor_id, $newStatus);

        if ($success) {
            header("Location: Dashboard.php?action=manage_doctors&status_updated=1");
            exit();
        }

        die("Failed to update doctor account status.");
    }

    public function edit_doctor() {
        $this->checkAdmin();

        $doctor_id = $_GET['id'] ?? null;

        if (!$doctor_id) {
            die("Doctor ID missing.");
        }

        $doctor = $this->model->getDoctorById($doctor_id);

        if (!$doctor) {
            die("Doctor not found.");
        }

        $message = "";
        $error = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $mobile = trim($_POST['mobile'] ?? '');
            $specialization = trim($_POST['specialization'] ?? '');
            $consultation_fee = $_POST['consultation_fee'] ?? '';
            $license_number = trim($_POST['license_number'] ?? '');
            $experience_years = $_POST['experience_years'] ?? '';
            $bio = trim($_POST['bio'] ?? '');

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
                $success = $this->model->updateDoctorDetails(
                    $doctor_id,
                    $name,
                    $mobile,
                    $specialization,
                    $consultation_fee,
                    $license_number,
                    $experience_years,
                    $bio
                );

                if ($success) {
                    header("Location: Dashboard.php?action=manage_doctors&updated=1");
                    exit();
                }

                $error = "Failed to update doctor.";
            }
        }

        include 'views/Admin/edit_doctor.php';
    }

    public function manage_approve_doctor() {
        $this->checkAdmin();

        $doctor_id = $_GET['id'] ?? null;

        if (!$doctor_id) {
            die("Doctor ID missing.");
        }

        $doctor = $this->model->getDoctorById($doctor_id);

        if (!$doctor) {
            die("Doctor not found.");
        }

        $success = $this->model->approveDoctor($doctor_id);

        if ($success) {
            header("Location: Dashboard.php?action=manage_doctors&approved=1");
            exit();
        }

        die("Failed to approve doctor.");
    }

    public function manage_reject_doctor() {
        $this->checkAdmin();

        $doctor_id = $_GET['id'] ?? null;

        if (!$doctor_id) {
            die("Doctor ID missing.");
        }

        $doctor = $this->model->getDoctorById($doctor_id);

        if (!$doctor) {
            die("Doctor not found.");
        }

        $success = $this->model->rejectDoctor($doctor_id);

        if ($success) {
            header("Location: Dashboard.php?action=manage_doctors&rejected=1");
            exit();
        }

        die("Failed to reject doctor.");
    }

    public function manage_patients() {
        $this->checkAdmin();

        $message = "";
        $error = "";

        if (isset($_GET['updated']) && $_GET['updated'] == 1) {
            $message = "Patient updated successfully.";
        }

        if (isset($_GET['status_updated']) && $_GET['status_updated'] == 1) {
            $message = "Patient account status updated successfully.";
        }

        $patients = $this->model->getAllPatients();

        include 'views/Admin/manage_patients.php';
    }

    public function toggle_patient_user_status() {
        $this->checkAdmin();

        $patient_id = $_GET['id'] ?? null;

        if (!$patient_id) {
            die("Patient ID missing.");
        }

        $patient = $this->model->getPatientById($patient_id);

        if (!$patient) {
            die("Patient not found.");
        }

        $newStatus = $patient['is_active'] == 1 ? 0 : 1;

        $success = $this->model->updatePatientUserStatus($patient_id, $newStatus);

        if ($success) {
            header("Location: Dashboard.php?action=manage_patients&status_updated=1");
            exit();
        }

        die("Failed to update patient account status.");
    }

    public function edit_patient() {
        $this->checkAdmin();

        $patient_id = $_GET['id'] ?? null;

        if (!$patient_id) {
            die("Patient ID missing.");
        }

        $patient = $this->model->getPatientById($patient_id);

        if (!$patient) {
            die("Patient not found.");
        }

        $message = "";
        $error = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $mobile = trim($_POST['mobile'] ?? '');
            $date_of_birth = $_POST['date_of_birth'] ?? '';
            $gender = $_POST['gender'] ?? '';
            $blood_group = $_POST['blood_group'] ?? '';
            $address = trim($_POST['address'] ?? '');
            $emergency_contact_name = trim($_POST['emergency_contact_name'] ?? '');
            $emergency_contact_phone = trim($_POST['emergency_contact_phone'] ?? '');
            $medical_history_notes = trim($_POST['medical_history_notes'] ?? '');

            if (
                empty($name) ||
                empty($mobile) ||
                empty($date_of_birth) ||
                empty($gender) ||
                empty($blood_group) ||
                empty($address) ||
                empty($emergency_contact_phone)
            ) {
                $error = "Please fill all required fields.";
            } elseif ($date_of_birth > date('Y-m-d')) {
                $error = "Date of birth cannot be in the future.";
            } else {
                $success = $this->model->updatePatientDetails(
                    $patient_id,
                    $name,
                    $mobile,
                    $date_of_birth,
                    $gender,
                    $blood_group,
                    $address,
                    $emergency_contact_name,
                    $emergency_contact_phone,
                    $medical_history_notes
                );

                if ($success) {
                    header("Location: Dashboard.php?action=manage_patients&updated=1");
                    exit();
                }

                $error = "Failed to update patient.";
            }
        }

        include 'views/Admin/edit_patient.php';
    }

    public function manage_receptionists() {
        $this->checkAdmin();

        $message = "";
        $error = "";

        if (isset($_GET['updated']) && $_GET['updated'] == 1) {
            $message = "Receptionist updated successfully.";
        }

        if (isset($_GET['status_updated']) && $_GET['status_updated'] == 1) {
            $message = "Receptionist account status updated successfully.";
        }

        $receptionists = $this->model->getAllReceptionists();

        include 'views/Admin/manage_receptionists.php';
    }

    public function toggle_receptionist_user_status() {
        $this->checkAdmin();

        $user_id = $_GET['id'] ?? null;

        if (!$user_id) {
            die("Receptionist user ID missing.");
        }

        $receptionist = $this->model->getReceptionistById($user_id);

        if (!$receptionist) {
            die("Receptionist not found.");
        }

        $newStatus = $receptionist['is_active'] == 1 ? 0 : 1;

        $success = $this->model->updateReceptionistUserStatus($user_id, $newStatus);

        if ($success) {
            header("Location: Dashboard.php?action=manage_receptionists&status_updated=1");
            exit();
        }

        die("Failed to update receptionist account status.");
    }

    public function edit_receptionist() {
        $this->checkAdmin();

        $user_id = $_GET['id'] ?? null;

        if (!$user_id) {
            die("Receptionist user ID missing.");
        }

        $receptionist = $this->model->getReceptionistById($user_id);

        if (!$receptionist) {
            die("Receptionist not found.");
        }

        $message = "";
        $error = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $mobile = trim($_POST['mobile'] ?? '');

            if (empty($name) || empty($mobile)) {
                $error = "Please fill all required fields.";
            } else {
                $success = $this->model->updateReceptionistDetails(
                    $user_id,
                    $name,
                    $mobile
                );

                if ($success) {
                    header("Location: Dashboard.php?action=manage_receptionists&updated=1");
                    exit();
                }

                $error = "Failed to update receptionist.";
            }
        }

        include 'views/Admin/edit_receptionist.php';
    }

    public function specializations() {
    $this->checkAdmin();

    $message = "";
    $error = "";

    if (isset($_GET['added']) && $_GET['added'] == 1) {
        $message = "Specialization added successfully.";
    }

    if (isset($_GET['updated']) && $_GET['updated'] == 1) {
        $message = "Specialization updated successfully.";
    }

    if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
        $message = "Specialization deleted successfully.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'active';

        if (empty($name)) {
            $error = "Specialization name is required.";
        } elseif (!in_array($status, ['active', 'inactive'])) {
            $error = "Invalid status selected.";
        } elseif ($this->model->specializationNameExists($name)) {
            $error = "This specialization already exists.";
        } else {
            $success = $this->model->addSpecialization($name, $description, $status);

            if ($success) {
                header("Location: Dashboard.php?action=specializations&added=1");
                exit();
            } else {
                $error = "Failed to add specialization.";
            }
        }
    }

    $specializations = $this->model->getAllSpecializations();

    include 'views/Admin/specializations.php';
}

 public function edit_specialization() {
    $this->checkAdmin();

    $id = $_GET['id'] ?? null;

    if (!$id) {
        die("Specialization ID missing.");
    }

    $specialization = $this->model->getSpecializationById($id);

    if (!$specialization) {
        die("Specialization not found.");
    }

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'active';

        if (empty($name)) {
            $error = "Specialization name is required.";
        } elseif (!in_array($status, ['active', 'inactive'])) {
            $error = "Invalid status selected.";
        } elseif ($this->model->specializationNameExists($name, $id)) {
            $error = "This specialization name already exists.";
        } else {
            $success = $this->model->updateSpecialization($id, $name, $description, $status);

            if ($success) {
                header("Location: Dashboard.php?action=specializations&updated=1");
                exit();
            } else {
                $error = "Failed to update specialization.";
            }
        }
    }

    include 'views/Admin/edit_specialization.php';
}

public function delete_specialization() {
    $this->checkAdmin();

    $id = $_GET['id'] ?? null;

    if (!$id) {
        die("Specialization ID missing.");
    }

    $specialization = $this->model->getSpecializationById($id);

    if (!$specialization) {
        die("Specialization not found.");
    }

    $success = $this->model->deleteSpecialization($id);

    if ($success) {
        header("Location: Dashboard.php?action=specializations&deleted=1");
        exit();
    }

    die("Failed to delete specialization.");
}

public function announcements() {
    $this->checkAdmin();

    $message = "";
    $error = "";

    if (isset($_GET['added']) && $_GET['added'] == 1) {
        $message = "Announcement added successfully.";
    }

    if (isset($_GET['updated']) && $_GET['updated'] == 1) {
        $message = "Announcement updated successfully.";
    }

    if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
        $message = "Announcement deleted successfully.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $target_role = $_POST['target_role'] ?? 'all';

        if (empty($title) || empty($body)) {
            $error = "Title and message are required.";
        } elseif (!in_array($target_role, ['all', 'patient', 'doctor'])) {
            $error = "Invalid target role.";
        } else {
            $success = $this->model->addAnnouncement(
                $_SESSION['user_id'],
                $title,
                $body,
                $target_role
            );

            if ($success) {
                header("Location: Dashboard.php?action=announcements&added=1");
                exit();
            } else {
                $error = "Failed to add announcement.";
            }
        }
    }

    $announcements = $this->model->getAllAnnouncements();

    include 'views/Admin/announcements.php';
}
public function edit_announcement() {
    $this->checkAdmin();

    $id = $_GET['id'] ?? null;

    if (!$id) {
        die("Announcement ID missing.");
    }

    $announcement = $this->model->getAnnouncementById($id);

    if (!$announcement) {
        die("Announcement not found.");
    }

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $target_role = $_POST['target_role'] ?? 'all';

        if (empty($title) || empty($body)) {
            $error = "Title and message are required.";
        } elseif (!in_array($target_role, ['all', 'patient', 'doctor'])) {
            $error = "Invalid target role.";
        } else {
            $success = $this->model->updateAnnouncement(
                $id,
                $title,
                $body,
                $target_role
            );

            if ($success) {
                header("Location: Dashboard.php?action=announcements&updated=1");
                exit();
            } else {
                $error = "Failed to update announcement.";
            }
        }
    }

    include 'views/Admin/edit_announcement.php';
}
public function delete_announcement() {
    $this->checkAdmin();

    $id = $_GET['id'] ?? null;

    if (!$id) {
        die("Announcement ID missing.");
    }

    $announcement = $this->model->getAnnouncementById($id);

    if (!$announcement) {
        die("Announcement not found.");
    }

    $success = $this->model->deleteAnnouncement($id);

    if ($success) {
        header("Location: Dashboard.php?action=announcements&deleted=1");
        exit();
    }

    die("Failed to delete announcement.");
}

public function appointment_policies() {
    $this->checkAdmin();

    $message = "";
    $error = "";

    $policy = $this->model->getAppointmentPolicy();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $minimum_cancellation_notice_hours = $_POST['minimum_cancellation_notice_hours'] ?? '';
        $maximum_advance_booking_days = $_POST['maximum_advance_booking_days'] ?? '';
        $default_consultation_fee = $_POST['default_consultation_fee'] ?? '';

        if (
            $minimum_cancellation_notice_hours === '' ||
            $maximum_advance_booking_days === '' ||
            $default_consultation_fee === ''
        ) {
            $error = "Please fill all policy fields.";
        } elseif (!is_numeric($minimum_cancellation_notice_hours) || $minimum_cancellation_notice_hours < 0) {
            $error = "Minimum cancellation notice must be a valid positive number.";
        } elseif (!is_numeric($maximum_advance_booking_days) || $maximum_advance_booking_days < 1) {
            $error = "Maximum advance booking days must be at least 1.";
        } elseif (!is_numeric($default_consultation_fee) || $default_consultation_fee < 0) {
            $error = "Default consultation fee must be a valid positive number.";
        } else {
            $success = $this->model->updateAppointmentPolicy(
                $policy['id'],
                (int)$minimum_cancellation_notice_hours,
                (int)$maximum_advance_booking_days,
                (float)$default_consultation_fee
            );

            if ($success) {
                header("Location: Dashboard.php?action=appointment_policies&updated=1");
                exit();
            } else {
                $error = "Failed to update appointment policy.";
            }
        }
    }

    if (isset($_GET['updated']) && $_GET['updated'] == 1) {
        $message = "Appointment policies updated successfully.";
        $policy = $this->model->getAppointmentPolicy();
    }

    include 'views/Admin/appointment_policies.php';
}

public function system_reports() {
    $this->checkAdmin();

    $userRoleSummary = $this->model->getUserRoleSummary();
    $appointmentStatusSummary = $this->model->getAppointmentStatusSummary();
    $doctorWiseSummary = $this->model->getDoctorWiseAppointmentSummary();
    $monthlySummary = $this->model->getMonthlyAppointmentSummary();
    $recentAppointments = $this->model->getRecentAppointmentsReport(20);

    include 'views/Admin/system_reports.php';
}
public function billing_report() {
    $this->checkAdmin();

    $billingSummary = $this->model->getBillingSummary();
    $paymentMethodSummary = $this->model->getPaymentMethodSummary();
    $doctorWiseRevenue = $this->model->getDoctorWiseRevenue();
    $monthlyRevenue = $this->model->getMonthlyRevenueSummary();
    $billingRecords = $this->model->getDetailedBillingRecords();

    include 'views/Admin/billing_report.php';
}

public function complaints() {
    $this->checkAdmin();

    $message = "";
    $error = "";

    if (isset($_GET['responded']) && $_GET['responded'] == 1) {
        $message = "Complaint response saved successfully.";
    }

    $complaints = $this->model->getAllComplaints();

    include 'views/Admin/complaints.php';
}
public function respond_complaint() {
    $this->checkAdmin();

    $id = $_GET['id'] ?? null;

    if (!$id) {
        die("Complaint ID missing.");
    }

    $complaint = $this->model->getComplaintById($id);

    if (!$complaint) {
        die("Complaint not found.");
    }

    $message = "";
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $admin_response = trim($_POST['admin_response'] ?? '');
        $status = $_POST['status'] ?? 'open';

        if (empty($admin_response)) {
            $error = "Admin response is required.";
        } elseif (!in_array($status, ['open', 'resolved'])) {
            $error = "Invalid complaint status.";
        } else {
            $success = $this->model->respondComplaint(
                $id,
                $admin_response,
                $status
            );

            if ($success) {
                header("Location: Dashboard.php?action=complaints&responded=1");
                exit();
            } else {
                $error = "Failed to save complaint response.";
            }
        }
    }

    include 'views/Admin/respond_complaint.php';
}
public function activity_logs() {
    $this->checkAdmin();

    $message = "";
    $error = "";

    if (isset($_GET['cleared']) && $_GET['cleared'] == 1) {
        $message = "Activity logs cleared successfully.";
    }

    if (isset($_GET['sample_added']) && $_GET['sample_added'] == 1) {
        $message = "Sample activity log added successfully.";
    }

    $logs = $this->model->getAllActivityLogs();

    include 'views/Admin/activity_logs.php';
}
public function add_sample_log() {
    $this->checkAdmin();

    $name = $_SESSION['name'] ?? 'Admin';
    $action = "Admin tested activity log system.";

    $success = $this->model->addActivityLog($name, $action);

    if ($success) {
        header("Location: Dashboard.php?action=activity_logs&sample_added=1");
        exit();
    }

    die("Failed to add sample log.");
}
public function clear_activity_logs() {
    $this->checkAdmin();

    $success = $this->model->clearActivityLogs();

    if ($success) {
        header("Location: Dashboard.php?action=activity_logs&cleared=1");
        exit();
    }

    die("Failed to clear activity logs.");
}

}

?>