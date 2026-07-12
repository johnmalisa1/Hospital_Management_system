<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM ambulances WHERE ambulance_id = $id");
header("Location: view.php");
exit();
