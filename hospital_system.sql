DROP DATABASE IF EXISTS `hospital_system`;
CREATE DATABASE `hospital_system`
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE `hospital_system`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+06:00";
SET FOREIGN_KEY_CHECKS = 0;

START TRANSACTION;

CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `mobile` VARCHAR(20) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `role` ENUM('patient','doctor','receptionist','admin') NOT NULL,
  `dob` DATE DEFAULT NULL,
  `profile_pic` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `status` ENUM('pending','active','inactive','rejected') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email_role` (`email`, `role`),
  UNIQUE KEY `unique_mobile_role` (`mobile`, `role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users`
(`id`, `name`, `email`, `password_hash`, `mobile`, `phone`, `role`, `dob`, `profile_pic`, `is_active`, `status`)
VALUES
(1, 'Mahir', 'mahir.receptionist@gmail.com', 'Mahir345@', '01923456789', '01923456789', 'receptionist', '2005-05-05', NULL, 1, 'active'),
(2, 'Mahir', 'mahir.frontdesk@gmail.com', 'Mahir345@', '01811111111', '01811111111', 'receptionist', '2004-03-10', NULL, 1, 'active'),
(3, 'Mahir Tajwar', 'mahir.patient@gmail.com', 'Mahir345@', '01710000001', '01710000001', 'patient', '2005-05-05', NULL, 1, 'active'),
(4, 'Nahid Hossain', 'nahid.patient@gmail.com', 'Nahid@123', '01710000002', '01710000002', 'patient', '2006-02-12', NULL, 1, 'active'),
(5, 'Dr. Fahmida Jannat', 'fahmida.doctor@gmail.com', 'Doctor@123', '01710000003', '01710000003', 'doctor', '1990-06-14', NULL, 1, 'active'),
(6, 'Dr. Rahman Karim', 'rahman.doctor@gmail.com', 'Doctor@123', '01710000004', '01710000004', 'doctor', '1988-08-20', NULL, 1, 'active'),
(7, 'System Admin', 'admin@gmail.com', 'Admin@123', '01700000000', '01700000000', 'admin', '1990-01-01', NULL, 1, 'active');

CREATE TABLE `patients` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `date_of_birth` DATE DEFAULT NULL,
  `blood_group` VARCHAR(5) NOT NULL,
  `gender` ENUM('Male','Female','Other') NOT NULL,
  `address` TEXT NOT NULL,
  `emergency_contact` VARCHAR(20) DEFAULT NULL,
  `emergency_contact_name` VARCHAR(100) DEFAULT NULL,
  `emergency_contact_phone` VARCHAR(20) DEFAULT NULL,
  `medical_history_notes` TEXT DEFAULT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_patient_user_profile` (`user_id`),
  CONSTRAINT `fk_patient_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `patients`
(`id`, `user_id`, `date_of_birth`, `blood_group`, `gender`, `address`, `emergency_contact`, `emergency_contact_name`, `emergency_contact_phone`, `medical_history_notes`)
VALUES
(1, 3, '2005-05-05', 'O+', 'Male', 'Dhaka, Bangladesh', '01711111111', 'Father', '01711111111', 'No major medical history.'),
(2, 4, '2006-02-12', 'B+', 'Male', 'Khulna, Bangladesh', '01822222222', 'Brother', '01822222222', 'Has mild allergy problem.');

CREATE TABLE `specializations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_specialization_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `specializations`
(`id`, `name`, `description`, `status`)
VALUES
(1, 'General Physician', 'General medicine and primary healthcare', 'active'),
(2, 'Cardiology', 'Heart and cardiovascular treatment', 'active'),
(3, 'Neurology', 'Brain and nervous system treatment', 'active'),
(4, 'Dermatology', 'Skin, hair, and nail treatment', 'active');

