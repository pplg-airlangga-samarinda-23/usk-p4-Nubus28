<?php
$file = "users.json";
$users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === "admin") {
        echo "<div class='alert alert-error'>❌ You cannot register as admin.</div>";
        exit;
    }

    if (isset($users[$username])) {
        echo "<div class='alert alert-error'>❌ Username already taken.</div>";
    } else {
        $users[$username] = password_hash($password, PASSWORD_DEFAULT);
        file_put_contents($file, json_encode($users));

        $conn = new mysqli('localhost', 'root', '', 'perpustakaan');
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO User (username, password, role) VALUES ('" . $conn->real_escape_string($username) . "', '" . $conn->real_escape_string($hashed) . "', 'anggota')";
        $conn->query($insert_sql);
        $conn->close();

        echo "<div class='alert alert-success'>✅ Registration successful. You can now login.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <form class="auth-form" method="post">
            <h1>Register</h1>
            <label for="username">Choose Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Choose Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>
            <a href="login.php">Login</a>
        </form>
    </div>
</body>
</html>
