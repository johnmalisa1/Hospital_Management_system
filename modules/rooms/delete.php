<?php
session_start();
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Room.php';

$room = new Room($db);

if (isset($_GET['id'])) {
    $room->deleteRoom($_GET['id']);
}
header("Location: view.php");
exit();
