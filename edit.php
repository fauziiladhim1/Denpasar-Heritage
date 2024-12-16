<?php
// edit.php

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

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Validasi bahwa id adalah integer
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
        exit();
    }

    // Query untuk mengambil data berdasarkan ID
    $sql = "SELECT * FROM destinasi_wisata WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menjalankan query']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyiapkan pernyataan SQL']);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
}
?>
