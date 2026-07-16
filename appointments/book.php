<?php
session_start();
include "../config/db.php";
require_once "../includes/classes/Appointment.php";
require_once "../includes/classes/User.php";

// Check if patient is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: ../login_patient.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
$success = "";
$appointment = new Appointment($db);

// Fetch all doctors
$user = new User($db);
$doctors = $user->getDoctorUserAccounts();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];

    if ($appointment->bookAppointment($patient_id, $doctor_id, $appointment_date)) {
        // Redirect with success
        header("Location: ../patient_dashboard.php?success=1");
        exit();
    } else {
        $success = "Failed to book appointment. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-brand">
            <i class="fas fa-calendar-plus"></i>
            <h2>Book Appointment</h2>
        </div>

        <?php if (!empty($success)) echo "<p class='error'>$success</p>"; ?>

        <form method="POST" style="box-shadow: none; padding: 0; width: 100%;">
            <label>Select Doctor:</label>
            <select name="doctor_id" required>
                <option value="">-- Choose Doctor --</option>
                <?php while ($doc = $doctors->fetch_assoc()): ?>
                    <option value="<?= $doc['id'] ?>"><?= htmlspecialchars($doc['username']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Appointment Date:</label>
            <input type="date" name="appointment_date" required>

            <button type="submit"><i class="fas fa-check"></i> Book</button>
        </form>

        <div style="text-align: center;">
            <a href="../patient_dashboard.php" class="back-home"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
