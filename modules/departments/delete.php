<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Department.php';

$department = new Department($db);

if (isset($_GET['id'])) {
    $department->deleteDepartment($_GET['id']);
}
header("Location: view.php");
exit();
