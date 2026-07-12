<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/BloodBank.php';

$bloodBank = new BloodBank($db);
$id = $_GET['id'];
$bloodBank->deleteUnit($id);

header("Location: view.php");
exit();
