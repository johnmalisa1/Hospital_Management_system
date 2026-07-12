<?php
session_start();
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Admission.php';

$admission = new Admission($db);
$id = $_GET['id'];
$admission->deleteAdmission($id);
header("Location: view.php");
exit();
?>
