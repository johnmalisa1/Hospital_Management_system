<?php
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/InsuranceProvider.php';

$insuranceProvider = new InsuranceProvider($db);
$id = $_GET['id'];
$insuranceProvider->deleteProvider($id);
header("Location: view.php");
exit();
