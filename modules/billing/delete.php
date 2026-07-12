<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Billing.php";
$id = $_GET['id'];
$billing = new Billing($db);
$billing->deleteBill($id);
header("Location: view.php");
exit();
?>
