<?php
session_start();
include "../../config/db.php";
include "../../navbar.php";
require_once __DIR__ . '/../../includes/classes/Room.php';

$room = new Room($db);

$id = $_GET['id'];
$row = $room->getRoomById($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room->updateRoom($id, $_POST['room_number'], $_POST['is_available']);
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">✏️ Edit Room</h2>
<form method="POST" style="width:400px; margin:auto; background:white; padding:20px; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Room Number:</label>
    <input type="text" name="room_number" value="<?= $row['room_number'] ?>" required style="width:100%; padding:10px;"><br><br>

    <label>Available?</label>
    <select name="is_available" required style="width:100%; padding:10px;">
        <option value="1" <?= $row['is_available'] == 1 ? 'selected' : '' ?>>Yes</option>
        <option value="0" <?= $row['is_available'] == 0 ? 'selected' : '' ?>>No</option>
    </select><br><br>

    <button type="submit" style="padding:10px 20px; background:#007bff; color:white; border:none;">Update Room</button>
</form>
