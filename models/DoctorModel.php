<?php
class DoctorModel {
    private $conn;
public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getDoctorByUserId($user_id) {
        $sql = "SELECT 
                    d.id AS doctor_id,
                    d.user_id,
                    d.specialization,
                    d.bio,
                    d.consultation_fee,
                    d.photo_path,
                    d.license_number,
                    d.experience_years,
                    d.is_approved,
                    d.status,
                    u.name,
                    u.email,
                    u.mobile,
                    u.phone,
                    u.profile_pic
                FROM doctors d
                JOIN users u ON d.user_id = u.id
                WHERE d.user_id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getTodayAppointmentsCount($doctor_id) {
        $today = date('Y-m-d');

        $sql = "SELECT COUNT(*) AS total
                FROM appointments
                WHERE doctor_id = ?
                AND appointment_date = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("is", $doctor_id, $today);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getPendingAppointmentsCount($doctor_id) {
        $sql = "SELECT COUNT(*) AS total
                FROM appointments
                WHERE doctor_id = ?
                AND status = 'pending'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getCompletedAppointmentsCount($doctor_id) {
        $sql = "SELECT COUNT(*) AS total
                FROM appointments
                WHERE doctor_id = ?
                AND status = 'completed'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getTotalEarnings($doctor_id) {
        $sql = "SELECT 
                    SUM(d.consultation_fee) AS total_earnings
                FROM appointments a
                JOIN doctors d ON a.doctor_id = d.id
                WHERE a.doctor_id = ?
                AND a.status = 'completed'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        return $row['total_earnings'] ?? 0;
    }

    public function updateDoctorProfile(
    $user_id,
    $name,
    $mobile,
    $specialization,
    $bio,
    $consultation_fee,
    $license_number,
    $experience_years) 
    {
    $this->conn->begin_transaction();

    try {
        $user_sql = "UPDATE users 
                     SET name = ?, mobile = ?, phone = ?
                     WHERE id = ?";

        $user_stmt = $this->conn->prepare($user_sql);

        if (!$user_stmt) {
            throw new Exception("User update prepare failed: " . $this->conn->error);
        }

        $user_stmt->bind_param("sssi", $name, $mobile, $mobile, $user_id);
        $user_stmt->execute();

        $doctor_sql = "UPDATE doctors 
                       SET specialization = ?,
                           bio = ?,
                           consultation_fee = ?,
                           license_number = ?,
                           experience_years = ?
                       WHERE user_id = ?";

        $doctor_stmt = $this->conn->prepare($doctor_sql);

        if (!$doctor_stmt) {
            throw new Exception("Doctor update prepare failed: " . $this->conn->error);
        }

        $doctor_stmt->bind_param(
            "ssdsii",
            $specialization,
            $bio,
            $consultation_fee,
            $license_number,
            $experience_years,
            $user_id
        );

        $doctor_stmt->execute();

        $this->conn->commit();
        return true;

    } catch (Exception $e) {
        $this->conn->rollback();
        die($e->getMessage());
    }
}

   public function getAvailability($doctor_id) {
    $sql = "SELECT *
            FROM doctor_availability
            WHERE doctor_id = ?
            ORDER BY FIELD(day_of_week, 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getAvailabilityByDay($doctor_id, $day_of_week) {
    $sql = "SELECT *
            FROM doctor_availability
            WHERE doctor_id = ?
            AND day_of_week = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("is", $doctor_id, $day_of_week);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function saveAvailability($doctor_id, $day_of_week, $start_time, $end_time, $slot_duration_minutes, $is_available) {
    $existing = $this->getAvailabilityByDay($doctor_id, $day_of_week);

    if ($existing) {
        $sql = "UPDATE doctor_availability
                SET start_time = ?,
                    end_time = ?,
                    slot_duration_minutes = ?,
                    is_available = ?
                WHERE doctor_id = ?
                AND day_of_week = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "ssiiis",
            $start_time,
            $end_time,
            $slot_duration_minutes,
            $is_available,
            $doctor_id,
            $day_of_week
        );

        return $stmt->execute();
    } else {
        $sql = "INSERT INTO doctor_availability
                (doctor_id, day_of_week, start_time, end_time, slot_duration_minutes, is_available)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "isssii",
            $doctor_id,
            $day_of_week,
            $start_time,
            $end_time,
            $slot_duration_minutes,
            $is_available
        );

        return $stmt->execute();
    }
}

public function getLeaveDates($doctor_id) {
    $sql = "SELECT *
            FROM leave_dates
            WHERE doctor_id = ?
            ORDER BY leave_date DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function addLeaveDate($doctor_id, $leave_date, $reason) {
    $sql = "INSERT INTO leave_dates
            (doctor_id, leave_date, reason)
            VALUES (?, ?, ?)";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("iss", $doctor_id, $leave_date, $reason);

    return $stmt->execute();
}

public function getLeaveDateById($leave_id) {
    $sql = "SELECT *
            FROM leave_dates
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $leave_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function deleteLeaveDate($leave_id, $doctor_id) {
    $sql = "DELETE FROM leave_dates
            WHERE id = ?
            AND doctor_id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ii", $leave_id, $doctor_id);

    return $stmt->execute();
}

  public function getTodayAppointments($doctor_id) {
    $today = date('Y-m-d');

    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status,
                a.booked_by,
                a.checkin_time,
                p.id AS patient_id,
                u.name AS patient_name,
                u.mobile AS patient_mobile,
                dep.name AS dependent_name,
                dep.relationship
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u ON p.user_id = u.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.doctor_id = ?
            AND a.appointment_date = ?
            ORDER BY a.appointment_time ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("is", $doctor_id, $today);
    $stmt->execute();

    return $stmt->get_result();
}

public function getDoctorAppointmentById($appointment_id, $doctor_id) {
    $sql = "SELECT 
                a.*,
                u.name AS patient_name,
                u.mobile AS patient_mobile,
                dep.name AS dependent_name,
                dep.relationship
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u ON p.user_id = u.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.id = ?
            AND a.doctor_id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ii", $appointment_id, $doctor_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function updateAppointmentStatus($appointment_id, $doctor_id, $status) {
    $sql = "UPDATE appointments
            SET status = ?
            WHERE id = ?
            AND doctor_id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("sii", $status, $appointment_id, $doctor_id);

    return $stmt->execute();
}

public function getPendingAppointments($doctor_id) {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status,
                a.booked_by,
                p.id AS patient_id,
                u.name AS patient_name,
                u.mobile AS patient_mobile,
                dep.name AS dependent_name,
                dep.relationship
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u ON p.user_id = u.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.doctor_id = ?
            AND a.status = 'pending'
            ORDER BY a.appointment_date ASC, a.appointment_time ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function checkInAppointment($appointment_id, $doctor_id) {
    $status = "checked_in";
    $checkin_time = date('Y-m-d H:i:s');

    $sql = "UPDATE appointments
            SET status = ?,
                checkin_time = ?
            WHERE id = ?
            AND doctor_id = ?
            AND status = 'confirmed'";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ssii", $status, $checkin_time, $appointment_id, $doctor_id);

    return $stmt->execute();
}

public function getCheckedInAppointments($doctor_id) {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.status,
                a.patient_id,
                p.id AS patient_id,
                u.name AS patient_name,
                u.mobile AS patient_mobile,
                dep.name AS dependent_name,
                dep.relationship
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u ON p.user_id = u.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.doctor_id = ?
            AND a.status = 'checked_in'
            ORDER BY a.appointment_date DESC, a.appointment_time DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function addConsultationNote(
    $appointment_id,
    $doctor_id,
    $patient_id,
    $symptoms,
    $diagnosis,
    $prescription,
    $follow_up_date
) {
    $this->conn->begin_transaction();

    try {
        $note_sql = "INSERT INTO consultation_notes
                     (appointment_id, doctor_id, patient_id, symptoms, diagnosis, prescription, follow_up_date)
                     VALUES (?, ?, ?, ?, ?, ?, ?)";

        $note_stmt = $this->conn->prepare($note_sql);

        if (!$note_stmt) {
            throw new Exception("Consultation note prepare failed: " . $this->conn->error);
        }

        if (empty($follow_up_date)) {
            $follow_up_date = null;
        }

        $note_stmt->bind_param(
            "iiissss",
            $appointment_id,
            $doctor_id,
            $patient_id,
            $symptoms,
            $diagnosis,
            $prescription,
            $follow_up_date
        );

        $note_stmt->execute();

        $status = "completed";

        $appointment_sql = "UPDATE appointments
                            SET status = ?
                            WHERE id = ?
                            AND doctor_id = ?
                            AND status = 'checked_in'";

        $appointment_stmt = $this->conn->prepare($appointment_sql);

        if (!$appointment_stmt) {
            throw new Exception("Appointment update prepare failed: " . $this->conn->error);
        }

        $appointment_stmt->bind_param("sii", $status, $appointment_id, $doctor_id);
        $appointment_stmt->execute();

        $this->conn->commit();
        return true;

    } catch (Exception $e) {
        $this->conn->rollback();
        die($e->getMessage());
    }
}

public function getPatientsWithConsultationNotes($doctor_id) {
    $sql = "SELECT DISTINCT
                p.id AS patient_id,
                u.name AS patient_name,
                u.mobile AS patient_mobile,
                p.blood_group,
                p.gender
            FROM consultation_notes cn
            JOIN patients p ON cn.patient_id = p.id
            JOIN users u ON p.user_id = u.id
            WHERE cn.doctor_id = ?
            ORDER BY u.name ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getPatientConsultationHistory($doctor_id, $patient_id) {
    $sql = "SELECT
                cn.id AS note_id,
                cn.symptoms,
                cn.diagnosis,
                cn.prescription,
                cn.follow_up_date,
                cn.created_at,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                dep.name AS dependent_name,
                dep.relationship
            FROM consultation_notes cn
            JOIN appointments a ON cn.appointment_id = a.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE cn.doctor_id = ?
            AND cn.patient_id = ?
            ORDER BY cn.created_at DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("ii", $doctor_id, $patient_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getPatientBasicInfo($patient_id) {
    $sql = "SELECT
                p.id AS patient_id,
                p.date_of_birth,
                p.blood_group,
                p.gender,
                p.address,
                p.emergency_contact_name,
                p.emergency_contact_phone,
                p.medical_history_notes,
                u.name AS patient_name,
                u.email,
                u.mobile
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

public function getDoctorReviews($doctor_id) {
    $sql = "SELECT 
                r.id AS review_id,
                r.rating,
                r.review_text,
                r.created_at,
                a.appointment_date,
                p.id AS patient_id,
                u.name AS patient_name
            FROM doctor_reviews r
            JOIN appointments a ON r.appointment_id = a.id
            JOIN patients p ON r.patient_id = p.id
            JOIN users u ON p.user_id = u.id
            WHERE r.doctor_id = ?
            ORDER BY r.created_at DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getDoctorReviewStats($doctor_id) {
    $sql = "SELECT 
                COUNT(*) AS total_reviews,
                AVG(rating) AS average_rating
            FROM doctor_reviews
            WHERE doctor_id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function getEarningsSummary($doctor_id) {
    $sql = "SELECT 
                COUNT(a.id) AS completed_count,
                SUM(d.consultation_fee) AS total_earnings
            FROM appointments a
            JOIN doctors d ON a.doctor_id = d.id
            WHERE a.doctor_id = ?
            AND a.status = 'completed'";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function getDailyEarnings($doctor_id) {
    $sql = "SELECT 
                a.appointment_date,
                COUNT(a.id) AS completed_count,
                SUM(d.consultation_fee) AS total_amount
            FROM appointments a
            JOIN doctors d ON a.doctor_id = d.id
            WHERE a.doctor_id = ?
            AND a.status = 'completed'
            GROUP BY a.appointment_date
            ORDER BY a.appointment_date DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getMonthlyEarnings($doctor_id) {
    $sql = "SELECT 
                DATE_FORMAT(a.appointment_date, '%Y-%m') AS earning_month,
                COUNT(a.id) AS completed_count,
                SUM(d.consultation_fee) AS total_amount
            FROM appointments a
            JOIN doctors d ON a.doctor_id = d.id
            WHERE a.doctor_id = ?
            AND a.status = 'completed'
            GROUP BY DATE_FORMAT(a.appointment_date, '%Y-%m')
            ORDER BY earning_month DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getCompletedAppointmentEarnings($doctor_id) {
    $sql = "SELECT 
                a.id AS appointment_id,
                a.appointment_date,
                a.appointment_time,
                d.consultation_fee,
                u.name AS patient_name,
                dep.name AS dependent_name,
                dep.relationship
            FROM appointments a
            JOIN doctors d ON a.doctor_id = d.id
            JOIN patients p ON a.patient_id = p.id
            JOIN users u ON p.user_id = u.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE a.doctor_id = ?
            AND a.status = 'completed'
            ORDER BY a.appointment_date DESC, a.appointment_time DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}
public function getAppointmentStatusStats($doctor_id) {
    $sql = "SELECT 
                status,
                COUNT(*) AS total
            FROM appointments
            WHERE doctor_id = ?
            GROUP BY status";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getTotalAppointments($doctor_id) {
    $sql = "SELECT COUNT(*) AS total
            FROM appointments
            WHERE doctor_id = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc()['total'];
}

public function getBusiestDays($doctor_id) {
    $sql = "SELECT 
                DAYNAME(appointment_date) AS day_name,
                COUNT(*) AS total
            FROM appointments
            WHERE doctor_id = ?
            GROUP BY DAYNAME(appointment_date)
            ORDER BY total DESC
            LIMIT 7";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getBusiestTimes($doctor_id) {
    $sql = "SELECT 
                appointment_time,
                COUNT(*) AS total
            FROM appointments
            WHERE doctor_id = ?
            GROUP BY appointment_time
            ORDER BY total DESC
            LIMIT 10";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

public function getMonthlyAppointmentStats($doctor_id) {
    $sql = "SELECT 
                DATE_FORMAT(appointment_date, '%Y-%m') AS month_name,
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled,
                SUM(CASE WHEN status = 'no_show' THEN 1 ELSE 0 END) AS no_show
            FROM appointments
            WHERE doctor_id = ?
            GROUP BY DATE_FORMAT(appointment_date, '%Y-%m')
            ORDER BY month_name DESC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}
public function getUpcomingFollowUps($doctor_id) {
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
                a.reason,
                p.id AS patient_id,
                u.name AS patient_name,
                u.mobile AS patient_mobile,
                dep.name AS dependent_name,
                dep.relationship
            FROM consultation_notes cn
            JOIN appointments a ON cn.appointment_id = a.id
            JOIN patients p ON cn.patient_id = p.id
            JOIN users u ON p.user_id = u.id
            LEFT JOIN dependents dep ON a.dependent_id = dep.id
            WHERE cn.doctor_id = ?
            AND cn.follow_up_date IS NOT NULL
            AND cn.follow_up_date >= CURDATE()
            ORDER BY cn.follow_up_date ASC";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();

    return $stmt->get_result();
}

}

?>
