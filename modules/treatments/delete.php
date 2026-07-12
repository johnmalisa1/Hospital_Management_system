<?php
include "../../config/db.php";
require_once "../../includes/classes/Treatment.php";
$id = $_GET['id'];
$treatment = new Treatment($db);
$treatment->deleteTreatment($id);
header("Location: view.php");
exit();
