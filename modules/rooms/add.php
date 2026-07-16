<?php
session_start();
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Room.php';
$room = new Room($db);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room->addRoom($_POST['room_number'], $_POST['is_available']);
    header("Location: view.php");
    exit();
}

include "../../templates/header.php";
?>

<a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to rooms</a>
<h2 style="text-align:center;">? Add Room</h2>
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
</div>
