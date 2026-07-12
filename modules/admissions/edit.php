<?php
session_start();
include "../../config/db.php";
include "../../navbar.php";
require_once __DIR__ . '/../../includes/classes/Admission.php';
require_once __DIR__ . '/../../includes/classes/Patient.php';

$admission = new Admission($db);
 $patient = new Patient($db);

$id = $_GET['id'];
$row = $admission->getAdmissionById($id);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $admission->updateAdmission(
        $id,
        $_POST['patient_id'],
        $_POST['room_id'],
        $_POST['admission_date']
    );
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">✏️ Edit Admission</h2>
<form method="POST" style="width:400px; margin:auto; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Patient:</label>
    <select name="patient_id" required style="width:100%; padding:10px;">
        <?php
        $patients = $patient->getAllPatients();
        while ($p = $patients->fetch_assoc()) {
            $sel = $p['patient_id'] == $row['patient_id'] ? "selected" : "";
            echo "<option value='{$p['patient_id']}' $sel>{$p['name']}</option>";
        }
        ?>
    </select><br><br>

    <label>Room:</label>
    <select name="room_id" required style="width:100%; padding:10px;">
        <?php
        $rooms = $admission->getAllRooms();
        while ($r = $rooms->fetch_assoc()) {
            $sel = $r['room_id'] == $row['room_id'] ? "selected" : "";
            echo "<option value='{$r['room_id']}' $sel>{$r['room_number']}</option>";
        }
        ?>
    </select><br><br>

    <label>Admission Date:</label>
    <input type="date" name="admission_date" value="<?= $row['admission_date'] ?>" required style="width:100%; padding:10px;"><br><br>

    <button type="submit" style="padding:10px 20px; background:#007bff; color:white; border:none;">Update</button>
</form>
