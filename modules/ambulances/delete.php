<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Ambulance.php';

$ambulance = new Ambulance($db);
$ambulance->deleteAmbulance($_GET['id']);
header("Location: view.php");
exit();
