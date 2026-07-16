<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php"); // <- CORRECT
    exit();
}


include "../../config/db.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        // Get the inserted user ID
        $new_user_id = $stmt->insert_id;

        // If role is Doctor, also insert into doctors table
        if ($role === 'Doctor') {
            $doctor_name    = $username;
            $specialization = 'General';
            $phone          = '';

            $insertDoctor = $conn->prepare("INSERT INTO doctors (doctor_name, specialization, phone, department_id) VALUES (?, ?, ?, NULL)");
            $insertDoctor->bind_param("sss", $doctor_name, $specialization, $phone);
            $insertDoctor->execute();
        }

        header("Location: view.php");
        exit();
    } else {
        $error = "Error inserting user.";
    }
}

include "../../templates/header.php";

?>

<a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to users</a>
<h2 style="text-align:center;">?? Add System User</h2>
<form method="POST" style="width:400px; margin:auto; padding:20px; background:white; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Username:</label>
    <input type="text" name="username" required style="width:100%; padding:10px;"><br><br>

    <label>Password:</label>
    <input type="password" name="password" required style="width:100%; padding:10px;"><br><br>

    <label>Role:</label>
    <select name="role" required style="width:100%; padding:10px;">
        <option value="">-- Select Role --</option>
        <option value="Admin">Admin</option>
        <option value="Doctor">Doctor</option>
        <option value="Receptionist">Receptionist</option>
    </select><br><br>

    <button type="submit" style="padding:10px 20px; background:#28a745; color:white; border:none;">Add User</button>
</form>
</div>