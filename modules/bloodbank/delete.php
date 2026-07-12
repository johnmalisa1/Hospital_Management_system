<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM blood_bank WHERE unit_id = $id");
header("Location: view.php");
exit();
