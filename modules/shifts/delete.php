<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM shifts WHERE shift_id = $id");
header("Location: view.php");
exit();
