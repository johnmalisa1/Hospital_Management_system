<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Ward.php';

$ward = new Ward($db);

$ward->deleteWard($_GET['id']);
header("Location: view.php");
exit();