CREATE TABLE `doctors` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `specialization_id` INT(11) DEFAULT NULL,
  `specialization` VARCHAR(100) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `consultation_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `photo_path` VARCHAR(255) DEFAULT NULL,
  `license_number` VARCHAR(100) DEFAULT NULL,
  `experience_years` INT(11) NOT NULL DEFAULT 0,
  `is_approved` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_doctor_user_id` (`user_id`),
  KEY `idx_doctor_specialization_id` (`specialization_id`),
  CONSTRAINT `fk_doctor_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_doctor_specialization`
    FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `doctors`
(`id`, `user_id`, `specialization_id`, `specialization`, `bio`, `consultation_fee`, `photo_path`, `license_number`, `experience_years`, `is_approved`, `status`)
VALUES
(1, 5, 1, 'General Physician', 'Experienced general physician providing primary healthcare and regular consultation.', 500.00, NULL, 'BMDC-2026-99A', 5, 1, 'approved'),
(2, 6, 2, 'Cardiology', 'Experienced cardiologist for heart and blood pressure related treatment.', 800.00, NULL, 'BMDC-2026-88B', 8, 1, 'approved');

CREATE TABLE `doctor_availability` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `doctor_id` INT(11) NOT NULL,
  `day_of_week` ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `slot_duration_minutes` INT(11) NOT NULL DEFAULT 30,
  `is_available` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_doctor_day` (`doctor_id`, `day_of_week`),
  CONSTRAINT `fk_availability_doctor`
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `doctor_availability`
(`doctor_id`, `day_of_week`, `start_time`, `end_time`, `slot_duration_minutes`, `is_available`)
VALUES
(1, 'Saturday', '10:00:00', '14:00:00', 30, 1),
(1, 'Sunday', '10:00:00', '14:00:00', 30, 1),
(1, 'Monday', '10:00:00', '14:00:00', 30, 1),
(1, 'Tuesday', '10:00:00', '14:00:00', 30, 1),
(1, 'Wednesday', '10:00:00', '14:00:00', 30, 1),
(1, 'Thursday', '10:00:00', '14:00:00', 30, 1),
(1, 'Friday', '10:00:00', '14:00:00', 30, 1),
(2, 'Saturday', '15:00:00', '18:00:00', 30, 1),
(2, 'Sunday', '15:00:00', '18:00:00', 30, 1),
(2, 'Monday', '15:00:00', '18:00:00', 30, 1),
(2, 'Tuesday', '15:00:00', '18:00:00', 30, 1),
(2, 'Wednesday', '15:00:00', '18:00:00', 30, 1),
(2, 'Thursday', '15:00:00', '18:00:00', 30, 1),
(2, 'Friday', '15:00:00', '18:00:00', 30, 1);

CREATE TABLE `leave_dates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `doctor_id` INT(11) NOT NULL,
  `leave_date` DATE NOT NULL,
  `reason` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_doctor_leave_date` (`doctor_id`, `leave_date`),
  CONSTRAINT `fk_leave_doctor`
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `leave_dates`
(`id`, `doctor_id`, `leave_date`, `reason`)
VALUES
(1, 1, DATE_ADD(CURDATE(), INTERVAL 5 DAY), 'Personal leave'),
(2, 2, DATE_ADD(CURDATE(), INTERVAL 6 DAY), 'Medical conference');

CREATE TABLE `dependents` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `primary_patient_id` INT(11) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `date_of_birth` DATE DEFAULT NULL,
  `relationship` VARCHAR(50) NOT NULL,
  `blood_group` VARCHAR(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_dependent_patient`
    FOREIGN KEY (`primary_patient_id`) REFERENCES `patients` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `dependents`
(`id`, `primary_patient_id`, `name`, `date_of_birth`, `relationship`, `blood_group`)
VALUES
(1, 1, 'Arafat Tajwar', '2015-01-01', 'Brother', 'O+'),
(2, 2, 'Nabila Hossain', '2017-03-15', 'Sister', 'B+');

