<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit(); }
include "../../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
    header("Location: view.php");
}

$users = $conn->query("SELECT id, username FROM users");
?>
<h2 style="text-align:center;">Send Notification</h2>
<form method="POST" style="width:400px;margin:auto;padding:30px;background:white;border-radius:10px;box-shadow:0 0 10px #ccc;">
    <label>Select User:</label>
    <select name="user_id" required style="width:100%;padding:10px;">
        <option value="">-- Choose --</option>
        <?php while ($u = $users->fetch_assoc()): ?>
            <option value="<?= $u['id'] ?>"><?= $u['username'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Message:</label>
    <textarea name="message" required style="width:100%;height:80px;"></textarea><br><br>

    <button type="submit" style="background:#007bff;color:white;padding:10px 20px;">Send</button>
</form>

