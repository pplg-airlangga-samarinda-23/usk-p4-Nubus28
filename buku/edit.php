<?php
$conn = new mysqli('localhost', 'root', '', 'perpustakaan');

if (!isset($_GET['id'])) {
    echo "ID buku tidak ditemukan!";
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM Book WHERE id = $id";
$result = $conn->query($sql);
$book = $result->fetch_assoc();

if (!$book) {
    echo "Buku tidak ditemukan!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $deskripsi = $_POST['deskripsi'];
    $genre = $_POST['genre'];
    $stok = $_POST['stok'];

    $update_sql = "UPDATE Book 
                   SET judul = '$judul', 
                       pengarang = '$pengarang', 
                       deskripsi = '$deskripsi', 
                       genre = '$genre',
                       stok = $stok
                   WHERE id = $id";

    if ($conn->query($update_sql)) {
        echo "Buku berhasil diperbarui!<br>";
        echo '<a href="index.php">Kembali</a>';
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
</head>
<body>
    <h1>Edit Buku</h1>
    <form method="POST">
        <label>Judul:</label><br>
        <input type="text" name="judul" value="<?php echo $book['judul']; ?>" required><br><br>
        
        <label>Pengarang:</label><br>
        <input type="text" name="pengarang" value="<?php echo $book['pengarang']; ?>"><br><br>
        
        <label>Genre:</label><br>
        <select name="genre" required>
            <option value="">-- Pilih Genre --</option>
            <option value="Romance" <?php echo ($book['genre'] == 'Romance') ? 'selected' : ''; ?>>Romance</option>
            <option value="Action" <?php echo ($book['genre'] == 'Action') ? 'selected' : ''; ?>>Action</option>
            <option value="Comedy" <?php echo ($book['genre'] == 'Comedy') ? 'selected' : ''; ?>>Comedy</option>
        </select><br><br>
        
        <label>Deskripsi:</label><br>
        <textarea name="deskripsi" rows="4"><?php echo $book['deskripsi']; ?></textarea><br><br>
        
        <label>Stok:</label><br>
        <input type="number" name="stok" value="<?php echo $book['stok']; ?>" min="0" required><br><br>
        
        <button type="submit">Simpan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>
