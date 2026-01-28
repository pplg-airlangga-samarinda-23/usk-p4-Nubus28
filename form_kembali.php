<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kembalikan Buku</title>
</head>
<body>
    <h1>Form Pengembalian Buku</h1>
    
    <?php
    session_start();
    
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
    
    $conn = new mysqli('localhost', 'root', '', 'perpustakaan');
    
    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_pinjam = $_POST['id_pinjam'];
        $tgl_kembali = $_POST['tgl_kembali'];
        
        $sql = "UPDATE peminjaman SET tgl_kembali = '$tgl_kembali' WHERE id = $id_pinjam";
        
        if ($conn->query($sql)) {
            echo "Buku berhasil dikembalikan!<br>";
            echo '<a href="dashboar_anggota.php">Kembali ke Dashboard</a>';
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    }
    
    $user_result = $conn->query("SELECT id FROM User WHERE username = '" . $conn->real_escape_string($_SESSION['user']) . "'");
    if ($user_result && $user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $user_id = $user['id'];
    } else {
        die("User tidak ditemukan di database!");
    }
    
    $peminjaman = $conn->query("SELECT p.id, b.judul, p.tgl_pinjam FROM peminjaman p JOIN Book b ON p.id_book = b.id WHERE p.id_user = $user_id AND p.tgl_kembali IS NULL");
    ?>
    
    <form method="POST">
        <label>Pilih Buku yang Dikembalikan:</label><br>
        <select name="id_pinjam" required>
            <option value="">-- Pilih Buku --</option>
            <?php while($row = $peminjaman->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['judul']; ?> (Pinjam: <?php echo $row['tgl_pinjam']; ?>)</option>
            <?php } ?>
        </select><br><br>
        
        <label>Tanggal Kembali:</label><br>
        <input type="date" name="tgl_kembali" required><br><br>
        
        <button type="submit">Kembalikan</button>
        <a href="dashboar_anggota.php">Batal</a>
    </form>
</body>
</html>
