<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM equipment WHERE equipment_id = $id");
header("Location: view.php");
exit();
