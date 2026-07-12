<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php"); // <- CORRECT
    exit();
}


include "../../config/db.php";
include "../../navbar.php";
?>

<div style="margin-left:230px; padding:20px; max-width:calc(100% - 230px);">
    <h2 style="text-align:center;">👥 System Users</h2>

    <div style="overflow-x:auto;">
        <table border="1" cellpadding="10" style="width:100%; background:white; box-shadow:0 0 10px #ccc; border-collapse:collapse;">
            <tr style="background:#007bff; color:white;">
                <th>ID</th><th>Username</th><th>Role</th><th>Actions</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['role']}</td>
                    <td>
                        <a href='edit.php?id={$row['id']}' style='color:green;'>Edit</a> |
                        <a href='delete.php?id={$row['id']}' style='color:red;' onclick='return confirm(\"Delete this user?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</div>

