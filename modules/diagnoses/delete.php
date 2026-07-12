<?php
include "../../config/db.php";
require_once "../../includes/classes/Diagnosis.php";
$id = $_GET['id'];
$diagnosisService = new Diagnosis($db);
$diagnosisService->deleteDiagnosis($id);
header("Location: view.php");
exit();
