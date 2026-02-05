<?php
$conn = new mysqli('localhost', 'root', '', 'perpustakaan');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM Book WHERE id = $id";
    $result = $conn->query($sql);
    $book = $result->fetch_assoc();

    if (!$book) {
        echo "<div class='alert alert-error'>Buku tidak ditemukan!</div>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $delete_sql = "DELETE FROM Book WHERE id = $id";

        if ($conn->query($delete_sql)) {
            echo "<div class='alert alert-success'>Buku berhasil dihapus!</div>";
            echo '<a href="index.php">Kembali</a>';
            exit;
        } else {
            echo "<div class='alert alert-error'>Error: " . $conn->error . "</div>";
        }
    }
} else {
    echo "<div class='alert alert-error'>ID buku tidak ditemukan!</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hapus Buku</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="box">
        <h1>Hapus Buku</h1>
        <p>Apakah anda yakin ingin menghapus buku ini?</p>
        <p><strong><?php echo htmlspecialchars($book['judul']); ?></strong></p>

        <form method="POST">
            <button type="submit">Hapus</button>
            <a href="index.php">Batal</a>
        </form>
    </div>
</body>
</html>
