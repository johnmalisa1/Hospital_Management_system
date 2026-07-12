<?php
session_start();
include "../config/db.php";
require_once "../includes/classes/Appointment.php";

// Check if patient is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: ../login_patient.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
$success = "";
$appointment = new Appointment($db);

// Fetch all doctors
$doctors = $appointment->getDoctorUserAccounts();

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
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .form-container {
            width: 400px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background: #007bff;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            font-size: 16px;
            border-radius: 5px;
        }

        button:hover {
            background: #0056b3;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Book Appointment</h2>

    <?php if (!empty($success)) echo "<p class='error'>$success</p>"; ?>

    <form method="POST">
        <label>Select Doctor:</label>
        <select name="doctor_id" required>
            <option value="">-- Choose Doctor --</option>
            <?php while ($doc = $doctors->fetch_assoc()): ?>
                <option value="<?= $doc['id'] ?>"><?= htmlspecialchars($doc['username']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Appointment Date:</label>
        <input type="date" name="appointment_date" required>

        <button type="submit">Book</button>
    </form>

    <a class="back-link" href="../patient_dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
