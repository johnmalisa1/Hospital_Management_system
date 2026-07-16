<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Appointment.php";
require_once "../../includes/classes/Patient.php";
require_once "../../includes/classes/User.php";

$appointmentService = new Appointment($db);
$patient = new Patient($db);
$userService = new User($db);

$id = $_GET['id'] ?? 0;
$appointment = $appointmentService->getAppointmentById($id);

if (!$appointment) {
    die("Appointment not found.");
}

// Get all patients and doctors
$patients = $patient->getAllPatients();
$doctors = $userService->getDoctorUserAccounts();

// Handle update form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['appointment_date'];

    $appointmentService->updateAppointment($id, $patient_id, $doctor_id, $date);

    header("Location: view.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Appointment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="sidebar-page">
<?php include "../../templates/header.php"; ?>

<div class="form-wrapper">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Appointments</a>
    <form method="POST">
        <h2>Edit Appointment</h2>

        <label>Patient:</label>
        <select name="patient_id" required>
            <option value="">-- Select Patient --</option>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <option value="<?= $p['patient_id'] ?>" <?= ($p['patient_id'] == $appointment['patient_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Doctor:</label>
        <select name="doctor_id" required>
            <option value="">-- Select Doctor --</option>
            <?php while ($d = $doctors->fetch_assoc()): ?>
                <option value="<?= $d['id'] ?>" <?= ($d['id'] == $appointment['doctor_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['username']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Appointment Date:</label>
        <input type="date" name="appointment_date" value="<?= $appointment['appointment_date'] ?>" required>

        <button type="submit">Update Appointment</button>
    </form>
</div>

<?php include "../../templates/footer.php"; ?>
</body>
</html>
