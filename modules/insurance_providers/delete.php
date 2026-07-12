<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM insurance_providers WHERE provider_id = $id");
header("Location: view.php");
exit();
