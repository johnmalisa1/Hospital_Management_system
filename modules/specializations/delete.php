<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM specializations WHERE specialization_id = $id");
header("Location: view.php");
exit();
