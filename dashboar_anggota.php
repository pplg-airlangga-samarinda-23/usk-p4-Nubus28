<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'anggota') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="member-container">
        <h1>Member Dashboard</h1>
        <a href="logout.php">Logout</a>
        <h2>Selamat datang, <?php echo htmlspecialchars($_SESSION['user']); ?></h2>

        <div class="services">
            <div class="card">
                <h3>Layanan Peminjaman Buku</h3>
                <p><a href="form_pinjam.php">Pinjam Buku</a></p>
                <p><a href="form_kembali.php">Kembalikan Buku</a></p>
            </div>

            <div class="card">
                <h3>Menu Lainnya</h3>
                <p><a href="index.php">Kembali ke Beranda</a></p>
            </div>
        </div>
    </div>
</body>
</html>
