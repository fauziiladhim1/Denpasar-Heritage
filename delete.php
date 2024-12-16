<?php
// delete.php

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

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Validasi bahwa id adalah integer
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
        exit();
    }

    // Query untuk menghapus data
    $sql = "DELETE FROM destinasi_wisata WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
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
