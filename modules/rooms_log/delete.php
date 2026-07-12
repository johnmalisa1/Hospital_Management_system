<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM rooms_log WHERE log_id = $id");
header("Location: view.php");
exit();
