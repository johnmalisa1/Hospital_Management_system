<?php
session_start();
include "../../config/db.php";
$conn->query("DELETE FROM invoice_items WHERE item_id = " . $_GET['id']);
header("Location: view.php");
