<?php
session_start();

$file = "users.json";
$users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

$adminUser = "admin";
$adminPass = "123"; 
$adminHash = password_hash($adminPass, PASSWORD_DEFAULT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === $adminUser && password_verify($password, $adminHash)) {
        $_SESSION["user"] = $username;
        $_SESSION["role"] = "admin";
        header("Location: dashboar_admin.php");
        exit;
    }

    if (isset($users[$username])) {
        if (password_verify($password, $users[$username])) {
            $_SESSION["user"] = $username;
            $_SESSION["role"] = "anggota";
            header("Location: dashboar_anggota.php");
            exit;
        } else {
            echo "<div class='alert alert-error'>Invalid password.</div>";
        }
    } else {
        echo "<div class='alert alert-error'>User not found. Please register.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <form class="auth-form" method="post">
            <h1>Login</h1>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
            <a href="register.php">Register</a>
        </form>
    </div>
</body>
</html>
