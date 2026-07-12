<?php
session_start();
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Expense.php';

$expense = new Expense($db);
$expense->deleteExpense($_GET['id']);
header("Location: view.php");
exit();
