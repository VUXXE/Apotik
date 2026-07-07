CREATE DATABASE IF NOT EXISTS db_apotek;
USE db_apotek;

-- Membuat tabel kategori_obat
CREATE TABLE kategori_obat (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

-- Membuat tabel data_obat dengan relasi ke kategori_obat
CREATE TABLE data_obat (
    id_obat INT AUTO_INCREMENT PRIMARY KEY,
    nama_obat VARCHAR(150) NOT NULL,
    id_kategori INT,
    harga INT NOT NULL,
    stok INT NOT NULL,
    tanggal_kadaluarsa DATE NOT NULL,
    gambar VARCHAR(255) DEFAULT 'default.jpg',
    FOREIGN KEY (id_kategori) REFERENCES kategori_obat(id_kategori) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Memasukkan data awal (dummy data) untuk kategori_obat
INSERT INTO kategori_obat (nama_kategori) VALUES 
('Analgesik / Pereda Nyeri'),
('Antibiotik'),
('Vitamin & Suplemen'),
('Obat Batuk & Flu');

-- Memasukkan data awal (dummy data) untuk data_obat
INSERT INTO data_obat (nama_obat, id_kategori, harga, stok, tanggal_kadaluarsa) VALUES 
('Paracetamol 500mg', 1, 5000, 100, '2025-12-31'),
('Amoxicillin 500mg', 2, 12000, 50, '2024-10-15'),
('Vitamin C 1000mg', 3, 15000, 200, '2026-05-20'),
('Bodrex', 1, 8000, 150, '2025-08-17');

-- Membuat tabel users
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

-- Memasukkan data admin default (password: admin123)
INSERT INTO users (nama_lengkap, username, password, role) VALUES 
('Administrator', 'admin', '$2y$12$998Fczvo0KAffYrkySkoqumWStKQqirGBcLPhcF1V0Wxj2CSY4fzy', 'admin');

-- Membuat tabel orders
CREATE TABLE orders (
    id_order INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    total_harga INT NOT NULL,
    status ENUM('pending', 'dibayar', 'dikirim', 'selesai', 'batal') NOT NULL DEFAULT 'pending',
    alamat TEXT NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    tanggal_order TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Membuat tabel order_details
CREATE TABLE order_details (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_order INT NOT NULL,
    id_obat INT NOT NULL,
    jumlah INT NOT NULL,
    harga_satuan INT NOT NULL,
    FOREIGN KEY (id_order) REFERENCES orders(id_order) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_obat) REFERENCES data_obat(id_obat) ON DELETE CASCADE ON UPDATE CASCADE
);
