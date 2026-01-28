<?php
$file = "users.json";
$users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === "admin") {
        echo "❌ You cannot register as admin.";
        exit;
    }

    if (isset($users[$username])) {
        echo "❌ Username already taken.";
    } else {
        $users[$username] = password_hash($password, PASSWORD_DEFAULT);
        file_put_contents($file, json_encode($users));
        
        $conn = new mysqli('localhost', 'root', '', 'perpustakaan');
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO User (username, password, role) VALUES ('" . $conn->real_escape_string($username) . "', '" . $conn->real_escape_string($hashed) . "', 'anggota')";
        $conn->query($insert_sql);
        $conn->close();
        
        echo "✅ Registration successful. You can now login.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form method="post">
        <label for="username">Choose Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Choose Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Register</button>
        <a href="login.php">Login</a>
    </form>
</body>
</html>
