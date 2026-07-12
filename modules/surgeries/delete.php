<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM surgeries WHERE surgery_id = $id");
header("Location: view.php");
exit();
