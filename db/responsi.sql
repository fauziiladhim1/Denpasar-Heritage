-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Des 2024 pada 04.33
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `responsi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `destinasi_wisata`
--

CREATE TABLE `destinasi_wisata` (
  `id` int(11) NOT NULL,
  `nama_tempat` varchar(255) NOT NULL,
  `wilayah_desa` varchar(255) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `destinasi_wisata`
--

INSERT INTO `destinasi_wisata` (`id`, `nama_tempat`, `wilayah_desa`, `longitude`, `latitude`, `gambar`, `deskripsi`) VALUES
(1, 'Werdhi Budaya Art Centre', 'Denpasar Selatan', 115.233828, -8.655494, 'https://lh3.googleusercontent.com/p/AF1QipOCFWHX1R9S_aS4PNUA-cAcpuHEl0_UZJdSG6tV=s294-w294-h220-k-no', 'Galeri kerajinan & panggung outdoor untuk pertunjukan tari Bali di tengah gerbang tradisional & paviliun.'),
(2, 'Monumen Bajra Sandhi', 'Denpasar Selatan', 115.233913, -8.671682, 'https://lh3.googleusercontent.com/p/AF1QipP_Q4wTrQKmzNTvQa8MlV7wKy8EQC4na5DArGv3=s294-w294-h220-k-no', 'Monumen terkenal yang penuh dengan ornamen bagi orang Bali, dengan taman berumput di sekitarnya.'),
(3, 'Pasar Badung', 'Kota Denpasar', 115.212677, -8.655516, 'https://lh3.googleusercontent.com/p/AF1QipN0aO-gndxVqxIEE7vui9C_jDjkfUSdx8JchHYd=s294-w294-h220-k-no', 'Pasar tradisional tanpa atap dengan beragam barang, termasuk bahan makanan, pakaian & suvenir.'),
(4, 'Museum Bali', 'Kota Denpasar', 115.218623, -8.657447, 'https://lh3.googleusercontent.com/proxy/WhKp40HxCE2NL_2SU2eW_RWSogmI7TTav2Eh5MxXR5V1AiGoDvTfPTa6LLvczZJbeqd_mY_13poN3YI3zUTxvrkmIdmhnzbQKGqvqHBYibhDUKWqkaGkVBSMlOEjIwAURAZYjt4ik09ZEcP6-f20fh9TL8B7Ag=s1360-w1360-h1020-rw', 'Pameran seni & sejarah Bali yang menampilkan patung, tekstil, temuan arkeologi & artefak lain.'),
(5, 'Pantai Sanur', 'Denpasar Selatan', 115.262611, -8.706808, 'https://lh3.googleusercontent.com/p/AF1QipP4oXMaV3qOXrsjD4NmsgCm6ghoRrAZvki8Jnm-=s294-w294-h220-k-no', 'Perahu nelayan penuh warna menghiasi pantai di kota resor ini, dengan galeri, restoran & kuil tua.'),
(6, 'Pura Sakenan', 'Serangan', 115.229493, -8.724735, 'https://lh3.googleusercontent.com/p/AF1QipNuZXdMyqoMwiueEyVNokApPSiMiV9yqenXBpRd=s294-w294-h220-k-no', 'Pura di pulau yang tenang dan situs ziarah yang didedikasikan untuk dewa Siwa, dengan kuil beratap jerami.'),
(20, 'Pantai Mertasari', 'Desa Selatan', 115.252236, -8.712385, 'https://lh3.googleusercontent.com/p/AF1QipNVp6A-MdFTXDOWrIC9p7MdAblg4h-uWrXc5HWq=s294-w294-h220-k-no', 'Tempat liburan pantai sejuk dengan rekreasi berenang, naik perahu, selancar angin & festival layang-layang.'),
(21, 'Pantai Sindhu', 'Denpasar Selatan', 115.264417, -8.683651, 'https://lh5.googleusercontent.com/p/AF1QipNPP4c4VPUZDwrbMup_4ERTlzuT5GTtA6WFr-HZ=w408-h306-k-no', 'Pantai santai untuk berenang & menikmati matahari terbit dengan pertokoan, kafe & penjual makanan di dekatnya.'),
(22, 'Desa Budaya Kertalangu', 'Denpasar Timur', 115.256608, -8.642060, 'https://lh5.googleusercontent.com/p/AF1QipMQXSiBtCJZ_nK1ZS4SujTpnlAtQl6a0mM6MFsE=w408-h306-k-no', 'Jogging menjadi kegiatan favorit di bekas desa yang indah dengan area berumput, kolam & gong raksasa.'),
(23, 'Taman Gong Perdamaian', 'Kota Denpasar', 115.256847, -8.643192, 'https://lh5.googleusercontent.com/p/AF1QipOnktjkB1oGHWuoNsD9VC2INYsbVJbxgDuF4LmY=w408-h725-k-no', 'Gong perdamaian berfungsi sebagai lambang perdamaian dunia. Lambang yang sama yakni Gong Perdamaian saat ini sudah ada sembilan monumen di seluruh dunia.');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `destinasi_wisata`
--
ALTER TABLE `destinasi_wisata`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `destinasi_wisata`
--
ALTER TABLE `destinasi_wisata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
