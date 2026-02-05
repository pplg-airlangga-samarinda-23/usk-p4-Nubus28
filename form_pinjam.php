<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'perpustakaan');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil user id dari session
$user_result = $conn->query("SELECT id FROM User WHERE username = '" . $conn->real_escape_string($_SESSION['user']) . "'");
$user = $user_result->fetch_assoc();
$user_id = $user['id'];

// ================== PROSES PINJAM ==================
$message = "";
if (isset($_POST['pinjam'])) {
    $id_book = (int)$_POST['id_book'];
    $tgl_pinjam = date("Y-m-d");

    $cek = $conn->query("SELECT stok FROM Book WHERE id = $id_book");
    $data = $cek->fetch_assoc();

    if ($data['stok'] > 0) {
        $conn->query("INSERT INTO peminjaman (id_book, id_user, tgl_pinjam) VALUES ($id_book, $user_id, '$tgl_pinjam')");
        $conn->query("UPDATE Book SET stok = stok - 1 WHERE id = $id_book");
        $message = "<div class='alert alert-success'>Buku berhasil dipinjam!</div>";
    } else {
        $message = "<div class='alert alert-error'>Stok buku habis!</div>";
    }
}

// ================== PROSES KEMBALI ==================
if (isset($_POST['kembali'])) {
    $id_pinjam = (int)$_POST['id_pinjam'];
    $tgl_kembali = date("Y-m-d");

    $conn->query("UPDATE peminjaman SET tgl_kembali = '$tgl_kembali' WHERE id = $id_pinjam");

    // ambil id_book
    $res = $conn->query("SELECT id_book FROM peminjaman WHERE id = $id_pinjam");
    $row = $res->fetch_assoc();
    $id_book = $row['id_book'];

    // tambah stok
    $conn->query("UPDATE Book SET stok = stok + 1 WHERE id = $id_book");

    $message = "<div class='alert alert-success'>Buku berhasil dikembalikan!</div>";
}

// ================== AMBIL SEMUA BUKU ==================
$books = $conn->query("SELECT * FROM Book");

// ================== AMBIL RIWAYAT PEMINJAMAN ==================
$peminjaman = $conn->query("
    SELECT p.id, b.judul, b.genre, p.tgl_pinjam, p.tgl_kembali 
    FROM peminjaman p 
    JOIN Book b ON p.id_book = b.id 
    WHERE p.id_user = $user_id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>booklist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="member-container">
        <h1>book</h1>
        <?php if (!empty($message)) echo $message; ?>

        <!-- Form Pencarian -->
        <div class="box">
            <h3>Search Book</h3>
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Enter book title..." required>
                <button type="submit">Search</button>
            </form>
        </div>

        <?php
        // ================== HASIL PENCARIAN ==================
        if (isset($_GET['search'])) {
            $search = $conn->real_escape_string($_GET['search']);
            $query = "SELECT * FROM Book WHERE judul LIKE '%$search%'";
            $result = $conn->query($query);

            echo "<div class='box'>";
            if ($result && $result->num_rows > 0) {
                echo "<h3>Hasil Pencarian:</h3>";
                echo "<table>
                        <tr>
                            <th>Judul</th>
                            <th>Genre</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".htmlspecialchars($row['judul'])."</td>
                            <td>".htmlspecialchars($row['genre'])."</td>
                            <td class='".($row['stok']>0?"tersedia":"habis")."'>".
                                ($row['stok']>0?$row['stok']:"Habis")."
                            </td>
                            <td>";
                    if ($row['stok'] > 0) {
                        echo "<form method='POST' style='display:inline;'>
                                <input type='hidden' name='id_book' value='{$row['id']}'>
                                <button type='submit' name='pinjam'>Pinjam</button>
                              </form>";
                    } else {
                        echo "Tidak tersedia";
                    }
                    echo "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Buku tidak ditemukan.</p>";
            }
            echo "</div>";
        }
        ?>

        <!-- Daftar Buku -->
        <div class="box">
            <h2>All Books</h2>
            <table>
                <tr>
                    <th>Namebook</th>
                    <th>Genre</th>
                    <th>Stok</th>
                    <th>Action</th>
                </tr>
                <?php while($row = $books->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['judul']); ?></td>
                        <td><?php echo htmlspecialchars($row['genre']); ?></td>
                        <td class="<?php echo $row['stok'] > 0 ? 'tersedia' : 'habis'; ?>">
                            <?php echo $row['stok'] > 0 ? $row['stok'] : "Habis"; ?>
                        </td>
                        <td>
                            <?php if ($row['stok'] > 0) { ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id_book" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="pinjam">Borrow</button>
                                </form>
                            <?php } else { ?>
                                not available
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Riwayat Peminjaman -->
        <div class="box">
            <h2>History of Borrowed Books</h2>
            <table>
                <tr>
                    <th>Namebook</th>
                    <th>Genre</th>
                    <th>date Borrowed</th>
                    <th>date Returned</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                <?php while($row = $peminjaman->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['judul']); ?></td>
                        <td><?php echo htmlspecialchars($row['genre']); ?></td>
                        <td><?php echo $row['tgl_pinjam']; ?></td>
                        <td><?php echo $row['tgl_kembali'] ? $row['tgl_kembali'] : "-"; ?></td>
                        <td>
                            <?php echo $row['tgl_kembali'] 
                                ? "<span class='tersedia'>Dikembalikan</span>" 
                                : "<span class='habis'>Dipinjam</span>"; ?>
                        </td>
                        <td>
                            <?php if (!$row['tgl_kembali']) { ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id_pinjam" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="kembali">Return</button>
                                </form>
                            <?php } else { ?>
                                -
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <a href="dashboar_anggota.php">home</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>
