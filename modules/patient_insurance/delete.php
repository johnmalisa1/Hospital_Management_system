<?php
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/PatientInsurance.php';

$patientInsurance = new PatientInsurance($db);
$id = $_GET['id'];
$patientInsurance->deleteInsurance($id);
header("Location: view.php");
exit();
