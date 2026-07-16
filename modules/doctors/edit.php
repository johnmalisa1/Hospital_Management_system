<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/Doctor.php";
$doctor = new Doctor($db);
if (!isset($_GET['id'])) {
    die("Missing doctor ID.");
}
$id = intval($_GET['id']);
$row = $doctor->getDoctorById($id);
if ($row === null) {
    die("Doctor not found.");
}
$departments = $doctor->getAllDepartments();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_name   = $_POST['doctor_name'];
    $specialization = $_POST['specialization'];
    $phone         = $_POST['phone'];
    $department_id = $_POST['department_id'];

    if ($doctor->updateDoctor($id, $doctor_name, $specialization, $phone, $department_id)) {
        header("Location: view.php");
        exit();
    } else {
        $error = "Failed to update doctor.";
    }
}

include "../../templates/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Doctor</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to doctors</a>
    <h2 class="center-text">?? Edit Doctor</h2>

    <?php if (isset($error)): ?>
        <p style="color:red; text-align:center;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="form-container">
        <label>Full Name</label>
        <input type="text" name="doctor_name" value="<?= htmlspecialchars($row['doctor_name']) ?>" required>

        <label>Specialization</label>
        <input type="text" name="specialization" value="<?= $row['specialization'] ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= $row['phone'] ?>" required>

        <label>Department</label>
        <select name="department_id" required>
            <option value="">-- Select Department --</option>
            <?php while ($dept = $departments->fetch_assoc()): ?>
                <option value="<?= $dept['department_id'] ?>" <?= $dept['department_id'] == $row['department_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($dept['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Update Doctor</button>
    </form>
</div>

<?php include "../../templates/footer.php"; ?>
</body>
</html>