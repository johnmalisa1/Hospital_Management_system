<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM ambulance_requests WHERE request_id = $id");
header("Location: view.php");
exit();
