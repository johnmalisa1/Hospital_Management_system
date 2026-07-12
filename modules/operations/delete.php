<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM operation_schedules WHERE schedule_id = $id");
header("Location: view.php");
exit();
