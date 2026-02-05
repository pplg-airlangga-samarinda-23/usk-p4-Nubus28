<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header with logo and navigation -->
    <header>
        <h1>Perpustakaan Neenee</h1>
        <img src="chibi10-removebg-preview.png" alt="Library Logo" class="logo">
       
    </header>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Welcome to Library</h1>
        <p>Still New</p>
    </div>

    <!-- Features Section -->
    <div class="features">
        <div class="card">
            <h3>Login</h3>
            <p>Akses akun Anda untuk meminjam dan mengelola buku.</p>
            <a href="login.php">Login</a>
        </div>

        <div class="card">
            <h3>Register</h3>
            <p>Buat akun baru untuk bergabung sebagai anggota perpustakaan.</p>
            <a href="register.php">Register</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Perpustakaan Neenee. All rights reserved.</p>
    </footer>
</body>
</html>
