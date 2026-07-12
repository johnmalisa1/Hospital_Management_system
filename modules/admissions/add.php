<?php
session_start();
include "../../config/db.php";
include "../../navbar.php";
require_once __DIR__ . '/../../includes/classes/Admission.php';
require_once __DIR__ . '/../../includes/classes/Patient.php';

$admission = new Admission($db);
$patient = new Patient($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admission->addAdmission(
        $_POST['patient_id'],
        $_POST['room_id'],
        $_POST['admission_date']
    );

    header("Location: view.php");
    exit();
}
?>

<div style="margin-left:230px; padding:20px; max-width:calc(100% - 230px);">
    <h2 style="text-align:center;">➕ Admit Patient</h2>

    <form method="POST" style="width:400px; margin:auto; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px #ccc;">
        <label>Patient:</label>
        <select name="patient_id" required style="width:100%; padding:10px;">
            <option value="">-- Select Patient --</option>
            <?php
            $patients = $patient->getAllPatients();
            while ($p = $patients->fetch_assoc()) {
                echo "<option value='{$p['patient_id']}'>{$p['name']}</option>";
            }
            ?>
        </select><br><br>

        <label>Room:</label>
        <select name="room_id" required style="width:100%; padding:10px;">
            <option value="">-- Select Room --</option>
            <?php
            $rooms = $admission->getAvailableRooms();
            while ($r = $rooms->fetch_assoc()) {
                echo "<option value='{$r['room_id']}'>{$r['room_number']}</option>";
            }
            ?>
        </select><br><br>

        <label>Admission Date:</label>
        <input type="date" name="admission_date" required style="width:100%; padding:10px;"><br><br>

        <button type="submit" style="padding:10px 20px; background:#28a745; color:white; border:none; border-radius:5px;">Admit</button>
    </form>
</div>

