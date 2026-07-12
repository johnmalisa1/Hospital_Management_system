<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/LabTest.php";
$id = $_GET['id'];
$labTest = new LabTest($db);
$labTest->deleteLabTest($id);
header("Location: view.php");
exit();
?>