CREATE TABLE `appointments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `patient_id` INT(11) DEFAULT NULL,
  `dependent_id` INT(11) DEFAULT NULL,
  `doctor_id` INT(11) DEFAULT NULL,
  `appointment_date` DATE DEFAULT NULL,
  `appointment_time` TIME DEFAULT NULL,
  `reason` TEXT DEFAULT NULL,
  `status` ENUM('pending','confirmed','checked_in','completed','cancelled','no_show') NOT NULL DEFAULT 'pending',
  `booked_by` ENUM('patient','receptionist') NOT NULL DEFAULT 'patient',
  `checkin_time` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_appointment_patient` (`patient_id`),
  KEY `idx_appointment_dependent` (`dependent_id`),
  KEY `idx_appointment_doctor` (`doctor_id`),
  KEY `idx_appointment_date_status` (`appointment_date`, `status`),
  CONSTRAINT `fk_appointment_patient`
    FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
    ON DELETE SET NULL,
  CONSTRAINT `fk_appointment_dependent`
    FOREIGN KEY (`dependent_id`) REFERENCES `dependents` (`id`)
    ON DELETE SET NULL,
  CONSTRAINT `fk_appointment_doctor`
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `appointments`
(`id`, `patient_id`, `dependent_id`, `doctor_id`, `appointment_date`, `appointment_time`, `reason`, `status`, `booked_by`, `checkin_time`)
VALUES
(1, 1, NULL, 1, CURDATE(), '10:00:00', 'Fever and headache', 'confirmed', 'patient', NULL),
(2, 2, NULL, 2, CURDATE(), '15:00:00', 'Chest pain and blood pressure checkup', 'confirmed', 'receptionist', NULL),
(3, 1, 1, 1, CURDATE(), '10:30:00', 'Dependent consultation', 'checked_in', 'receptionist', NOW()),
(4, 2, 2, 2, CURDATE(), '15:30:00', 'Follow-up consultation', 'completed', 'receptionist', NOW()),
(5, 1, NULL, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '11:00:00', 'Regular checkup', 'pending', 'patient', NULL),
(6, 2, NULL, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '16:00:00', 'Heart consultation', 'confirmed', 'patient', NULL);

CREATE TABLE `consultation_notes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` INT(11) NOT NULL,
  `doctor_id` INT(11) NOT NULL,
  `patient_id` INT(11) NOT NULL,
  `symptoms` TEXT DEFAULT NULL,
  `diagnosis` TEXT DEFAULT NULL,
  `prescription` TEXT DEFAULT NULL,
  `follow_up_date` DATE DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notes_doctor` (`doctor_id`),
  KEY `idx_notes_patient` (`patient_id`),
  CONSTRAINT `fk_notes_appointment`
    FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_notes_doctor`
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_notes_patient`
    FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `consultation_notes`
(`id`, `appointment_id`, `doctor_id`, `patient_id`, `symptoms`, `diagnosis`, `prescription`, `follow_up_date`)
VALUES
(1, 4, 2, 2, 'Chest discomfort', 'Mild blood pressure issue', 'Take medicine after meal and avoid oily food', DATE_ADD(CURDATE(), INTERVAL 7 DAY)),
(2, 3, 1, 1, 'Fever and headache', 'Seasonal flu', 'Paracetamol 500mg twice daily for 3 days', DATE_ADD(CURDATE(), INTERVAL 5 DAY));

CREATE TABLE `billing` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` INT(11) DEFAULT NULL,
  `patient_id` INT(11) DEFAULT NULL,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` VARCHAR(50) DEFAULT NULL,
  `payment_status` ENUM('pending','paid') NOT NULL DEFAULT 'pending',
  `paid_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_billing_appointment` (`appointment_id`),
  KEY `idx_billing_patient` (`patient_id`),
  KEY `idx_billing_status` (`payment_status`),
  CONSTRAINT `fk_billing_appointment`
    FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`)
    ON DELETE SET NULL,
  CONSTRAINT `fk_billing_patient`
    FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `billing`
