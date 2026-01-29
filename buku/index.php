<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
</head>
<body>
    <h1>Daftar Buku</h1>
    <a href="../dashboar_admin.php">← Kembali ke Beranda</a><br><br>
    
    <a href="create.php">+ Tambah Buku</a><br><br>
    
    <form method="GET" style="margin-bottom: 20px;">
        <label>Filter berdasarkan Genre:</label>
        <select name="genre" onchange="this.form.submit()">
            <option value="">-- Semua Genre --</option>
            <option value="Romance" <?php echo (isset($_GET['genre']) && $_GET['genre'] == 'Romance') ? 'selected' : ''; ?>>Romance</option>
            <option value="Action" <?php echo (isset($_GET['genre']) && $_GET['genre'] == 'Action') ? 'selected' : ''; ?>>Action</option>
            <option value="Comedy" <?php echo (isset($_GET['genre']) && $_GET['genre'] == 'Comedy') ? 'selected' : ''; ?>>Comedy</option>
        </select>
    </form>
    
    <?php
    $conn = new mysqli('localhost', 'root', '', 'perpustakaan');
    
    // --- Hapus riwayat peminjaman jika admin klik tombol ---
    if (isset($_POST['hapus_pinjam'])) {
        $id_pinjam = $_POST['id_pinjam'];
        $conn->query("DELETE FROM peminjaman WHERE id = $id_pinjam");
        echo "<p style='color:red;'>Riwayat peminjaman berhasil dihapus!</p>";
    }

    // --- Daftar Buku ---
    $sql = "SELECT * FROM Book";
    if (isset($_GET['genre']) && !empty($_GET['genre'])) {
        $genre = $conn->real_escape_string($_GET['genre']);
        $sql .= " WHERE genre = '$genre'";
    }
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>No</th><th>Judul</th><th>Pengarang</th><th>Genre</th><th>Deskripsi</th><th>Stok</th><th>Aksi</th></tr>";
        
        $no = 1;
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no . "</td>";
            echo "<td>" . $row['judul'] . "</td>";
            echo "<td>" . $row['pengarang'] . "</td>";
            echo "<td>" . $row['genre'] . "</td>";
            echo "<td>" . substr($row['deskripsi'], 0, 50) . "...</td>";
            echo "<td>" . $row['stok'] . "</td>";  
            echo "<td>";
            echo "<a href='edit.php?id=" . $row['id'] . "'>Edit</a> | ";
            echo "<a href='delete.php?id=" . $row['id'] . "'>Hapus</a>";
            echo "</td>";
            echo "</tr>";
            $no++;
        }
        echo "</table>";
    } else {
        echo "Tidak ada buku.";
    }

    // --- Tabel Riwayat Peminjaman ---
    $riwayat = $conn->query("
        SELECT p.id, b.judul, b.genre, p.tgl_pinjam, p.tgl_kembali, u.username 
        FROM peminjaman p 
        JOIN Book b ON p.id_book = b.id 
        JOIN User u ON p.id_user = u.id
        ORDER BY p.id DESC
    ");

    if ($riwayat->num_rows > 0) {
        echo "<h2>Riwayat Peminjaman</h2>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Judul</th><th>Genre</th><th>User</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Aksi</th></tr>";
        while($row = $riwayat->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['judul']."</td>";
            echo "<td>".$row['genre']."</td>";
            echo "<td>".$row['username']."</td>";
            echo "<td>".$row['tgl_pinjam']."</td>";
            echo "<td>".($row['tgl_kembali'] ? $row['tgl_kembali'] : "-")."</td>";
            echo "<td>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='id_pinjam' value='".$row['id']."'>
                    <button type='submit' name='hapus_pinjam'>Hapus</button>
                </form>
            </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Tidak ada riwayat peminjaman.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
