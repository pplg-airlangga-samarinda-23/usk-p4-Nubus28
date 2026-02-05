<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'perpustakaan');
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $deskripsi = $_POST['deskripsi'];
    $genre = $_POST['genre'];
    $stok = $_POST['stok'];

    $sql = "INSERT INTO Book (judul, pengarang, deskripsi, genre, stok) 
            VALUES ('$judul', '$pengarang', '$deskripsi', '$genre', $stok)";

    if ($conn->query($sql)) {
        echo "<div class='alert alert-success'>Buku berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-error'>Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="auth-container">
        <form class="auth-form" method="POST">
            <h1>Add Book</h1>

            <label>Namebook:</label>
            <input type="text" name="judul" required>

            <label>Author:</label>
            <input type="text" name="pengarang">

            <label>Genre:</label>
            <select name="genre" required>
                <option value="Romance">Romance</option>
                <option value="Action">Action</option>
                <option value="Comedy">Comedy</option>
            </select>

            <label>Description:</label>
            <textarea name="deskripsi"></textarea>

            <label>Stok:</label>
            <input type="number" name="stok" min="0" required>

            <button type="submit">Save</button>
            <a href="index.php">Back</a>
        </form>
    </div>
</body>
</html>
