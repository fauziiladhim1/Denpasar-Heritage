<?php
// leaflet.php

// Konfigurasi MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "responsi";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
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

// Tutup koneksi
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Denpasar Heritage</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;900&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(to right, rgb(27, 27, 32), #A64D79, #000);
            font-family: 'Poppins', sans-serif;
            color: #eeeeee;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .navbar {
            background: linear-gradient(to right, rgb(27, 27, 32), #A64D79, #000);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
            top: 0;
            width: 100%;
            z-index: 1000;
            text-shadow: 0 4px 6px rgba(255, 255, 255, 0.338);
        }

        .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            font-size: 1.8rem;
            color: #ffffff !important;
            text-decoration: none;
        }

        .navbar-nav .nav-link {
            font-size: 1rem;
            color: #eeeeee;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link:focus {
            color: #00adb5;
        }

        .navbar-nav .nav-link.active {
            color: #00adb5;
            font-weight: 700;
        }

        .section-heading {
            color: #16d4f6;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            text-align: center;
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        #map {
            height: 500px;
            border-radius: 20px;
            border: 2px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .content-container {
            max-width: 1200px;
            width: 100%;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        .card {
            background-color: #222;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: none;
            margin-bottom: 2rem;
        }

        .card .table-responsive {
            padding: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            text-align: center;
            vertical-align: middle;
            padding: 12px;
        }

        th {
            background-color: #00796b;
            color: #fff;
            font-size: 1.1em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        td {
            background-color: #f9f9f9;
            color: #333;
        }

        td:hover {
            background-color: #e8f5e9;
        }

        td img {
            width: 80px;
            height: auto;
            border-radius: 6px;
            object-fit: cover;
        }

        .btn-edit {
            background-color: #ffca28;
            border: none;
            color: #222;
            font-size: 0.9em;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-edit:hover {
            background-color: #f9a825;
            color: #ffffff;
        }

        .btn-delete {
            background-color: #e53935;
            border: none;
            color: #ffffff;
            font-size: 0.9em;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            margin-left: 5px;
        }

        .btn-delete:hover {
            background-color: #c62828;
        }

        .card-body {
            background-color: #393e46;
            border-radius: 0 0 12px 12px;
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 1.1em;
            margin-bottom: 8px;
            color: #ffecd1;
        }

        .form-control {
            background-color: #222831;
            color: #eeeeee;
            border: 2px solid #00adb5;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .form-control::placeholder {
            color: #b0bec5;
        }

        .form-control:focus {
            background-color: #1f262f;
            border-color: #ff97cb;
            outline: none;
            box-shadow: none;
        }

        .btn-primary.w-100 {
            background-color: #ff97cb;
            font-weight: 600;
            border: none;
            transition: background-color 0.3s ease;
            border-radius: 30px;
            padding: 0.75rem 1.5rem;
            font-size: 1em;
        }

        .btn-primary.w-100:hover {
            background-color: #d81b60;
        }

        .map-container {
            margin: 3rem auto 0 auto;
            max-width: 1200px;
            width: 100%;
            padding: 0 2rem;
        }

        /* Footer Styles */
        footer {
            padding: 2rem 0;
            background-color: #222831;
            color: #eeeeee;
            text-align: center;
            text-shadow: 0 4px 6px rgba(255, 255, 255, 0.338);
            margin-top: 3rem;
        }

        footer p {
            margin: 0;
            font-size: 1rem;
        }

        footer .social-icons a {
            margin: 0 10px;
            color: #eeeeee;
            transition: color 0.3s ease;
        }

        footer .social-icons a:hover {
            color: #00adb5;
        }

        /* Tambahan CSS untuk Destination Card */
        .horizontal-scroll {
            display: flex;
            overflow-x: auto;
            gap: 20px;
            padding-bottom: 1rem;
            scroll-behavior: smooth;
        }

        .destination-card {
            max-width: 415px;
            background-color: #1f1f1f;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s, box-shadow 0.3s;
            flex-shrink: 0;
        }

        .destination-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.5);
        }

        .destination-card img {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            max-height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #ffffff;
            font-weight: bold;
        }

        .card-text {
            font-size: 1rem;
            color: #cccccc;
            margin-bottom: 15px;
            height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-accent {
            background-color: #ff7f50;
            border: none;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s;
            font-weight: 600;
        }

        .btn-accent:hover {
            background-color: #ff5722;
        }

        /* Scrollbar Styling */
        .horizontal-scroll::-webkit-scrollbar {
            height: 8px;
        }

        .horizontal-scroll::-webkit-scrollbar-track {
            background: #2c2c2c;
            border-radius: 4px;
        }

        .horizontal-scroll::-webkit-scrollbar-thumb {
            background: #ff7f50;
            border-radius: 4px;
        }

        .horizontal-scroll::-webkit-scrollbar-thumb:hover {
            background: #ff5722;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .destination-card {
                min-width: 250px;
                max-width: 300px;
            }

            .card-title {
                font-size: 1.3rem;
            }

            .card-text {
                font-size: 0.9rem;
                height: 50px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="index.html">Denpasar Heritage</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Tambahkan justify-content-center di sini -->
                <ul class="navbar-nav mx-auto justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.html">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html#informasi">
                            <i class="fas fa-info me-1"></i> Informasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#destinasi">
                            <i class="fas fa-map-marker-alt me-1"></i> Destinasi Wisata
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="leaflet.php" id="loadMap">
                            <i class="fas fa-map me-1"></i> Peta Interaktif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html#kontak">
                            <i class="fas fa-envelope me-1"></i> Kontak
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Destinasi Section -->
    <section id="destinasi" class="py-5 bg-dark text-white animate__animated animate__fadeInUp">
        <div class="container">
            <h2 class="section-heading">Berbagai Destinasi Wisata Budaya</h2>
            <div class="horizontal-scroll" id="destinasi-container">
                <?php
                if (!empty($destinations)) {
                    foreach ($destinations as $row) {
                        echo '<div class="destination-card animate__animated animate__zoomIn">';
                        // Gambar Destinasi
                        if (!empty($row["gambar"])) {
                            echo '<img src="' . htmlspecialchars($row["gambar"]) . '" alt="' . htmlspecialchars($row["nama_tempat"]) . '">';
                        } else {
                            echo '<img src="default-image.jpg" alt="Gambar Tidak Tersedia">';
                        }
                        // Body Card
                        echo '<div class="card-body">';
                        // Nama Tempat
                        echo '<h5 class="card-title">' . htmlspecialchars($row["nama_tempat"]) . '</h5>';
                        // Deskripsi
                        echo '<p class="card-text">' . htmlspecialchars($row["deskripsi"]) . '</p>';
                        // Tombol Lihat Lokasi dengan data-id
                        echo '<a href="#" class="btn btn-accent lihat-lokasi" data-id="' . htmlspecialchars($row["id"]) . '" data-latitude="' . htmlspecialchars($row["latitude"]) . '" data-longitude="' . htmlspecialchars($row["longitude"]) . '">Lihat Lokasi</a>';
                        echo '</div>'; // End of card-body
                        echo '</div>'; // End of destination-card
                    }
                } else {
                    echo "<p class='text-center'>Tidak ada destinasi tersedia.</p>";
                }
                ?>
            </div>
        </div>
    </section>


    <!-- Peta Interaktif -->
    <div class="map-container">
        <h2 class="section-heading text-center mb-4">Peta Interaktif</h2>
        <div id="map"></div>
    </div>

    <!-- Data Wisata -->
    <div class="content-container">
        <h2 class="section-heading text-center">Data Wisata</h2>
        <div class="card">
            <div class="table-responsive">
                <?php if (!empty($destinations)): ?>
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Tempat</th>
                                <th>Wilayah Desa</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Gambar</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($destinations as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['nama_tempat']) ?></td>
                                    <td><?= htmlspecialchars($row['wilayah_desa']) ?></td>
                                    <td><?= htmlspecialchars($row['latitude']) ?></td>
                                    <td><?= htmlspecialchars($row['longitude']) ?></td>
                                    <td>
                                        <?php if (!empty($row['gambar'])): ?>
                                            <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar <?= htmlspecialchars($row['nama_tempat']) ?>">
                                        <?php else: ?>
                                            <span class="text-muted">Tidak ada gambar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                    <td>
                                        <button class="btn btn-edit btn-sm" onclick="editData(<?= $row['id'] ?>)">Edit</button>
                                        <button class="btn btn-delete btn-sm" onclick="deleteData(<?= $row['id'] ?>)">Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center py-3">Tidak ada data yang ditemukan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Form Input Data -->
    <div class="content-container">
    <h2 class="section-heading text-center">Input Data</h2>
    <div class="card">
        <div class="card-body">
            <form id="input-form" enctype="multipart/form-data">
                <input type="hidden" id="id" name="id">
                
                <div class="mb-3">
                    <label for="nama_tempat" class="form-label">Nama Tempat:</label>
                    <input type="text" id="nama_tempat" name="nama_tempat" class="form-control" required placeholder="Contoh: Pantai Sanur" style="color: white;">
                </div>
                
                <div class="mb-3">
                    <label for="wilayah_desa" class="form-label">Wilayah Desa:</label>
                    <input type="text" id="wilayah_desa" name="wilayah_desa" class="form-control" required placeholder="Contoh: Desa Sanur" style="color: white;">
                </div>
                
                <div class="mb-3">
                    <label for="latitude" class="form-label">Latitude:</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" required placeholder="-8.6705" style="color: white;">
                </div>
                
                <div class="mb-3">
                    <label for="longitude" class="form-label">Longitude:</label>
                    <input type="text" id="longitude" name="longitude" class="form-control" required placeholder="115.2126" style="color: white;">
                </div>
                
                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar (URL):</label>
                    <input type="text" id="gambar" name="gambar" class="form-control" required placeholder="Contoh: https://example.com/gambar.jpg" style="color: white;">
                </div>
                
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi:</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" required rows="3" placeholder="Deskripsi singkat tempat wisata" style="color: white;"></textarea>
                </div>
                
                <input type="submit" value="Submit" class="btn btn-primary w-100">
            </form>
        </div>
    </div>
</div>

    <!-- Footer -->
    <footer class="text-center py-4 text-white">
        <div class="container">
            <div class="mb-3 social-icons">
                <a href="https://github.com/fauziiladhim1" target="_blank" class="text-white me-3"><i class="fab fa-github fa-lg"></i></a>
                <a href="https://www.instagram.com/fauzil.as" target="_blank" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="https://www.linkedin.com/in/fauziiladhim" target="_blank" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
            </div>
            <p>&copy; 2024 Denpasar Heritage. All rights reserved.</p>
            <p><a href="#" class="text-white">Nama: Muhammad Fauzil Adhim Sulistyo</a> | <a href="#" class="text-white">NIM: 23/521853/SV/23514</a>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <!-- Definisikan var markers sebelum memuat script.js -->
    <script>
        var markers = <?php echo json_encode($destinations); ?>;
    </script>
    <script src="js/script.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>