<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['role'], $_SESSION['name'])) {
    header("Location: index.php");
    exit();
}

require_once 'config/database.php';

$role = $_SESSION['role'];
$action = $_GET['action'] ?? 'dashboard';

switch ($role) {
    case 'admin':
        require_once 'models/AdminModel.php';
        require_once 'controllers/AdminController.php';
        $controller = new AdminController($conn);
        break;

    case 'receptionist':
        require_once 'models/ReceptionistModel.php';
        require_once 'controllers/ReceptionistController.php';
        $controller = new ReceptionistController($conn);
        break;

    case 'doctor':
        require_once 'models/DoctorModel.php';
        require_once 'controllers/DoctorController.php';
        $controller = new DoctorController($conn);
        break;

    case 'patient':
        require_once 'models/PatientModel.php';
        require_once 'controllers/PatientController.php';
        $controller = new PatientController($conn);
        break;

    default:
        session_destroy();
        header("Location: index.php");
        exit();
}

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    die("Action not found.");
}
?>