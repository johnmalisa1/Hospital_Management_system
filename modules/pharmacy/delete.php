<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Medicine.php";
$id = $_GET['id'];
$medicine = new Medicine($db);
$medicine->deleteMedicine((int)$id);
header("Location: view.php");
exit();
?>
