<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM attendance WHERE attendance_id = $id");
header("Location: view.php");
exit();
