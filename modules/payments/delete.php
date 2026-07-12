<?php
include "../../config/db.php";
require_once "../../includes/classes/Payment.php";
$id = $_GET['id'];
$payment = new Payment($db);
$payment->deletePayment($id);
header("Location: view.php");
exit();
