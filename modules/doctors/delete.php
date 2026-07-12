<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Doctor.php";

$id = $_GET['id'];
$doctor = new Doctor($db);
$doctor->deleteDoctor($id);

header("Location: view.php");
exit();
?>
