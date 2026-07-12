<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/Patient.php";
include "../../templates/header.php";

$patient = new Patient($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = $_POST['name'];
    $gender  = $_POST['gender'];
    $dob     = $_POST['dob'];
    $phone   = $_POST['phone'];
    $address = $_POST['address'];

    if ($patient->addPatient($name, $gender, $dob, $phone, $address)) {
        header("Location: view.php");
        exit();
    } else {
        $error = "Failed to add patient.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Patient</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="main-content">
    <h2 class="center-text">➕ Add New Patient</h2>

    <?php if (isset($error)): ?>
        <p style="color:red; text-align:center;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="form-container">
    <label>Full Name</label>
    <input type="text" name="name" required>

    <label>Gender</label>
    <select name="gender" required>
        <option value="">-- Select Gender --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>

    <label>Date of Birth</label>
    <input type="date" name="dob" required>

    <label>Phone</label>
    <input type="text" name="phone" required>

    <label>Address</label>
    <input type="text" name="address" required>

    <button type="submit">Add Patient</button>
</form>

</div>

<?php include "../../templates/footer.php"; ?>
</body>
</html>
