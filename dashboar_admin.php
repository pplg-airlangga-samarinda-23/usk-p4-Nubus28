<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'perpustakaan');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$message = "";

// Add user
if (isset($_POST['add'])) {
    $u = $conn->real_escape_string($_POST['new_username']);
    $p = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $role = $conn->real_escape_string($_POST['role']); // role selector

    $check = $conn->query("SELECT id FROM User WHERE username='$u'");
    if ($check->num_rows > 0) {
        $message = "❌ Username already exists.";
    } else {
        $conn->query("INSERT INTO User (username, password, role) VALUES ('$u', '$p', '$role')");
        $message = "✅ User added with role $role.";
    }
}

// Edit username
if (isset($_POST['edit'])) {
    $old = $conn->real_escape_string($_POST['edit_username']);
    $new = $conn->real_escape_string($_POST['new_username']);

    $check = $conn->query("SELECT id FROM User WHERE username='$old'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE User SET username='$new' WHERE username='$old'");
        $message = "✅ Username updated.";
    } else {
        $message = "❌ User not found.";
    }
}

// Delete user
if (isset($_POST['delete'])) {
    $u = $conn->real_escape_string($_POST['delete_username']);
    $check = $conn->query("SELECT id FROM User WHERE username='$u'");
    if ($check->num_rows > 0) {
        $conn->query("DELETE FROM User WHERE username='$u'");
        $message = "✅ User deleted.";
    } else {
        $message = "❌ User not found.";
    }
}

// Reset password
if (isset($_POST['reset'])) {
    $u = $conn->real_escape_string($_POST['reset_username']);
    $newPass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT id FROM User WHERE username='$u'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE User SET password='$newPass' WHERE username='$u'");
        $message = "✅ Password reset successfully.";
    } else {
        $message = "❌ User not found.";
    }
}

// Get all users
$users = $conn->query("SELECT username, role FROM User");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        .box { border: 1px solid black; padding: 15px; margin-top: 20px; }
        table { border-collapse: collapse; width: 50%; }
        table, th, td { border: 1px solid black; padding: 5px; }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="logout.php">Logout</a>
    <a href="buku/index.php">buku</a>
    <h2>Selamat datang, <?php echo htmlspecialchars($_SESSION['user']); ?></h2>

    <?php if (!empty($message)) echo "<p><strong>$message</strong></p>"; ?>

    <div class="box">
        <h3>Manajemen User</h3>

        <!-- Add User -->
        <form method="post">
            <input type="text" name="new_username" placeholder="Username" required>
            <input type="password" name="new_password" placeholder="Password" required>
            <select name="role" required>
                <option value="anggota">Anggota</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="add">Add</button>
        </form>

        <!-- Edit User -->
        <form method="post">
            <input type="text" name="edit_username" placeholder="Current Username" required>
            <input type="text" name="new_username" placeholder="New Username" required>
            <button type="submit" name="edit">Update Username</button>
        </form>

        <!-- Delete User -->
        <form method="post">
            <input type="text" name="delete_username" placeholder="Username" required>
            <button type="submit" name="delete">Delete</button>
        </form>

        <!-- Reset Password -->
        <form method="post">
            <input type="text" name="reset_username" placeholder="Username" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit" name="reset">Reset Password</button>
        </form>

        <hr>

        <h3>Daftar User</h3>
        <table>
            <tr>
                <th>Username</th>
                <th>Role</th>
            </tr>
            <?php while ($row = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <hr>
   
</body>
</html>
