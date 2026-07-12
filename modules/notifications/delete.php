<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit(); }
include "../../config/db.php";
$conn->query("DELETE FROM notifications WHERE id = " . $_GET['id']);
header("Location: view.php");
