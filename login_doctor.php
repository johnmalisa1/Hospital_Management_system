<?php
session_start();
include "config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = 'Doctor'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $doctor = $res->fetch_assoc();

    if ($doctor && password_verify($password, $doctor['password'])) {
        $_SESSION['user_id'] = $doctor['id'];
        $_SESSION['username'] = $doctor['username'];
        $_SESSION['role'] = 'Doctor';
        header("Location: doctor_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Login</title>
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
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            width: 350px;
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            font-size: 16px;
            border-radius: 5px;
        }

        .error {
            color: red;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

<form method="POST">
    <h2>Doctor Login</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <input type="text" name="username" placeholder="Doctor Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

</body>
</html>

