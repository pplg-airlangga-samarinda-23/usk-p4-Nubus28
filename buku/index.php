<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
</head>
<body>
    <h1>Daftar Buku</h1>
    
    <a href="create.php">+ Tambah Buku</a><br><br>
    
    <?php
    $conn = new mysqli('localhost', 'root', '', 'perpustakaan');
    
    $sql = "SELECT * FROM Book";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>No</th><th>Judul</th><th>Pengarang</th><th>Deskripsi</th><th>Aksi</th></tr>";
        
        $no = 1;
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no . "</td>";
            echo "<td>" . $row['judul'] . "</td>";
            echo "<td>" . $row['pengarang'] . "</td>";
            echo "<td>" . substr($row['deskripsi'], 0, 50) . "...</td>";
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
    
    $conn->close();
    ?>
</body>
</html>
