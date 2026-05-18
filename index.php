<?php
$hospital_phone = "+880 1234 567890";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CareConnect Hospital | Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/index.css">
</head>

<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img 
                src="assets/images/logo.png"
                alt="CareConnect Logo"
                width="45"
                height="45"
                class="me-2"
            >
            <span class="fw-bold">CareConnect</span>
        </a>

        <button 
            class="navbar-toggler" 
            type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#services">Services</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#heroes">Doctors</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>

                <li class="nav-item dropdown ms-lg-3">
                    <button 
                        class="btn btn-outline-primary dropdown-toggle"
                        type="button"
                        id="loginDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        Login
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="loginDropdown">
                        <li>
                            <a class="dropdown-item" href="login.php?role=patient">Patient</a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="login.php?role=doctor">Doctor</a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="login.php?role=receptionist">Receptionist</a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="login.php?role=admin">Admin</a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a href="register.php" class="btn btn-primary">
                        Register
                    </a>
                </li>

                <li class="nav-item dropdown ms-lg-2 mt-2 mt-lg-0">
                    <button 
                        class="btn btn-outline-secondary dropdown-toggle"
                        type="button"
                        id="searchDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="bi bi-search"></i>
                    </button>

                    <div 
                        class="dropdown-menu dropdown-menu-end p-3 shadow border-0"
                        aria-labelledby="searchDropdown"
                        style="min-width: 280px;"
                    >
                        <form action="search.php" method="GET">
                            <div class="input-group">
                                <input 
                                    type="text"
                                    name="q"
                                    class="form-control"
                                    placeholder="Search doctors..."
                                    required
                                >

                                <button class="btn btn-primary" type="submit">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>
                </li>

            </ul>

        </div>
    </div>
</nav>

<section class="hero" id="home">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">

                <h1>Your Health, Our Priority</h1>

                <p>
                    Book appointments with trusted specialists, access online reports,
                    and consult doctors remotely.
                </p>

                <div class="row mt-5 g-3">

                    <div class="col-md-6 col-lg-3">
                        <a href="public_doctors.php" class="text-decoration-none">
                            <div class="bg-white text-dark rounded-4 p-3 text-center shadow-sm h-100">
                                <i class="bi bi-person-badge fs-2 text-primary"></i>
                                <h6 class="mt-2 mb-0">Find a Doctor</h6>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <a href="login.php?role=patient" class="text-decoration-none">
                            <div class="bg-white text-dark rounded-4 p-3 text-center shadow-sm h-100">
                                <i class="bi bi-calendar-check fs-2 text-primary"></i>
                                <h6 class="mt-2 mb-0">Book Appointment</h6>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <a href="login.php?role=patient" class="text-decoration-none">
                            <div class="bg-white text-dark rounded-4 p-3 text-center shadow-sm h-100">
                                <i class="bi bi-file-earmark-medical fs-2 text-primary"></i>
                                <h6 class="mt-2 mb-0">Online Report</h6>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <button 
                            type="button"
                            onclick="showTeleNumber()"
                            class="w-100 border-0 bg-transparent p-0"
                        >
                            <div class="bg-white text-dark rounded-4 p-3 text-center shadow-sm h-100">
                                <i class="bi bi-camera-video fs-2 text-primary"></i>
                                <h6 class="mt-2 mb-0">Tele Online</h6>
                            </div>
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>

<section id="services">
    <div class="container text-center">

        <h2 class="section-title">Why Choose CareConnect?</h2>
        <p class="section-subtitle">
            A modern healthcare platform for smarter appointment management.
        </p>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100 rounded-4">
                    <i class="bi bi-calendar-check fs-1 text-primary"></i>
                    <h5 class="mt-3">Easy Booking</h5>
                    <p>Schedule appointments in just a few clicks.</p>
                    <a href="login.php?role=patient" class="btn btn-outline-primary mt-auto">
                        Book Now
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100 rounded-4">
                    <i class="bi bi-file-earmark-medical fs-1 text-primary"></i>
                    <h5 class="mt-3">Digital Reports</h5>
                    <p>View prescriptions and reports online anytime.</p>
                    <a href="login.php?role=patient" class="btn btn-outline-primary mt-auto">
                        Patient Login
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100 rounded-4">
                    <i class="bi bi-camera-video fs-1 text-primary"></i>
                    <h5 class="mt-3">Telemedicine</h5>
                    <p>Consult with doctors remotely from your home.</p>

                    <button 
                        type="button"
                        onclick="showTeleNumber()"
                        class="btn btn-outline-primary mt-auto"
                    >
                        Get Started
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="stats py-5">
    <div class="container">
        <div class="row text-center">

            <div class="col-md-3">
                <h2>150+</h2>
                <p>Specialist Doctors</p>
            </div>

            <div class="col-md-3">
                <h2>20+</h2>
                <p>Departments</p>
            </div>

            <div class="col-md-3">
                <h2>25K+</h2>
                <p>Patients Served</p>
            </div>

            <div class="col-md-3">
                <h2>4.9★</h2>
                <p>Average Rating</p>
            </div>

        </div>
    </div>
