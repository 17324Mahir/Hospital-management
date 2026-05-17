<?php

class AdminModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAdminByUserId($user_id) {
        $sql = "SELECT 
                    id,
                    name,
                    email,
                    mobile,
                    phone,
                    role,
                    status,
                    is_active,
                    created_at
                FROM users
                WHERE id = ?
                AND role = 'admin'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function countUsersByRole($role) {
        $sql = "SELECT COUNT(*) AS total
                FROM users
                WHERE role = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("s", $role);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getTotalAppointments() {
        $sql = "SELECT COUNT(*) AS total FROM appointments";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getTodayAppointments() {
        $sql = "SELECT COUNT(*) AS total
                FROM appointments
                WHERE appointment_date = CURDATE()";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getPendingDoctorCount() {
        $sql = "SELECT COUNT(*) AS total
                FROM doctors
                WHERE is_approved = 0
                OR status = 'pending'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getTotalRevenue() {
        $sql = "SELECT SUM(amount) AS total
                FROM billing
                WHERE payment_status = 'paid'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        return $row['total'] ?? 0;
    }

    public function getPendingBillsCount() {
        $sql = "SELECT COUNT(*) AS total
                FROM billing
                WHERE payment_status = 'pending'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getRecentAppointments($limit = 5) {
        $sql = "SELECT 
                    a.id AS appointment_id,
                    a.appointment_date,
                    a.appointment_time,
                    a.status,
                    a.booked_by,
                    u_patient.name AS patient_name,
                    u_doctor.name AS doctor_name,
                    d.specialization
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users u_patient ON p.user_id = u_patient.id
                JOIN doctors d ON a.doctor_id = d.id
                JOIN users u_doctor ON d.user_id = u_doctor.id
                ORDER BY a.id DESC
                LIMIT ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result();
    }

    public function getAllUsers() {
    $sql = "SELECT 
                id,
                name,
                email,
                mobile,
                phone,
                role,
                status,
                is_active,
                created_at
            FROM users
            ORDER BY role ASC, name ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getUserById($user_id) {
    $sql = "SELECT 
                id,
                name,
                email,
                mobile,
                phone,
                role,
                status,
                is_active,
                created_at
            FROM users
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function updateUserActiveStatus($user_id, $is_active) {
    $status = $is_active == 1 ? "active" : "inactive";

    $sql = "UPDATE users
            SET is_active = ?,
                status = ?
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("isi", $is_active, $status, $user_id);

    return $stmt->execute();
}

public function getPendingDoctors() {
    $sql = "SELECT 
                d.id AS doctor_id,
                d.user_id,
                d.specialization,
                d.bio,
                d.consultation_fee,
                d.license_number,
                d.experience_years,
                d.is_approved,
                d.status,
                u.name,
                u.email,
                u.mobile,
                u.created_at
            FROM doctors d
            JOIN users u ON d.user_id = u.id
            WHERE d.is_approved = 0
            OR d.status = 'pending'
            ORDER BY u.created_at DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getDoctorById($doctor_id) {
    $sql = "SELECT 
                d.id AS doctor_id,
                d.user_id,
                d.specialization,
                d.bio,
                d.consultation_fee,
                d.license_number,
                d.experience_years,
                d.is_approved,
                d.status,
                u.name,
                u.email,
                u.mobile
            FROM doctors d
            JOIN users u ON d.user_id = u.id
            WHERE d.id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function approveDoctor($doctor_id) {
    $status = "approved";
    $is_approved = 1;

    $sql = "UPDATE doctors
            SET is_approved = ?,
                status = ?
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("isi", $is_approved, $status, $doctor_id);

    return $stmt->execute();
}

public function rejectDoctor($doctor_id) {
    $status = "rejected";
    $is_approved = 0;

    $sql = "UPDATE doctors
            SET is_approved = ?,
                status = ?
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("isi", $is_approved, $status, $doctor_id);

    return $stmt->execute();
}

public function getAllDoctors() {
    $sql = "SELECT 
                d.id AS doctor_id,
                d.user_id,
                d.specialization,
                d.bio,
                d.consultation_fee,
                d.license_number,
                d.experience_years,
                d.is_approved,
                d.status AS doctor_status,

                u.name,
                u.email,
                u.mobile,
                u.phone,
                u.status AS user_status,
                u.is_active,
                u.created_at
            FROM doctors d
            JOIN users u ON d.user_id = u.id
            ORDER BY u.name ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function updateDoctorUserStatus($doctor_id, $is_active) {
    $doctor = $this->getDoctorById($doctor_id);

    if (!$doctor) {
        return false;
    }

    $status = $is_active == 1 ? "active" : "inactive";

    $sql = "UPDATE users
            SET is_active = ?,
                status = ?
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("isi", $is_active, $status, $doctor['user_id']);

    return $stmt->execute();
}

public function updateDoctorDetails(
    $doctor_id,
    $name,
    $mobile,
    $specialization,
    $consultation_fee,
    $license_number,
    $experience_years,
    $bio
) {
    $doctor = $this->getDoctorById($doctor_id);

    if (!$doctor) {
        return false;
    }

    $this->conn->begin_transaction();

    try {
        $user_sql = "UPDATE users
                     SET name = ?,
                         mobile = ?,
                         phone = ?
                     WHERE id = ?";

        $user_stmt = $this->conn->prepare($user_sql);

        if (!$user_stmt) {
            throw new Exception("User update prepare failed: " . $this->conn->error);
        }

        $user_stmt->bind_param(
            "sssi",
            $name,
            $mobile,
            $mobile,
            $doctor['user_id']
        );

        $user_stmt->execute();

        $doctor_sql = "UPDATE doctors
                       SET specialization = ?,
                           consultation_fee = ?,
                           license_number = ?,
                           experience_years = ?,
                           bio = ?
                       WHERE id = ?";

        $doctor_stmt = $this->conn->prepare($doctor_sql);

        if (!$doctor_stmt) {
            throw new Exception("Doctor update prepare failed: " . $this->conn->error);
        }

        $doctor_stmt->bind_param(
            "sdsisi",
            $specialization,
            $consultation_fee,
            $license_number,
            $experience_years,
            $bio,
            $doctor_id
        );

        $doctor_stmt->execute();

        $this->conn->commit();

        return true;

    } catch (Exception $e) {
        $this->conn->rollback();
        die($e->getMessage());
    }
}
public function getAllPatients() {
    $sql = "SELECT 
                p.id AS patient_id,
                p.user_id,
                p.date_of_birth,
                p.blood_group,
                p.gender,
                p.address,
                p.emergency_contact,
                p.emergency_contact_name,
                p.emergency_contact_phone,
                p.medical_history_notes,

                u.name,
                u.email,
                u.mobile,
                u.phone,
                u.status AS user_status,
                u.is_active,
                u.created_at
            FROM patients p
            JOIN users u ON p.user_id = u.id
            ORDER BY u.name ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getPatientById($patient_id) {
    $sql = "SELECT 
                p.id AS patient_id,
                p.user_id,
                p.date_of_birth,
                p.blood_group,
                p.gender,
                p.address,
                p.emergency_contact,
                p.emergency_contact_name,
                p.emergency_contact_phone,
                p.medical_history_notes,

                u.name,
                u.email,
                u.mobile,
                u.phone,
                u.status AS user_status,
                u.is_active,
                u.created_at
            FROM patients p
            JOIN users u ON p.user_id = u.id
            WHERE p.id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function updatePatientUserStatus($patient_id, $is_active) {
    $patient = $this->getPatientById($patient_id);

    if (!$patient) {
        return false;
    }

    $status = $is_active == 1 ? "active" : "inactive";

    $sql = "UPDATE users
            SET is_active = ?,
                status = ?
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("isi", $is_active, $status, $patient['user_id']);

    return $stmt->execute();
}

public function updatePatientDetails(
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
) {
    $patient = $this->getPatientById($patient_id);

    if (!$patient) {
        return false;
    }

    $this->conn->begin_transaction();

    try {
        $user_sql = "UPDATE users
                     SET name = ?,
                         mobile = ?,
                         phone = ?
                     WHERE id = ?";

        $user_stmt = $this->conn->prepare($user_sql);

        if (!$user_stmt) {
            throw new Exception("User update prepare failed: " . $this->conn->error);
        }

        $user_stmt->bind_param(
            "sssi",
            $name,
            $mobile,
            $mobile,
            $patient['user_id']
        );

        $user_stmt->execute();

        $patient_sql = "UPDATE patients
                        SET date_of_birth = ?,
                            blood_group = ?,
                            gender = ?,
                            address = ?,
                            emergency_contact = ?,
                            emergency_contact_name = ?,
                            emergency_contact_phone = ?,
                            medical_history_notes = ?
                        WHERE id = ?";

        $patient_stmt = $this->conn->prepare($patient_sql);

        if (!$patient_stmt) {
            throw new Exception("Patient update prepare failed: " . $this->conn->error);
        }

        $patient_stmt->bind_param(
            "ssssssssi",
            $date_of_birth,
            $blood_group,
            $gender,
            $address,
            $emergency_contact_phone,
            $emergency_contact_name,
            $emergency_contact_phone,
            $medical_history_notes,
            $patient_id
        );

        $patient_stmt->execute();

        $this->conn->commit();

        return true;

    } catch (Exception $e) {
        $this->conn->rollback();
        die($e->getMessage());
    }
}

public function getAllReceptionists() {
    $sql = "SELECT 
                id,
                name,
                email,
                mobile,
                phone,
                role,
                status,
                is_active,
                created_at
            FROM users
            WHERE role = 'receptionist'
            ORDER BY name ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getReceptionistById($user_id) {
    $sql = "SELECT 
                id,
                name,
                email,
                mobile,
                phone,
                role,
                status,
                is_active,
                created_at
            FROM users
            WHERE id = ?
            AND role = 'receptionist'";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function updateReceptionistUserStatus($user_id, $is_active) {
    $status = $is_active == 1 ? "active" : "inactive";

    $sql = "UPDATE users
            SET is_active = ?,
                status = ?
            WHERE id = ?
            AND role = 'receptionist'";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("isi", $is_active, $status, $user_id);

    return $stmt->execute();
}

public function updateReceptionistDetails($user_id, $name, $mobile) {
    $sql = "UPDATE users
            SET name = ?,
                mobile = ?,
                phone = ?
            WHERE id = ?
            AND role = 'receptionist'";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("sssi", $name, $mobile, $mobile, $user_id);

    return $stmt->execute();
}
public function getAllSpecializations() {
    $sql = "SELECT *
            FROM specializations
            ORDER BY name ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getSpecializationById($id) {
    $sql = "SELECT *
            FROM specializations
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function specializationNameExists($name, $exclude_id = null) {
    if ($exclude_id) {
        $sql = "SELECT id
                FROM specializations
                WHERE name = ?
                AND id != ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("si", $name, $exclude_id);
    } else {
        $sql = "SELECT id
                FROM specializations
                WHERE name = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("s", $name);
    }

    $stmt->execute();

    return $stmt->get_result()->num_rows > 0;
}

public function addSpecialization($name, $description, $status) {
    $sql = "INSERT INTO specializations
            (name, description, status)
            VALUES (?, ?, ?)";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("sss", $name, $description, $status);

    return $stmt->execute();
}

public function updateSpecialization($id, $name, $description, $status) {
    $sql = "UPDATE specializations
            SET name = ?,
                description = ?,
                status = ?
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("sssi", $name, $description, $status, $id);

    return $stmt->execute();
}

public function deleteSpecialization($id) {
    $sql = "DELETE FROM specializations
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}
public function getAllAnnouncements() {
    $sql = "SELECT 
                a.id,
                a.author_id,
                a.title,
                a.body,
                a.target_role,
                a.published_at,
                u.name AS author_name
            FROM announcements a
            LEFT JOIN users u ON a.author_id = u.id
            ORDER BY a.published_at DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getAnnouncementById($id) {
    $sql = "SELECT *
            FROM announcements
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function addAnnouncement($author_id, $title, $body, $target_role) {
    $sql = "INSERT INTO announcements
            (author_id, title, body, target_role)
            VALUES (?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("isss", $author_id, $title, $body, $target_role);

    return $stmt->execute();
}

public function updateAnnouncement($id, $title, $body, $target_role) {
    $sql = "UPDATE announcements
            SET title = ?,
                body = ?,
                target_role = ?
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("sssi", $title, $body, $target_role, $id);

    return $stmt->execute();
}

public function deleteAnnouncement($id) {
    $sql = "DELETE FROM announcements
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

public function getAppointmentPolicy() {
    $sql = "SELECT *
            FROM appointment_policies
            ORDER BY id ASC
            LIMIT 1";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    $policy = $stmt->get_result()->fetch_assoc();

    if (!$policy) {
        $this->createDefaultAppointmentPolicy();

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $policy = $stmt->get_result()->fetch_assoc();
    }

    return $policy;
}

public function createDefaultAppointmentPolicy() {
    $sql = "INSERT INTO appointment_policies
            (minimum_cancellation_notice_hours, maximum_advance_booking_days, default_consultation_fee)
            VALUES (24, 30, 500.00)";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    return $stmt->execute();
}

public function updateAppointmentPolicy(
    $id,
    $minimum_cancellation_notice_hours,
    $maximum_advance_booking_days,
    $default_consultation_fee
) {
    $sql = "UPDATE appointment_policies
            SET minimum_cancellation_notice_hours = ?,
                maximum_advance_booking_days = ?,
                default_consultation_fee = ?
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param(
        "iidi",
        $minimum_cancellation_notice_hours,
        $maximum_advance_booking_days,
        $default_consultation_fee,
        $id
    );

    return $stmt->execute();
}

public function getUserRoleSummary() {
    $sql = "SELECT 
                role,
                COUNT(*) AS total,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS active_count,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) AS inactive_count
            FROM users
            GROUP BY role
            ORDER BY role ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getAppointmentStatusSummary() {
    $sql = "SELECT 
                status,
                COUNT(*) AS total
            FROM appointments
            GROUP BY status
            ORDER BY status ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getDoctorWiseAppointmentSummary() {
    $sql = "SELECT 
                d.id AS doctor_id,
                u.name AS doctor_name,
                d.specialization,
                COUNT(a.id) AS total_appointments,
                SUM(CASE WHEN a.status = 'completed' THEN 1 ELSE 0 END) AS completed_count,
                SUM(CASE WHEN a.status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_count,
                SUM(CASE WHEN a.status = 'no_show' THEN 1 ELSE 0 END) AS no_show_count
            FROM doctors d
            JOIN users u ON d.user_id = u.id
            LEFT JOIN appointments a ON d.id = a.doctor_id
            GROUP BY d.id, u.name, d.specialization
            ORDER BY total_appointments DESC, u.name ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getMonthlyAppointmentSummary() {
    $sql = "SELECT 
                DATE_FORMAT(appointment_date, '%Y-%m') AS report_month,
                COUNT(*) AS total_appointments,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_count,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_count,
                SUM(CASE WHEN status = 'no_show' THEN 1 ELSE 0 END) AS no_show_count
            FROM appointments
            WHERE appointment_date IS NOT NULL
            GROUP BY DATE_FORMAT(appointment_date, '%Y-%m')
            ORDER BY report_month DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getRecentAppointmentsReport($limit = 20) {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status,
                a.booked_by,
                u_patient.name AS patient_name,
                u_doctor.name AS doctor_name,
                d.specialization
            FROM appointments a
            LEFT JOIN patients p ON a.patient_id = p.id
            LEFT JOIN users u_patient ON p.user_id = u_patient.id
            LEFT JOIN doctors d ON a.doctor_id = d.id
            LEFT JOIN users u_doctor ON d.user_id = u_doctor.id
            ORDER BY a.id DESC
            LIMIT ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $limit);
    $stmt->execute();

    return $stmt->get_result();
}

public function getBillingSummary() {
    $sql = "SELECT 
                COUNT(*) AS total_bills,
                SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) AS paid_bills,
                SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) AS pending_bills,
                SUM(CASE WHEN payment_status = 'paid' THEN amount ELSE 0 END) AS total_revenue,
                SUM(CASE WHEN payment_status = 'pending' THEN amount ELSE 0 END) AS pending_amount
            FROM billing";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function getPaymentMethodSummary() {
    $sql = "SELECT 
                payment_method,
                COUNT(*) AS total_payments,
                SUM(amount) AS total_amount
            FROM billing
            WHERE payment_status = 'paid'
            GROUP BY payment_method
            ORDER BY total_amount DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getDoctorWiseRevenue() {
    $sql = "SELECT 
                d.id AS doctor_id,
                u.name AS doctor_name,
                d.specialization,
                COUNT(b.id) AS total_bills,
                SUM(CASE WHEN b.payment_status = 'paid' THEN b.amount ELSE 0 END) AS paid_revenue,
                SUM(CASE WHEN b.payment_status = 'pending' THEN b.amount ELSE 0 END) AS pending_revenue
            FROM doctors d
            JOIN users u ON d.user_id = u.id
            LEFT JOIN appointments a ON d.id = a.doctor_id
            LEFT JOIN billing b ON a.id = b.appointment_id
            GROUP BY d.id, u.name, d.specialization
            ORDER BY paid_revenue DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getMonthlyRevenueSummary() {
    $sql = "SELECT 
                DATE_FORMAT(COALESCE(b.paid_at, a.appointment_date), '%Y-%m') AS report_month,
                COUNT(b.id) AS total_bills,
                SUM(CASE WHEN b.payment_status = 'paid' THEN b.amount ELSE 0 END) AS paid_revenue,
                SUM(CASE WHEN b.payment_status = 'pending' THEN b.amount ELSE 0 END) AS pending_revenue
            FROM billing b
            LEFT JOIN appointments a ON b.appointment_id = a.id
            GROUP BY DATE_FORMAT(COALESCE(b.paid_at, a.appointment_date), '%Y-%m')
            ORDER BY report_month DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getDetailedBillingRecords() {
    $sql = "SELECT 
                b.id AS billing_id,
                b.appointment_id,
                b.patient_id,
                b.amount,
                b.payment_method,
                b.payment_status,
                b.paid_at,

                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status AS appointment_status,

                u_patient.name AS patient_name,
                u_patient.mobile AS patient_mobile,

                u_doctor.name AS doctor_name,
                d.specialization
            FROM billing b
            LEFT JOIN appointments a ON b.appointment_id = a.id
            LEFT JOIN patients p ON b.patient_id = p.id
            LEFT JOIN users u_patient ON p.user_id = u_patient.id
            LEFT JOIN doctors d ON a.doctor_id = d.id
            LEFT JOIN users u_doctor ON d.user_id = u_doctor.id
            ORDER BY b.id DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getAllComplaints() {
    $sql = "SELECT 
                pc.id,
                pc.patient_id,
                pc.subject,
                pc.message,
                pc.status,
                pc.admin_response,
                pc.created_at,
                pc.resolved_at,

                u.name AS patient_name,
                u.email AS patient_email,
                u.mobile AS patient_mobile
            FROM patient_complaints pc
            JOIN patients p ON pc.patient_id = p.id
            JOIN users u ON p.user_id = u.id
            ORDER BY 
                CASE 
                    WHEN pc.status = 'open' THEN 1
                    ELSE 2
                END,
                pc.created_at DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getComplaintById($id) {
    $sql = "SELECT 
                pc.id,
                pc.patient_id,
                pc.subject,
                pc.message,
                pc.status,
                pc.admin_response,
                pc.created_at,
                pc.resolved_at,

                u.name AS patient_name,
                u.email AS patient_email,
                u.mobile AS patient_mobile
            FROM patient_complaints pc
            JOIN patients p ON pc.patient_id = p.id
            JOIN users u ON p.user_id = u.id
            WHERE pc.id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function respondComplaint($id, $admin_response, $status) {
    $resolved_at = null;

    if ($status === 'resolved') {
        $resolved_at = date('Y-m-d H:i:s');
    }

    $sql = "UPDATE patient_complaints
            SET admin_response = ?,
                status = ?,
                resolved_at = ?
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("sssi", $admin_response, $status, $resolved_at, $id);

    return $stmt->execute();
}
public function getAllActivityLogs() {
    $sql = "SELECT 
                id,
                receptionist_name,
                action_performed,
                created_at
            FROM receptionist_logs
            ORDER BY created_at DESC, id DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function addActivityLog($name, $action) {
    $sql = "INSERT INTO receptionist_logs
            (receptionist_name, action_performed)
            VALUES (?, ?)";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ss", $name, $action);

    return $stmt->execute();
}

public function clearActivityLogs() {
    $sql = "DELETE FROM receptionist_logs";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    return $stmt->execute();
}


}

?>