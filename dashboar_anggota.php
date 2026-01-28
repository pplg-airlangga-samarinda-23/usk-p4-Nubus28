<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
</head>
<body>
    <?php
    session_start();
    
    if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'anggota') {
        header("Location: login.php");
        exit;
    }
    ?>
    
    <h1>Member Dashboard</h1>
    <a href="logout.php">Logout</a>
    
    <h2>Selamat datang, <?php echo $_SESSION['user']; ?></h2>
        
        <h3>Layanan Peminjaman Buku</h3>
        <p>
            <a href="form_pinjam.php">Pinjam Buku</a><br><br>
            <a href="form_kembali.php">Kembalikan Buku</a>
        </p>
        
        <hr>
        
        <h3>Menu Lainnya</h3>
        <p>
            <a href="index.php">Kembali ke Beranda</a>
        </p>
    
</body>
</html>
