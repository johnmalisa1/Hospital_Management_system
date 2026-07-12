<?php
session_start();
include "../../config/db.php";
include "../../navbar.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = $_POST['room_number'];
    $is_available = $_POST['is_available'];

    $stmt = $conn->prepare("INSERT INTO rooms (room_number, is_available) VALUES (?, ?)");
    $stmt->bind_param("si", $room_number, $is_available);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">➕ Add Room</h2>
<form method="POST" style="width:400px; margin:auto; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Room Number:</label>
    <input type="text" name="room_number" required style="width:100%; padding:10px;"><br><br>

    <label>Available?</label>
    <select name="is_available" required style="width:100%; padding:10px;">
        <option value="1">Yes</option>
        <option value="0">No</option>
    </select><br><br>

    <button type="submit" style="padding:10px 20px; background:#28a745; color:white; border:none;">Save Room</button>
</form>