</section>

<section id="heroes">
    <div class="container">

        <div class="text-center">
            <h2 class="section-title">Our Heroes</h2>
            <p class="section-subtitle">
                Dedicated doctors who make CareConnect special
            </p>
        </div>

        <div class="row g-4 mt-3">

            <div class="col-md-4 col-lg-2-4">
                <a href="public_doctors.php" class="text-decoration-none text-dark d-block h-100">
                    <div class="card doctor-card shadow-sm h-100">
                        <img src="assets/images/doctor1.jpg" class="card-img-top" alt="Doctor">
                        <div class="card-body text-center">
                            <h6 class="mb-1">Dr. Sarah Ahmed</h6>
                            <small class="text-muted">Cardiologist</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-lg-2-4">
                <a href="public_doctors.php" class="text-decoration-none text-dark d-block h-100">
                    <div class="card doctor-card shadow-sm h-100">
                        <img src="assets/images/doctor2.jpg" class="card-img-top" alt="Doctor">
                        <div class="card-body text-center">
                            <h6 class="mb-1">Dr. Rahman Karim</h6>
                            <small class="text-muted">Neurologist</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-lg-2-4">
                <a href="public_doctors.php" class="text-decoration-none text-dark d-block h-100">
                    <div class="card doctor-card shadow-sm h-100">
                        <img src="assets/images/doctor3.jpg" class="card-img-top" alt="Doctor">
                        <div class="card-body text-center">
                            <h6 class="mb-1">Dr. Nusrat Jahan</h6>
                            <small class="text-muted">Pediatrician</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-2-4">
                <a href="public_doctors.php" class="text-decoration-none text-dark d-block h-100">
                    <div class="card doctor-card shadow-sm h-100">
                        <img src="assets/images/doctor4.jpg" class="card-img-top" alt="Doctor">
                        <div class="card-body text-center">
                            <h6 class="mb-1">Dr. Mehedi Hasan</h6>
                            <small class="text-muted">Orthopedic Specialist</small>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-2-4">
                <a href="public_doctors.php" class="text-decoration-none text-dark d-block h-100">
                    <div class="card doctor-card shadow-sm h-100">
                        <img src="assets/images/doctor5.jpg" class="card-img-top" alt="Doctor">
                        <div class="card-body text-center">
                            <h6 class="mb-1">Dr. Ayesha Rahman</h6>
                            <small class="text-muted">Dermatologist</small>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <div class="text-center mt-4">
            <a href="public_doctors.php" class="btn btn-primary">
                View All Doctors
            </a>
        </div>

    </div>
</section>

<section id="about" style="background: #f7fbfc;">
    <div class="container text-center">
        <h2 class="section-title">About Us</h2>

        <p class="lead">
            CareConnect Hospital Appointment Booking System is designed to simplify healthcare access
            by connecting patients, doctors, receptionists, and administrators on one secure platform.
        </p>

        <a href="register.php" class="btn btn-primary mt-3">
            Create Patient Account
        </a>
    </div>
</section>

<section class="map-section py-5">
    <div class="container text-center">

        <h2 class="section-title">Find Us on Map</h2>
        <p class="section-subtitle">Visit CareConnect Hospital easily using Google Maps</p>

        <div class="map-container mt-4">
            <iframe
                src="https://www.google.com/maps?q=Evercare+Hospital+Dhaka&output=embed"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

    </div>
</section>

<footer id="contact">
    <div class="container">

        <div class="row g-4">

            <div class="col-lg-4">
                <h4>CareConnect Hospital</h4>
                <p>
                    A complete digital healthcare platform for booking appointments and managing patient care.
                </p>
            </div>

            <div class="col-lg-4">
                <h5>Visit Us</h5>
                <p>
                    <i class="bi bi-geo-alt"></i>
                    Plot 81, Block E, Bashundhara R/A, Dhaka 1229
                </p>
            </div>

            <div class="col-lg-4">
                <h5>Contact Info</h5>
                <p><i class="bi bi-telephone"></i> <?= htmlspecialchars($hospital_phone); ?></p>
                <p><i class="bi bi-envelope"></i> info@careconnect.com</p>
                <p><i class="bi bi-clock"></i> Open 24/7</p>
            </div>

        </div>

        <hr class="border-secondary mt-4">

        <p class="text-center mb-0">
            &copy; <?= date('Y'); ?> CareConnect Hospital. All Rights Reserved.
        </p>

    </div>
</footer>

<script>
function showTeleNumber() {
    alert("For Tele Online service, please call: <?= htmlspecialchars($hospital_phone); ?>");
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>