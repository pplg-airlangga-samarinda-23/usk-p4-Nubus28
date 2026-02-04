CREATE DATABASE perpustakaan;
USE perpustakaan;

-- 1. Tabel User
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'anggota') NOT NULL
);

-- 2. Tabel Book
CREATE TABLE Book (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    pengarang VARCHAR(255),
    deskripsi TEXT,
    genre VARCHAR(100)
);
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    stock INT DEFAULT 0
);


-- 3. Tabel Peminjaman
CREATE TABLE peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_book INT,
    id_user INT,
    tgl_pinjam DATE NOT NULL,
    tgl_kembali DATE,
    -- Menambahkan Relasi Foreign Key
    CONSTRAINT fk_book FOREIGN KEY (id_book) REFERENCES Book(id) ON DELETE CASCADE,
    CONSTRAINT fk_user FOREIGN KEY (id_user) REFERENCES User(id) ON DELETE CASCADE
);