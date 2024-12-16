<?php
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

// Ambil data dari tabel destinasi_wisata
$sql = "SELECT * FROM destinasi_wisata";
$result = $conn->query($sql);

// Siapkan data untuk JSON
$destinations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $destinations[] = $row;
    }
}

// Kembalikan data dalam format JSON
echo json_encode(['status' => 'success', 'data' => $destinations]);

// Tutup koneksi
$conn->close();
?>
