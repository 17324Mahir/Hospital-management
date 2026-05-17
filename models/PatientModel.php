<?php

class PatientModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getPatientByUserId($user_id) {
        $sql = "SELECT * FROM patients WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getMyHistory($patient_id) {
    $sql = "SELECT 
                a.id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status,
                a.booked_by,
                dep.name AS dependent_name,
                dep.relationship,
                u.name AS doctor_name,
                d.specialization,
                d.consultation_fee
            FROM appointments a
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u ON d.user_id = u.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.patient_id = ?
            ORDER BY a.appointment_date DESC, a.appointment_time DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    return $stmt->get_result();
}

    public function getApprovedDoctors() {
        $sql = "SELECT 
                    d.id AS doctor_id,
                    d.specialization,
                    d.consultation_fee,
                    d.bio,
                    d.experience_years,
                    u.name AS doctor_name,
                    u.email,
                    u.mobile
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
                    d.bio,
                    d.experience_years,
                    u.name AS doctor_name,
                    u.email,
                    u.mobile
                FROM doctors d
                JOIN users u ON d.user_id = u.id
                WHERE d.id = ? AND d.is_approved = 1";

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

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function bookAppointment($patient_id, $dependent_id, $doctor_id, $appointment_date, $appointment_time, $reason) {
    $status = "pending";
    $booked_by = "patient";

    $sql = "INSERT INTO appointments 
            (patient_id, dependent_id, doctor_id, appointment_date, appointment_time, reason, status, booked_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    if ($dependent_id === null || $dependent_id === "") {
        $dependent_id = null;
    }

    $stmt->bind_param(
        "iiisssss",
        $patient_id,
        $dependent_id,
        $doctor_id,
        $appointment_date,
        $appointment_time,
        $reason,
        $status,
        $booked_by
    );

    return $stmt->execute();
}

    public function getAppointmentById($appointment_id) {
        $sql = "SELECT * FROM appointments WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function cancelAppointment($appointment_id, $patient_id) {
        $status = "cancelled";

        $sql = "UPDATE appointments
                SET status = ?
                WHERE id = ?
                AND patient_id = ?
                AND status IN ('pending', 'confirmed')";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("sii", $status, $appointment_id, $patient_id);

        return $stmt->execute();
    }

    public function getBillingHistory($patient_id) 
    {
    $sql = "SELECT 
                b.id AS billing_id,
                b.appointment_id,
                b.amount,
                b.payment_method,
                b.payment_status,
                b.paid_at,
                a.appointment_date,
                a.appointment_time,
                a.status AS appointment_status,
                u.name AS doctor_name,
                d.specialization
            FROM billing b
            JOIN appointments a ON b.appointment_id = a.id
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u ON d.user_id = u.id
            WHERE b.patient_id = ?
            ORDER BY a.appointment_date DESC, a.appointment_time DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    return $stmt->get_result();
    }

    public function getCompletedAppointmentsForReview($patient_id) {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_date,
                a.appointment_time,
                a.status,
                d.id AS doctor_id,
                d.specialization,
                u.name AS doctor_name
            FROM appointments a
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u ON d.user_id = u.id
            LEFT JOIN doctor_reviews r ON a.id = r.appointment_id
            WHERE a.patient_id = ?
            AND a.status = 'completed'
            AND r.id IS NULL
            ORDER BY a.appointment_date DESC, a.appointment_time DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getMyReviews($patient_id) {
    $sql = "SELECT 
                r.id AS review_id,
                r.rating,
                r.review_text,
                r.created_at,
                a.appointment_date,
                d.specialization,
                u.name AS doctor_name
            FROM doctor_reviews r
            JOIN appointments a ON r.appointment_id = a.id
            JOIN doctors d ON r.doctor_id = d.id
            JOIN users u ON d.user_id = u.id
            WHERE r.patient_id = ?
            ORDER BY r.created_at DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function addDoctorReview($appointment_id, $patient_id, $doctor_id, $rating, $review_text) {
    $sql = "INSERT INTO doctor_reviews 
            (appointment_id, patient_id, doctor_id, rating, review_text)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("iiiis", $appointment_id, $patient_id, $doctor_id, $rating, $review_text);

    return $stmt->execute();
}

public function getCompletedAppointmentById($appointment_id, $patient_id) {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.patient_id,
                a.doctor_id,
                a.status
            FROM appointments a
            WHERE a.id = ?
            AND a.patient_id = ?
            AND a.status = 'completed'";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ii", $appointment_id, $patient_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

  public function getPatientAnnouncements() {
    $sql = "SELECT 
                a.id,
                a.title,
                a.body,
                a.target_role,
                a.published_at,
                u.name AS author_name
            FROM announcements a
            LEFT JOIN users u ON a.author_id = u.id
            WHERE a.target_role IN ('all', 'patient')
            ORDER BY a.published_at DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();

    return $stmt->get_result();
}
  public function getFullPatientProfile($user_id) {
    $sql = "SELECT 
                u.id AS user_id,
                u.name,
                u.email,
                u.mobile,
                u.phone,
                u.dob,
                u.profile_pic,
                p.id AS patient_id,
                p.date_of_birth,
                p.blood_group,
                p.gender,
                p.address,
                p.emergency_contact,
                p.emergency_contact_name,
                p.emergency_contact_phone,
                p.medical_history_notes
            FROM users u
            JOIN patients p ON u.id = p.user_id
            WHERE u.id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function updatePatientProfile(
    $user_id,
    $name,
    $mobile,
    $date_of_birth,
    $blood_group,
    $gender,
    $address,
    $emergency_contact_name,
    $emergency_contact_phone,
    $medical_history_notes
) {
    $this->conn->begin_transaction();

    try {
        $user_sql = "UPDATE users 
                     SET name = ?, mobile = ?, phone = ?, dob = ?
                     WHERE id = ?";

        $user_stmt = $this->conn->prepare($user_sql);

        if (!$user_stmt) {
            throw new Exception("User update prepare failed: " . $this->conn->error);
        }

        $user_stmt->bind_param("ssssi", $name, $mobile, $mobile, $date_of_birth, $user_id);
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
                        WHERE user_id = ?";

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
            $user_id
        );

        $patient_stmt->execute();

        $this->conn->commit();
        return true;

    } catch (Exception $e) {
        $this->conn->rollback();
        die($e->getMessage());
    }
}

public function getDependents($patient_id) {
    $sql = "SELECT *
            FROM dependents
            WHERE primary_patient_id = ?
            ORDER BY id DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function addDependent($patient_id, $name, $date_of_birth, $relationship, $blood_group) {
    $sql = "INSERT INTO dependents
            (primary_patient_id, name, date_of_birth, relationship, blood_group)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param(
        "issss",
        $patient_id,
        $name,
        $date_of_birth,
        $relationship,
        $blood_group
    );

    return $stmt->execute();
}

public function getDependentById($dependent_id) {
    $sql = "SELECT *
            FROM dependents
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $dependent_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function deleteDependent($dependent_id, $patient_id) {
    $sql = "DELETE FROM dependents
            WHERE id = ?
            AND primary_patient_id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ii", $dependent_id, $patient_id);

    return $stmt->execute();
}
 public function isMyDependent($dependent_id, $patient_id) {
    $sql = "SELECT id 
            FROM dependents 
            WHERE id = ? 
            AND primary_patient_id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ii", $dependent_id, $patient_id);
    $stmt->execute();

    return $stmt->get_result()->num_rows > 0;
}

  public function getConsultationNotes($patient_id) {
    $sql = "SELECT 
                cn.id AS note_id,
                cn.appointment_id,
                cn.symptoms,
                cn.diagnosis,
                cn.prescription,
                cn.follow_up_date,
                cn.created_at,
                a.appointment_date,
                a.appointment_time,
                a.status AS appointment_status,
                dep.name AS dependent_name,
                dep.relationship,
                u.name AS doctor_name,
                d.specialization
            FROM consultation_notes cn
            JOIN appointments a ON cn.appointment_id = a.id
            JOIN doctors d ON cn.doctor_id = d.id
            JOIN users u ON d.user_id = u.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE cn.patient_id = ?
            ORDER BY cn.created_at DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getConsultationNoteById($note_id, $patient_id) {
    $sql = "SELECT 
                cn.id AS note_id,
                cn.appointment_id,
                cn.symptoms,
                cn.diagnosis,
                cn.prescription,
                cn.follow_up_date,
                cn.created_at,
                a.appointment_date,
                a.appointment_time,
                dep.name AS dependent_name,
                dep.relationship,
                u.name AS doctor_name,
                d.specialization
            FROM consultation_notes cn
            JOIN appointments a ON cn.appointment_id = a.id
            JOIN doctors d ON cn.doctor_id = d.id
            JOIN users u ON d.user_id = u.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE cn.id = ?
            AND cn.patient_id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ii", $note_id, $patient_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function getPatientAppointmentById($appointment_id, $patient_id) {
    $sql = "SELECT 
                a.id,
                a.patient_id,
                a.dependent_id,
                a.doctor_id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status,
                dep.name AS dependent_name,
                dep.relationship,
                u.name AS doctor_name,
                d.specialization
            FROM appointments a
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u ON d.user_id = u.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.id = ?
            AND a.patient_id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ii", $appointment_id, $patient_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function rescheduleAppointment($appointment_id, $patient_id, $new_date, $new_time) {
    $status = "pending";

    $sql = "UPDATE appointments
            SET appointment_date = ?,
                appointment_time = ?,
                status = ?
            WHERE id = ?
            AND patient_id = ?
            AND status IN ('pending', 'confirmed')";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("sssii", $new_date, $new_time, $status, $appointment_id, $patient_id);

    return $stmt->execute();
}
}

?>