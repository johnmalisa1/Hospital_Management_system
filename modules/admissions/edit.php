<?php
session_start();
include "../../config/db.php";
include "../../navbar.php";

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM admissions WHERE admission_id = $id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_id = $_POST['patient_id'];
    $room_id = $_POST['room_id'];
    $date = $_POST['admission_date'];

    $stmt = $conn->prepare("UPDATE admissions SET patient_id=?, room_id=?, admission_date=? WHERE admission_id=?");
    $stmt->bind_param("iisi", $patient_id, $room_id, $date, $id);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">✏️ Edit Admission</h2>
<form method="POST" style="width:400px; margin:auto; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Patient:</label>
    <select name="patient_id" required style="width:100%; padding:10px;">
        <?php
        $patients = $conn->query("SELECT * FROM patients");
        while ($p = $patients->fetch_assoc()) {
            $sel = $p['patient_id'] == $row['patient_id'] ? "selected" : "";
            echo "<option value='{$p['patient_id']}' $sel>{$p['name']}</option>";
        }
        ?>
    </select><br><br>

    <label>Room:</label>
    <select name="room_id" required style="width:100%; padding:10px;">
        <?php
        $rooms = $conn->query("SELECT * FROM rooms");
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
