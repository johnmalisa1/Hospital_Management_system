<?php
session_start();
include "../../config/db.php";
include "../../navbar.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $room_id = $_POST['room_id'];
    $admission_date = $_POST['admission_date'];

    $stmt = $conn->prepare("INSERT INTO admissions (patient_id, room_id, admission_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $patient_id, $room_id, $admission_date);
    $stmt->execute();

    // Update room availability to 0 (occupied)
    $conn->query("UPDATE rooms SET is_available = 0 WHERE room_id = $room_id");

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
            $patients = $conn->query("SELECT * FROM patients");
            while ($p = $patients->fetch_assoc()) {
                echo "<option value='{$p['patient_id']}'>{$p['name']}</option>";
            }
            ?>
        </select><br><br>

        <label>Room:</label>
        <select name="room_id" required style="width:100%; padding:10px;">
            <option value="">-- Select Room --</option>
            <?php
            $rooms = $conn->query("SELECT * FROM rooms WHERE is_available = 1");
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
<?php
session_start();
include "../../config/db.php";
include "../../navbar.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_id = $_POST['patient_id'];
    $room_id = $_POST['room_id'];
    $date = $_POST['admission_date'];

    $stmt = $conn->prepare("INSERT INTO admissions (patient_id, room_id, admission_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $patient_id, $room_id, $date);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">🏥 Admit Patient</h2>
<form method="POST" style="width:400px; margin:auto; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Patient:</label>
    <select name="patient_id" required style="width:100%; padding:10px;">
        <option value="">-- Select Patient --</option>
        <?php
        $patients = $conn->query("SELECT * FROM patients");
        while ($p = $patients->fetch_assoc()) {
            echo "<option value='{$p['patient_id']}'>{$p['name']}</option>";
        }
        ?>
    </select><br><br>
