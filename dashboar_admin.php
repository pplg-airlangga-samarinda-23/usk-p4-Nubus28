<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$file = "users.json";
$users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];


if (isset($_POST['add'])) {
    $u = $_POST['new_username'];
    $p = $_POST['new_password'];
    if (isset($users[$u])) {
        $message = "❌ Username already exists.";
    } else {
        $users[$u] = password_hash($p, PASSWORD_DEFAULT);
        file_put_contents($file, json_encode($users));
        $message = "✅ User added.";
    }
}

if (isset($_POST['edit'])) {
    $u = $_POST['edit_username'];
    $p = $_POST['edit_password'];
    if (isset($users[$u])) {
        $users[$u] = password_hash($p, PASSWORD_DEFAULT);
        file_put_contents($file, json_encode($users));
        $message = "✅ Password updated.";
    } else {
        $message = "❌ User not found.";
    }
}


if (isset($_POST['delete'])) {
    $u = $_POST['delete_username'];
    if (isset($users[$u])) {
        unset($users[$u]);
        file_put_contents($file, json_encode($users));
        $message = "✅ User deleted.";
    } else {
        $message = "❌ User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        .box {
            border: 1px solid black;
            padding: 15px;
            margin-top: 20px;
        }
        table {
            border-collapse: collapse;
            width: 50%;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="logout.php">Logout</a>
    <h2>Selamat datang, <?php echo htmlspecialchars($_SESSION['user']); ?></h2>

    <?php if (!empty($message)) echo "<p><strong>$message</strong></p>"; ?>

    <div class="box">
        <h3>Manajemen User</h3>

      
        <form method="post">
            <input type="text" name="new_username" placeholder="Username" required>
            <input type="password" name="new_password" placeholder="Password" required>
            <button type="submit" name="add">Add</button>
        </form>

      
        <form method="post">
            <input type="text" name="edit_username" placeholder="Username" required>
            <input type="password" name="edit_password" placeholder="New Password" required>
            <button type="submit" name="edit">Update</button>
        </form>

     
        <form method="post">
            <input type="text" name="delete_username" placeholder="Username" required>
            <button type="submit" name="delete">Delete</button>
        </form>

        <hr>

        <h3>Daftar User</h3>
        <table>
            <tr>
                <th>Username</th>
            </tr>
            <?php foreach ($users as $u => $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <hr>
    <p><a href="index.php">Kembali ke Beranda</a></p>
</body>
</html>
