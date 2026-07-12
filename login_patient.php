<?php
session_start();
include "config/db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM patients WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();
if ($patient && password_verify($password, $patient['password'])) {
    $_SESSION['user_id'] = $patient['patient_id'];
    $_SESSION['username'] = $patient['username'];
    $_SESSION['role'] = 'Patient';
    header("Location: patient_dashboard.php");
    exit();
} else {
    $error = "Invalid username or password!";
}

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Login</title>
    <style>
        body {
            background: #f2f2f2;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background: white;
            padding: 30px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            background: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<form method="POST">
    <h2>Patient Login</h2>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <input type="text" name="username" placeholder="Enter Username" required>
    <input type="password" name="password" placeholder="Enter Password" required>

    <button type="submit">Login</button>
</form>

</body>
</html>
