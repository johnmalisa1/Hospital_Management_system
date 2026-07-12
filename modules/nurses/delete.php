<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM nurses WHERE nurse_id = $id");
header("Location: view.php");
exit();
