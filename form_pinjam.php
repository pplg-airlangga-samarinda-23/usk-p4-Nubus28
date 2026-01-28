<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Buku</title>
</head>
<body>
    <h1>Form Peminjaman Buku</h1>
    
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
        $id_book = $_POST['id_book'];
        $id_user = $_POST['id_user'];
        $tgl_pinjam = $_POST['tgl_pinjam'];
        
        $sql = "INSERT INTO peminjaman (id_book, id_user, tgl_pinjam) VALUES ($id_book, $id_user, '$tgl_pinjam')";
        
        if ($conn->query($sql)) {
            echo "Buku berhasil dipinjam!<br>";
            echo '<a href="dashboar_anggota.php">Kembali ke Dashboard</a>';
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    }
    
    $books = $conn->query("SELECT id, judul FROM Book");
    
    $user_result = $conn->query("SELECT id FROM User WHERE username = '" . $conn->real_escape_string($_SESSION['user']) . "'");
    if ($user_result && $user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $user_id = $user['id'];
    } else {
        die("User tidak ditemukan di database!");
    }
    ?>
    
    <form method="POST">
        <label>Pilih Buku:</label><br>
        <select name="id_book" required>
            <option value="">-- Pilih Buku --</option>
            <?php while($row = $books->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['judul']; ?></option>
            <?php } ?>
        </select><br><br>
        
        <label>Tanggal Pinjam:</label><br>
        <input type="date" name="tgl_pinjam" required><br><br>
        
        <input type="hidden" name="id_user" value="<?php echo $user_id; ?>">
        
        <button type="submit">Pinjam</button>
        <a href="dashboar_anggota.php">Batal</a>
    </form>
</body>
</html>
