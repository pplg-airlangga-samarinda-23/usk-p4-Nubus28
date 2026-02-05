<?php
$conn = new mysqli('localhost', 'root', '', 'perpustakaan');

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-error'>ID buku tidak ditemukan!</div>";
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM Book WHERE id = $id";
$result = $conn->query($sql);
$book = $result->fetch_assoc();

if (!$book) {
    echo "<div class='alert alert-error'>Buku tidak ditemukan!</div>";
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
        echo "<div class='alert alert-success'>Buku berhasil diperbarui!</div>";
        echo '<a href="index.php">Kembali</a>';
        exit;
    } else {
        echo "<div class='alert alert-error'>Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="auth-container">
        <form class="auth-form" method="POST">
            <h1>Edit Buku</h1>

            <label>Judul:</label>
            <input type="text" name="judul" value="<?php echo htmlspecialchars($book['judul']); ?>" required>

            <label>Pengarang:</label>
            <input type="text" name="pengarang" value="<?php echo htmlspecialchars($book['pengarang']); ?>">

            <label>Genre:</label>
            <select name="genre" required>
                <option value="">-- Pilih Genre --</option>
                <option value="Romance" <?php echo ($book['genre'] == 'Romance') ? 'selected' : ''; ?>>Romance</option>
                <option value="Action" <?php echo ($book['genre'] == 'Action') ? 'selected' : ''; ?>>Action</option>
                <option value="Comedy" <?php echo ($book['genre'] == 'Comedy') ? 'selected' : ''; ?>>Comedy</option>
            </select>

            <label>Deskripsi:</label>
            <textarea name="deskripsi" rows="4"><?php echo htmlspecialchars($book['deskripsi']); ?></textarea>

            <label>Stok:</label>
            <input type="number" name="stok" value="<?php echo htmlspecialchars($book['stok']); ?>" min="0" required>

            <button type="submit">Simpan</button>
            <a href="index.php">Batal</a>
        </form>
    </div>
</body>
</html>
