<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Buku</title>
</head>
<body>
    <h1>Hapus Buku</h1>
    
    <?php
    $conn = new mysqli('localhost', 'root', '', 'perpustakaan');
    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        $sql = "SELECT * FROM Book WHERE id = $id";
        $result = $conn->query($sql);
        $book = $result->fetch_assoc();
        
        if (!$book) {
            echo "Buku tidak ditemukan!";
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $delete_sql = "DELETE FROM Book WHERE id = $id";
            
            if ($conn->query($delete_sql)) {
                echo "Buku berhasil dihapus!<br>";
                echo '<a href="index.php">Kembali</a>';
                exit;
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "ID buku tidak ditemukan!";
        exit;
    }
    ?>
    
    <p>Apakah anda yakin ingin menghapus buku ini?</p>
    
    <p><strong><?php echo $book['judul']; ?></strong></p>
    
    <form method="POST">
        <button type="submit">Hapus</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>
