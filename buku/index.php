<?php
$conn = new mysqli('localhost', 'root', '', 'perpustakaan');

// --- Hapus riwayat peminjaman jika admin klik tombol ---
$message = "";
if (isset($_POST['hapus_pinjam'])) {
    $id_pinjam = $_POST['id_pinjam'];
    $conn->query("DELETE FROM peminjaman WHERE id = $id_pinjam");
    $message = "Riwayat peminjaman berhasil dihapus!";
}

// --- Daftar Buku ---
$sql = "SELECT * FROM Book";
if (isset($_GET['genre']) && !empty($_GET['genre'])) {
    $genre = $conn->real_escape_string($_GET['genre']);
    $sql .= " WHERE genre = '$genre'";
}
$result = $conn->query($sql);

// --- Tabel Riwayat Peminjaman ---
$riwayat = $conn->query("
    SELECT p.id, b.judul, b.genre, p.tgl_pinjam, p.tgl_kembali, u.username 
    FROM peminjaman p 
    JOIN Book b ON p.id_book = b.id 
    JOIN User u ON p.id_user = u.id
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="admin-container">
        <h1>Daftar Buku</h1>
        <a href="../dashboar_admin.php">← Kembali ke Beranda</a>
        <a href="create.php">+ Tambah Buku</a>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="GET" class="mb-20">
            <label>Filter berdasarkan Genre:</label>
            <select name="genre" onchange="this.form.submit()">
                <option value="">-- Semua Genre --</option>
                <option value="Romance" <?php echo (isset($_GET['genre']) && $_GET['genre']=='Romance')?'selected':''; ?>>Romance</option>
                <option value="Action" <?php echo (isset($_GET['genre']) && $_GET['genre']=='Action')?'selected':''; ?>>Action</option>
                <option value="Comedy" <?php echo (isset($_GET['genre']) && $_GET['genre']=='Comedy')?'selected':''; ?>>Comedy</option>
            </select>
        </form>

        <div class="box">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Genre</th>
                        <th>Deskripsi</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                    <?php $no=1; while($row=$result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['judul']); ?></td>
                            <td><?php echo htmlspecialchars($row['pengarang']); ?></td>
                            <td><?php echo htmlspecialchars($row['genre']); ?></td>
                            <td><?php echo substr(htmlspecialchars($row['deskripsi']),0,50)."…"; ?></td>
                            <td><?php echo htmlspecialchars($row['stok']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                <a href="delete.php?id=<?php echo $row['id']; ?>">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Tidak ada buku.</p>
            <?php endif; ?>
        </div>

        <div class="box">
            <h2>Riwayat Peminjaman</h2>
            <?php if ($riwayat->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Genre</th>
                        <th>User</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Aksi</th>
                    </tr>
                    <?php while($row=$riwayat->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['judul']); ?></td>
                            <td><?php echo htmlspecialchars($row['genre']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo $row['tgl_pinjam']; ?></td>
                            <td><?php echo $row['tgl_kembali'] ? $row['tgl_kembali'] : "-"; ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id_pinjam" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="hapus_pinjam">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>Tidak ada riwayat peminjaman.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
