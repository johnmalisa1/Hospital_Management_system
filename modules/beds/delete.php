<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM beds WHERE bed_id = $id");
header("Location: view.php");
exit();
