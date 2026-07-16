<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Appointment.php";
require_once "../../includes/classes/User.php";

$appointment = new Appointment($db);
$userService = new User($db);
$username = $_SESSION['username'];
$pid = $userService->getPatientIdByUsername($username);
?>

<h2 style="text-align:center;">My Appointments</h2>

<table style="width:90%; margin:auto; background:white; border-collapse:collapse; box-shadow:0 0 8px #ccc;">
    <tr style="background:#007bff; color:white;">
        <th>Doctor</th>
        <th>Date</th>
        <th>Status</th>
    </tr>

<?php
$result = $appointment->getAppointmentsByPatient($pid);

while ($row = $result->fetch_assoc()):
?>
<tr>
    <td><?= $row['doctor_name'] ?></td>
   <td><?= $row['appointment_date'] ?></td>
    <td><?= $row['status'] ?></td>
</tr>
<?php endwhile; ?>
</table>