(`id`, `appointment_id`, `patient_id`, `amount`, `payment_method`, `payment_status`, `paid_at`)
VALUES
(1, 1, 1, 500.00, NULL, 'pending', NULL),
(2, 2, 2, 800.00, NULL, 'pending', NULL),
(3, 3, 1, 500.00, NULL, 'pending', NULL),
(4, 4, 2, 800.00, 'cash', 'paid', NOW()),
(5, 5, 1, 500.00, NULL, 'pending', NULL),
(6, 6, 2, 800.00, NULL, 'pending', NULL);

CREATE TABLE `doctor_reviews` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` INT(11) DEFAULT NULL,
  `patient_id` INT(11) NOT NULL,
  `doctor_id` INT(11) NOT NULL,
  `rating` TINYINT(1) NOT NULL,
  `review_text` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_review_doctor` (`doctor_id`),
  KEY `idx_review_patient` (`patient_id`),
  CONSTRAINT `fk_review_appointment`
    FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`)
    ON DELETE SET NULL,
  CONSTRAINT `fk_review_patient`
    FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_review_doctor`
    FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `chk_review_rating` CHECK (`rating` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `doctor_reviews`
(`id`, `appointment_id`, `patient_id`, `doctor_id`, `rating`, `review_text`)
VALUES
(1, 4, 2, 2, 5, 'Doctor was helpful and professional.'),
(2, 3, 1, 1, 4, 'Good consultation and clear explanation.');

CREATE TABLE `announcements` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `author_id` INT(11) DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `body` TEXT NOT NULL,
  `target_role` ENUM('all','patient','doctor','receptionist') NOT NULL DEFAULT 'all',
  `published_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_announcement_author`
    FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `announcements`
(`id`, `author_id`, `title`, `body`, `target_role`)
VALUES
(1, 7, 'Welcome to CareConnect', 'Patients can now browse doctors and book appointments online.', 'patient'),
(2, 7, 'Reception Notice', 'Receptionists should check today schedule and pending bills regularly.', 'receptionist');

CREATE TABLE `appointment_policies` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `minimum_cancellation_notice_hours` INT(11) NOT NULL DEFAULT 24,
  `maximum_advance_booking_days` INT(11) NOT NULL DEFAULT 30,
  `default_consultation_fee` DECIMAL(10,2) NOT NULL DEFAULT 500.00,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `appointment_policies`
(`id`, `minimum_cancellation_notice_hours`, `maximum_advance_booking_days`, `default_consultation_fee`)
VALUES
(1, 24, 30, 500.00),
(2, 12, 15, 800.00);

CREATE TABLE `patient_complaints` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `patient_id` INT(11) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('open','resolved') NOT NULL DEFAULT 'open',
  `admin_response` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resolved_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_complaint_patient`
    FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `patient_complaints`
(`id`, `patient_id`, `subject`, `message`, `status`, `admin_response`, `resolved_at`)
VALUES
(1, 1, 'Long waiting time', 'I had to wait longer than expected.', 'open', NULL, NULL),
(2, 2, 'Payment issue', 'I need help with my billing record.', 'resolved', 'Issue checked and resolved.', NOW());

CREATE TABLE `activity_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `role` VARCHAR(50) DEFAULT NULL,
  `action` VARCHAR(255) NOT NULL,
  `details` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_activity_user` (`user_id`),
  CONSTRAINT `fk_activity_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `activity_logs`
(`id`, `user_id`, `role`, `action`, `details`)
VALUES
(1, 7, 'admin', 'Database initialized', 'Hospital system database created with seed data.'),
(2, 1, 'receptionist', 'Receptionist dashboard accessed', 'Mahir accessed receptionist dashboard.');

CREATE TABLE `receptionist_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `receptionist_name` VARCHAR(100) DEFAULT NULL,
  `action_performed` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `receptionist_logs`
(`id`, `receptionist_name`, `action_performed`)
VALUES
(1, 'Mahir', 'Checked receptionist dashboard.'),
(2, 'Mahir', 'Processed sample front desk operation.');

SET FOREIGN_KEY_CHECKS = 1;

COMMIT;