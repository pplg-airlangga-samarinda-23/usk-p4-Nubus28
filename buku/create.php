<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku</title>
</head>
<body>
    <h1>Tambah Buku</h1>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn = new mysqli('localhost', 'root', '', 'perpustakaan');
        
        $judul = $_POST['judul'];
        $pengarang = $_POST['pengarang'];
        $deskripsi = $_POST['deskripsi'];
        
        $sql = "INSERT INTO Book (judul, pengarang, deskripsi) VALUES ('$judul', '$pengarang', '$deskripsi')";
        
        if ($conn->query($sql)) {
            echo "Buku berhasil ditambahkan!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
    ?>
    
    <form method="POST">
        <label>Judul:</label><br>
        <input type="text" name="judul" required><br><br>
        
        <label>Pengarang:</label><br>
        <input type="text" name="pengarang"><br><br>
        
        <label>Deskripsi:</label><br>
        <textarea name="deskripsi" rows="4"></textarea><br><br>
        
        <button type="submit">Simpan</button>
    </form>
    
    <br>
    <a href="index.php">Kembali</a>
</body>
</html>
