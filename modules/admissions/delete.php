<?php
session_start();
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM admissions WHERE admission_id = $id");
header("Location: view.php");
exit();
?>
