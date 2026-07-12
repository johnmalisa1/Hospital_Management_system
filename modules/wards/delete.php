<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";

$id = $_GET['id'];
$conn->query("DELETE FROM wards WHERE ward_id = $id");

header("Location: view.php");
exit();
