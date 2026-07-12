<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Pharmacy.php";
$id = $_GET['id'];
$pharmacy = new Pharmacy($db);
$pharmacy->deleteMedicine($id);
header("Location: view.php");
exit();
?>
