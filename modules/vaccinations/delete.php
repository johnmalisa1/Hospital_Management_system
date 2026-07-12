<?php
include "../../config/db.php";
require_once "../../includes/classes/Vaccination.php";
$id = $_GET['id'];
$vaccination = new Vaccination($db);
$vaccination->deleteVaccination($id);
header("Location: view.php");
exit();
