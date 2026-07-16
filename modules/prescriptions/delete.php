<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Prescription.php";
$prescription = new Prescription($db);
$prescription->deletePrescription($_GET['id']);
header("Location: view.php");
exit();
