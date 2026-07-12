<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Equipment.php';

$equipment = new Equipment($db);
$id = $_GET['id'];
$equipment->deleteEquipment($id);

header("Location: view.php");
exit();
