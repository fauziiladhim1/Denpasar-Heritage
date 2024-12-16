<?php
// input.php

header('Content-Type: application/json');

// Konfigurasi MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "responsi";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi gagal: ' . $conn->connect_error]);
    exit();
}

// Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nama_tempat = isset($_POST['nama_tempat']) ? $conn->real_escape_string($_POST['nama_tempat']) : '';
    $wilayah_desa = isset($_POST['wilayah_desa']) ? $conn->real_escape_string($_POST['wilayah_desa']) : '';
    $latitude = isset($_POST['latitude']) ? doubleval($_POST['latitude']) : 0;
    $longitude = isset($_POST['longitude']) ? doubleval($_POST['longitude']) : 0;
    $gambar = isset($_POST['gambar']) ? $conn->real_escape_string($_POST['gambar']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? $conn->real_escape_string($_POST['deskripsi']) : '';

    if ($id > 0) {
        // Update data
        $stmt = $conn->prepare("UPDATE destinasi_wisata SET nama_tempat = ?, wilayah_desa = ?, latitude = ?, longitude = ?, gambar = ?, deskripsi = ? WHERE id = ?");
        $stmt->bind_param("ssddssi", $nama_tempat, $wilayah_desa, $latitude, $longitude, $gambar, $deskripsi, $id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
        }
        $stmt->close();
    } else {
        // Insert data baru
        $stmt = $conn->prepare("INSERT INTO destinasi_wisata (nama_tempat, wilayah_desa, latitude, longitude, gambar, deskripsi) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddss", $nama_tempat, $wilayah_desa, $latitude, $longitude, $gambar, $deskripsi);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambahkan.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan data.']);
        }
        $stmt->close();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak diizinkan.']);
}

$conn->close();
?>
