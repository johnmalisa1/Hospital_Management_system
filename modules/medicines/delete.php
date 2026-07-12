<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit(); }
include "../../config/db.php";
require_once "../../includes/classes/Medicine.php";

$id = $_GET['id'];
$medicine = new Medicine($db);
$medicine->deleteMedicine($id);
header("Location: view.php");
