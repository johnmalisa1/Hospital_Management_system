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

$appointment = new Appointment($db);
$patient = new Patient($db);
$userService = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];

    $appointment->bookAppointment($patient_id, $doctor_id, $appointment_date);

    $_SESSION['message'] = "Appointment added successfully.";
    header("Location: view.php");
    exit();
}

$patients = $patient->getAllPatients();
$doctors = $userService->getDoctorUserAccounts();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Appointment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="sidebar-page">
<?php include "../../templates/header.php"; ?>

<div class="form-container">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Appointments</a>
    <h2 style="text-align:center;"><i class="fas fa-calendar-plus"></i> Add New Appointment</h2>
    <form method="POST">
        <label for="patient_id">Select Patient</label>
        <select name="patient_id" required>
            <option value="">-- Select Patient --</option>
            <?php while ($row = $patients->fetch_assoc()): ?>
                <option value="<?= $row['patient_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="doctor_id">Select Doctor</label>
        <select name="doctor_id" required>
            <option value="">-- Select Doctor --</option>
            <?php while ($row = $doctors->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['username']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="appointment_date">Appointment Date</label>
        <input type="date" name="appointment_date" required>

        <button type="submit"><i class="fas fa-save"></i> Save Appointment</button>
    </form>
</div>

<?php include "../../templates/footer.php"; ?>
</body>
</html>
