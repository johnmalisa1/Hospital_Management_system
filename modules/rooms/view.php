<?php
session_start();
include "../../config/db.php";
include "../../navbar.php";
require_once __DIR__ . '/../../includes/classes/Room.php';

$room = new Room($db);
$result = $room->getAllRooms();
?>

<div style="margin-left:230px; padding:20px; max-width:calc(100% - 230px);">
    <h2 style="text-align:center;">🏥 Hospital Rooms</h2>

    <div style="overflow-x:auto;">
        <table border="1" cellpadding="10" style="width:100%; background:white; box-shadow:0 0 10px #ccc; border-collapse:collapse;">
            <tr style="background:#007bff; color:white;">
                <th>ID</th><th>Room Number</th><th>Available</th><th>Actions</th>
            </tr>
            <?php
            while ($row = $result->fetch_assoc()) {
                $availability = $row['is_available'] ? "Yes" : "No";
                echo "<tr>
                    <td>{$row['room_id']}</td>
                    <td>{$row['room_number']}</td>
                    <td>{$availability}</td>
                    <td>
                        <a href='edit.php?id={$row['room_id']}' style='color:green;'>Edit</a> |
                        <a href='delete.php?id={$row['room_id']}' style='color:red;' onclick='return confirm(\"Delete this room?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</div>
