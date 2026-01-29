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

// Proses pinjam
if (isset($_POST['pinjam'])) {
    $id_book = $_POST['id_book'];
    $tgl_pinjam = date("Y-m-d");

    $cek = $conn->query("SELECT stok FROM Book WHERE id = $id_book");
    $data = $cek->fetch_assoc();

    if ($data['stok'] > 0) {
        $conn->query("INSERT INTO peminjaman (id_book, id_user, tgl_pinjam) VALUES ($id_book, $user_id, '$tgl_pinjam')");
        $conn->query("UPDATE Book SET stok = stok - 1 WHERE id = $id_book");
        echo "<p style='color:green;'>Buku berhasil dipinjam!</p>";
    } else {
        echo "<p style='color:red;'>Stok buku habis!</p>";
    }
}

// Proses kembali
if (isset($_POST['kembali'])) {
    $id_pinjam = $_POST['id_pinjam'];
    $tgl_kembali = date("Y-m-d");

    $conn->query("UPDATE peminjaman SET tgl_kembali = '$tgl_kembali' WHERE id = $id_pinjam");

    // ambil id_book
    $res = $conn->query("SELECT id_book FROM peminjaman WHERE id = $id_pinjam");
    $row = $res->fetch_assoc();
    $id_book = $row['id_book'];

    // tambah stok
    $conn->query("UPDATE Book SET stok = stok + 1 WHERE id = $id_book");

    echo "<p style='color:blue;'>Buku berhasil dikembalikan!</p>";
}

// Ambil semua buku
$books = $conn->query("SELECT * FROM Book");

// Ambil riwayat peminjaman user
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
    <title>Daftar Buku</title>
    <style>
        table { border-collapse: collapse; width: 80%; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background: #eee; }
        .habis { color: red; font-weight: bold; }
        .tersedia { color: green; }
    </style>
</head>
<body>
    <h1>Daftar Buku</h1>
    <table>
        <tr>
            <th>Judul</th>
            <th>Genre</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = $books->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['judul']; ?></td>
                <td><?php echo $row['genre']; ?></td>
                <td class="<?php echo $row['stok'] > 0 ? 'tersedia' : 'habis'; ?>">
                    <?php echo $row['stok'] > 0 ? $row['stok'] : "Habis"; ?>
                </td>
                <td>
                    <?php if ($row['stok'] > 0) { ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_book" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="pinjam">Pinjam</button>
                        </form>
                    <?php } else { ?>
                        Tidak tersedia
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h2>Riwayat Peminjaman</h2>
    <table>
        <tr>
            <th>Judul</th>
            <th>Genre</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = $peminjaman->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['judul']; ?></td>
                <td><?php echo $row['genre']; ?></td>
                <td><?php echo $row['tgl_pinjam']; ?></td>
                <td><?php echo $row['tgl_kembali'] ? $row['tgl_kembali'] : "-"; ?></td>
                <td>
                    <?php echo $row['tgl_kembali'] ? "<span style='color:blue;'>Dikembalikan</span>" : "<span style='color:red;'>Dipinjam</span>"; ?>
                </td>
                <td>
                    <?php if (!$row['tgl_kembali']) { ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_pinjam" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="kembali">Kembalikan</button>
                        </form>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <br><a href="dashboar_anggota.php">Kembali ke Dashboard</a>
</body>
</html>
