<?php

class ReceptionistModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getReceptionistByUserId($user_id) {
        $sql = "SELECT 
                    id,
                    name,
                    email,
                    mobile,
                    phone,
                    role,
                    profile_pic,
                    is_active,
                    status,
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

    public function getTodayTotalAppointments() {
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

    public function getTodayCheckedInAppointments() {
        $sql = "SELECT COUNT(*) AS total
                FROM appointments
                WHERE appointment_date = CURDATE()
                AND status = 'checked_in'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getTodayCompletedAppointments() {
        $sql = "SELECT COUNT(*) AS total
                FROM appointments
                WHERE appointment_date = CURDATE()
                AND status = 'completed'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getTodayCancelledAppointments() {
        $sql = "SELECT COUNT(*) AS total
                FROM appointments
                WHERE appointment_date = CURDATE()
                AND status = 'cancelled'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getTodayRevenue() {
        $sql = "SELECT SUM(b.amount) AS total
                FROM billing b
                JOIN appointments a ON b.appointment_id = a.id
                WHERE DATE(b.paid_at) = CURDATE()
                AND b.payment_status = 'paid'";

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
    public function getTodayAppointmentsGrouped() {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status,
                a.booked_by,
                a.checkin_time,

                p.id AS patient_id,
                u_patient.name AS patient_name,
                u_patient.mobile AS patient_mobile,

                dep.name AS dependent_name,
                dep.relationship,

                d.id AS doctor_id,
                d.specialization,
                u_doctor.name AS doctor_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u_patient ON p.user_id = u_patient.id
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u_doctor ON d.user_id = u_doctor.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.appointment_date = CURDATE()
            ORDER BY u_doctor.name ASC, a.appointment_time ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}
public function searchPatients($keyword) {
    $search = "%" . $keyword . "%";

    $sql = "SELECT 
                p.id AS patient_id,
                p.date_of_birth,
                p.blood_group,
                p.gender,
                p.address,
                p.emergency_contact_name,
                p.emergency_contact_phone,
                p.medical_history_notes,

                u.id AS user_id,
                u.name,
                u.email,
                u.mobile,
                u.phone,
                u.status,
                u.is_active
            FROM patients p
            JOIN users u ON p.user_id = u.id
            WHERE 
                p.id LIKE ?
                OR u.name LIKE ?
                OR u.email LIKE ?
                OR u.mobile LIKE ?
                OR u.phone LIKE ?
            ORDER BY u.name ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("sssss", $search, $search, $search, $search, $search);
    $stmt->execute();

    return $stmt->get_result();
}

public function getPatientById($patient_id) {
    $sql = "SELECT 
                p.id AS patient_id,
                p.date_of_birth,
                p.blood_group,
                p.gender,
                p.address,
                p.emergency_contact_name,
                p.emergency_contact_phone,
                p.medical_history_notes,

                u.id AS user_id,
                u.name,
                u.email,
                u.mobile,
                u.phone,
                u.status,
                u.is_active
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

public function getPatientUpcomingAppointments($patient_id) {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status,
                a.booked_by,

                d.specialization,
                u.name AS doctor_name,

                dep.name AS dependent_name,
                dep.relationship
            FROM appointments a
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u ON d.user_id = u.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.patient_id = ?
            AND a.appointment_date >= CURDATE()
            AND a.status IN ('pending', 'confirmed', 'checked_in')
            ORDER BY a.appointment_date ASC, a.appointment_time ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getPatientBillingSummary($patient_id) {
    $sql = "SELECT 
                COUNT(*) AS total_bills,
                SUM(CASE WHEN payment_status = 'paid' THEN amount ELSE 0 END) AS total_paid,
                SUM(CASE WHEN payment_status = 'pending' THEN amount ELSE 0 END) AS total_pending
            FROM billing
            WHERE patient_id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}
public function emailRoleExists($email, $role) {
    $sql = "SELECT id FROM users WHERE email = ? AND role = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();

    return $stmt->get_result()->num_rows > 0;
}

public function mobileRoleExists($mobile, $role) {
    $sql = "SELECT id FROM users WHERE mobile = ? AND role = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ss", $mobile, $role);
    $stmt->execute();

    return $stmt->get_result()->num_rows > 0;
}

public function registerPatient(
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
) {
    $this->conn->begin_transaction();

    try {
        $role = "patient";
        $status = "active";
        $is_active = 1;

        /*
            For your current project, passwords are plain text in seed data.
            If your login uses password_verify(), change this to:
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
        */
        $password_hash = $password;

        $user_sql = "INSERT INTO users
                     (name, email, password_hash, mobile, phone, role, dob, is_active, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $user_stmt = $this->conn->prepare($user_sql);

        if (!$user_stmt) {
            throw new Exception("User insert prepare failed: " . $this->conn->error);
        }

        $user_stmt->bind_param(
            "sssssssis",
            $name,
            $email,
            $password_hash,
            $mobile,
            $mobile,
            $role,
            $date_of_birth,
            $is_active,
            $status
        );

        $user_stmt->execute();

        $user_id = $this->conn->insert_id;

        $patient_sql = "INSERT INTO patients
                        (user_id, date_of_birth, blood_group, gender, address, emergency_contact, emergency_contact_name, emergency_contact_phone, medical_history_notes)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $patient_stmt = $this->conn->prepare($patient_sql);

        if (!$patient_stmt) {
            throw new Exception("Patient insert prepare failed: " . $this->conn->error);
        }

        $patient_stmt->bind_param(
            "issssssss",
            $user_id,
            $date_of_birth,
            $blood_group,
            $gender,
            $address,
            $emergency_contact_phone,
            $emergency_contact_name,
            $emergency_contact_phone,
            $medical_history_notes
        );

        $patient_stmt->execute();

        $this->conn->commit();

        return true;

    } catch (Exception $e) {
        $this->conn->rollback();
        die($e->getMessage());
    }
}
public function getAllApprovedDoctors() {
    $sql = "SELECT 
                d.id AS doctor_id,
                d.specialization,
                d.consultation_fee,
                d.experience_years,
                u.name AS doctor_name
            FROM doctors d
            JOIN users u ON d.user_id = u.id
            WHERE d.is_approved = 1
            ORDER BY u.name ASC";

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
                d.specialization,
                d.consultation_fee,
                d.experience_years,
                u.name AS doctor_name
            FROM doctors d
            JOIN users u ON d.user_id = u.id
            WHERE d.id = ?
            AND d.is_approved = 1";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function isSlotAlreadyBooked($doctor_id, $appointment_date, $appointment_time) {
    $sql = "SELECT id 
            FROM appointments
            WHERE doctor_id = ?
            AND appointment_date = ?
            AND appointment_time = ?
            AND status != 'cancelled'";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("iss", $doctor_id, $appointment_date, $appointment_time);
    $stmt->execute();

    return $stmt->get_result()->num_rows > 0;
}

public function bookWalkinAppointment($patient_id, $doctor_id, $appointment_date, $appointment_time, $reason) {
    $status = "confirmed";
    $booked_by = "receptionist";

    $sql = "INSERT INTO appointments
            (patient_id, doctor_id, appointment_date, appointment_time, reason, status, booked_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param(
        "iisssss",
        $patient_id,
        $doctor_id,
        $appointment_date,
        $appointment_time,
        $reason,
        $status,
        $booked_by
    );

    return $stmt->execute();
}
public function getTodayConfirmedAppointments() {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status,
                a.booked_by,

                p.id AS patient_id,
                u_patient.name AS patient_name,
                u_patient.mobile AS patient_mobile,

                dep.name AS dependent_name,
                dep.relationship,

                d.id AS doctor_id,
                d.specialization,
                u_doctor.name AS doctor_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u_patient ON p.user_id = u_patient.id
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u_doctor ON d.user_id = u_doctor.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.appointment_date = CURDATE()
            AND a.status = 'confirmed'
            ORDER BY a.appointment_time ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getAppointmentById($appointment_id) {
    $sql = "SELECT 
                a.*,
                p.id AS patient_id,
                u_patient.name AS patient_name,
                u_patient.mobile AS patient_mobile,
                u_doctor.name AS doctor_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u_patient ON p.user_id = u_patient.id
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u_doctor ON d.user_id = u_doctor.id
            WHERE a.id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function checkInAppointment($appointment_id) {
    $status = "checked_in";
    $checkin_time = date('Y-m-d H:i:s');

    $sql = "UPDATE appointments
            SET status = ?,
                checkin_time = ?
            WHERE id = ?
            AND appointment_date = CURDATE()
            AND status = 'confirmed'";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ssi", $status, $checkin_time, $appointment_id);

    return $stmt->execute();
}
public function getWaitingRoomQueue() {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status,
                a.checkin_time,

                p.id AS patient_id,
                u_patient.name AS patient_name,
                u_patient.mobile AS patient_mobile,

                dep.name AS dependent_name,
                dep.relationship,

                d.id AS doctor_id,
                d.specialization,
                u_doctor.name AS doctor_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u_patient ON p.user_id = u_patient.id
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u_doctor ON d.user_id = u_doctor.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.appointment_date = CURDATE()
            AND a.status = 'checked_in'
            ORDER BY u_doctor.name ASC, a.checkin_time ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}
public function getPendingPayments() {
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

                dep.name AS dependent_name,
                dep.relationship,

                d.specialization,
                u_doctor.name AS doctor_name
            FROM billing b
            JOIN appointments a ON b.appointment_id = a.id
            JOIN patients p ON b.patient_id = p.id
            JOIN users u_patient ON p.user_id = u_patient.id
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u_doctor ON d.user_id = u_doctor.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE b.payment_status = 'pending'
            ORDER BY a.appointment_date DESC, a.appointment_time DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function getBillingById($billing_id) {
    $sql = "SELECT 
                b.*,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                u_patient.name AS patient_name,
                u_patient.mobile AS patient_mobile,
                u_doctor.name AS doctor_name,
                d.specialization
            FROM billing b
            JOIN appointments a ON b.appointment_id = a.id
            JOIN patients p ON b.patient_id = p.id
            JOIN users u_patient ON p.user_id = u_patient.id
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u_doctor ON d.user_id = u_doctor.id
            WHERE b.id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $billing_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function markBillAsPaid($billing_id, $payment_method) {
    $payment_status = "paid";
    $paid_at = date('Y-m-d H:i:s');

    $sql = "UPDATE billing
            SET payment_status = ?,
                payment_method = ?,
                paid_at = ?
            WHERE id = ?
            AND payment_status = 'pending'";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("sssi", $payment_status, $payment_method, $paid_at, $billing_id);

    return $stmt->execute();
}

public function createBillForAppointmentIfMissing($appointment_id) {
    $check_sql = "SELECT id FROM billing WHERE appointment_id = ?";
    $check_stmt = $this->conn->prepare($check_sql);

    if (!$check_stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $check_stmt->bind_param("i", $appointment_id);
    $check_stmt->execute();
    $existing = $check_stmt->get_result()->fetch_assoc();

    if ($existing) {
        return true;
    }

    $appointment_sql = "SELECT 
                            a.id,
                            a.patient_id,
                            d.consultation_fee
                        FROM appointments a
                        JOIN doctors d ON a.doctor_id = d.id
                        WHERE a.id = ?";

    $appointment_stmt = $this->conn->prepare($appointment_sql);

    if (!$appointment_stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $appointment_stmt->bind_param("i", $appointment_id);
    $appointment_stmt->execute();
    $appointment = $appointment_stmt->get_result()->fetch_assoc();

    if (!$appointment) {
        return false;
    }

    $payment_status = "pending";

    $insert_sql = "INSERT INTO billing
                   (appointment_id, patient_id, amount, payment_status)
                   VALUES (?, ?, ?, ?)";

    $insert_stmt = $this->conn->prepare($insert_sql);

    if (!$insert_stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $insert_stmt->bind_param(
        "iids",
        $appointment['id'],
        $appointment['patient_id'],
        $appointment['consultation_fee'],
        $payment_status
    );

    return $insert_stmt->execute();
}
public function getAllActiveAppointments() {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status,
                a.booked_by,
                a.checkin_time,

                p.id AS patient_id,
                u_patient.name AS patient_name,
                u_patient.mobile AS patient_mobile,

                dep.name AS dependent_name,
                dep.relationship,

                d.id AS doctor_id,
                d.specialization,
                u_doctor.name AS doctor_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u_patient ON p.user_id = u_patient.id
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u_doctor ON d.user_id = u_doctor.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.status IN ('pending', 'confirmed', 'checked_in')
            ORDER BY a.appointment_date ASC, a.appointment_time ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}

public function cancelAppointmentByReceptionist($appointment_id) {
    $status = "cancelled";

    $sql = "UPDATE appointments
            SET status = ?
            WHERE id = ?
            AND status IN ('pending', 'confirmed')";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("si", $status, $appointment_id);

    return $stmt->execute();
}

public function rescheduleAppointmentByReceptionist($appointment_id, $doctor_id, $new_date, $new_time) {
    $status = "confirmed";

    $sql = "UPDATE appointments
            SET doctor_id = ?,
                appointment_date = ?,
                appointment_time = ?,
                status = ?
            WHERE id = ?
            AND status IN ('pending', 'confirmed')";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("isssi", $doctor_id, $new_date, $new_time, $status, $appointment_id);

    return $stmt->execute();
}
public function getDoctorAvailabilityByDate($selected_date) {
    $day_of_week = date('l', strtotime($selected_date));

    $sql = "SELECT 
                d.id AS doctor_id,
                d.specialization,
                d.consultation_fee,
                d.experience_years,
                u.name AS doctor_name,
                u.mobile AS doctor_mobile,

                da.start_time,
                da.end_time,
                da.slot_duration_minutes,
                da.is_available,

                ld.id AS leave_id,
                ld.reason AS leave_reason
            FROM doctors d
            JOIN users u ON d.user_id = u.id
            LEFT JOIN doctor_availability da 
                ON d.id = da.doctor_id 
                AND da.day_of_week = ?
            LEFT JOIN leave_dates ld 
                ON d.id = ld.doctor_id 
                AND ld.leave_date = ?
            WHERE d.is_approved = 1
            ORDER BY u.name ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ss", $day_of_week, $selected_date);
    $stmt->execute();

    return $stmt->get_result();
}

public function countBookedSlotsForDoctorDate($doctor_id, $selected_date) {
    $sql = "SELECT COUNT(*) AS total
            FROM appointments
            WHERE doctor_id = ?
            AND appointment_date = ?
            AND status != 'cancelled'";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("is", $doctor_id, $selected_date);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc()['total'];
}
public function getDailyAppointmentSummary($date) {
    $sql = "SELECT 
                COUNT(*) AS total_appointments,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) AS confirmed_count,
                SUM(CASE WHEN status = 'checked_in' THEN 1 ELSE 0 END) AS checked_in_count,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_count,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_count,
                SUM(CASE WHEN status = 'no_show' THEN 1 ELSE 0 END) AS no_show_count
            FROM appointments
            WHERE appointment_date = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("s", $date);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function getDailyBillingSummary($date) {
    $sql = "SELECT 
                COUNT(b.id) AS total_bills,
                SUM(CASE WHEN b.payment_status = 'paid' THEN 1 ELSE 0 END) AS paid_bills,
                SUM(CASE WHEN b.payment_status = 'pending' THEN 1 ELSE 0 END) AS pending_bills,
                SUM(CASE WHEN b.payment_status = 'paid' THEN b.amount ELSE 0 END) AS total_revenue,
                SUM(CASE WHEN b.payment_status = 'pending' THEN b.amount ELSE 0 END) AS pending_amount
            FROM billing b
            JOIN appointments a ON b.appointment_id = a.id
            WHERE a.appointment_date = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("s", $date);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function getDailyPaymentMethodSummary($date) {
    $sql = "SELECT 
                payment_method,
                COUNT(*) AS total_payments,
                SUM(amount) AS total_amount
            FROM billing
            WHERE DATE(paid_at) = ?
            AND payment_status = 'paid'
            GROUP BY payment_method
            ORDER BY total_amount DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("s", $date);
    $stmt->execute();

    return $stmt->get_result();
}

public function getDoctorWiseDailySummary($date) {
    $sql = "SELECT 
                d.id AS doctor_id,
                u.name AS doctor_name,
                d.specialization,
                COUNT(a.id) AS total_appointments,
                SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
                SUM(CASE WHEN a.status = 'confirmed' THEN 1 ELSE 0 END) AS confirmed_count,
                SUM(CASE WHEN a.status = 'checked_in' THEN 1 ELSE 0 END) AS checked_in_count,
                SUM(CASE WHEN a.status = 'completed' THEN 1 ELSE 0 END) AS completed_count,
                SUM(CASE WHEN a.status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_count
            FROM appointments a
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u ON d.user_id = u.id
            WHERE a.appointment_date = ?
            GROUP BY d.id, u.name, d.specialization
            ORDER BY u.name ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("s", $date);
    $stmt->execute();

    return $stmt->get_result();
}

public function getDailyDetailedAppointments($date) {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_time,
                a.reason,
                a.status,
                a.booked_by,
                a.checkin_time,

                u_patient.name AS patient_name,
                u_patient.mobile AS patient_mobile,

                dep.name AS dependent_name,
                dep.relationship,

                u_doctor.name AS doctor_name,
                d.specialization
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u_patient ON p.user_id = u_patient.id
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u_doctor ON d.user_id = u_doctor.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.appointment_date = ?
            ORDER BY a.appointment_time ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("s", $date);
    $stmt->execute();

    return $stmt->get_result();
}
}

?>