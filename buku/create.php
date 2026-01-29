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
        echo "Buku berhasil ditambahkan!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<form method="POST">
    Judul: <input type="text" name="judul" required><br>
    Pengarang: <input type="text" name="pengarang"><br>
    Genre: 
    <select name="genre" required>
        <option value="Romance">Romance</option>
        <option value="Action">Action</option>
        <option value="Comedy">Comedy</option>
    </select><br>
    Deskripsi: <textarea name="deskripsi"></textarea><br>
    Stok: <input type="number" name="stok" min="0" required><br>
    <button type="submit">Simpan</button>
    <a href="index.php">kembali</a>
</form>
