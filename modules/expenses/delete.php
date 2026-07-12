<?php
include "../../config/db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM expenses WHERE expense_id = $id");
header("Location: view.php");
exit();
