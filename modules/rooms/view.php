<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Room.php';

$room = new Room($db);
$result = $room->getAllRooms();
?>


    <h2 style="text-align:center;">🏥 Hospital Rooms</h2>

    <div class="table-responsive">
        <table>
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

