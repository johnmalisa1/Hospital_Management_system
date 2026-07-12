<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit(); }
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Notification.php';

$notification = new Notification($db);
$notification->deleteNotification($_GET['id']);
header("Location: view.php");
