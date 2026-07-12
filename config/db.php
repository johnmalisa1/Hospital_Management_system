<?php
require_once __DIR__ . '/../includes/classes/Database.php';

$host = "localhost";
$username = "root";
$password = "";
$database = "hospital_db";

$db = new Database($host, $username, $password, $database);

// Kept for pages that have not yet been refactored.
$conn = $db->getConnection();
?>
