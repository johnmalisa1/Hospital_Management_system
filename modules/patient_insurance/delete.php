<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM patient_insurance WHERE id = $id");
header("Location: view.php");
exit();
