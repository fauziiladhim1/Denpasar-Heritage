// js/script.js

document.addEventListener("DOMContentLoaded", () => {
  initializeMap();
  setupFormSubmission();
  setupLihatLokasiButtons();
});

// Object untuk menyimpan instance marker, diindeks oleh id
var markerObjects = {};

// Fungsi untuk menginisialisasi peta
function initializeMap() {
  // Inisialisasi peta di posisi Denpasar
  var map = L.map("map").setView([-8.6705, 115.2126], 12);

  // Tambahkan tile layer dari OpenStreetMap
  var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
    maxZoom: 19,
  }).addTo(map);

  // Definisikan baseMaps dan overlayMaps
  var baseMaps = {
    OpenStreetMap: osm,
  };

  var overlayMaps = {};

  // Tambahkan kontrol layer
  var layerControl = L.control
    .layers(baseMaps, overlayMaps, {
      collapsed: false,
    })
    .addTo(map);

  // Tambahkan marker yang sudah ada dari PHP
  markers.forEach(function (marker) {
    if (marker.latitude && marker.longitude) {
      var markerInstance = L.marker([marker.latitude, marker.longitude])
        .addTo(map)
        .bindPopup(
          "<strong>Nama Tempat:</strong> " +
            escapeHtml(marker.nama_tempat) +
            "<br>" +
            "<strong>Deskripsi:</strong> " +
            escapeHtml(marker.deskripsi)
        );

      // Simpan instance marker dengan key id
      markerObjects[marker.id] = markerInstance;
    }
  });

  // Tambahkan layer GeoJSON untuk Jalan
  fetch("data/KOTA%20DENPASAR/KOTA%20DENPASAR/JALAN_LN_25K.geojson")
    .then((response) => response.json())
    .then((data) => {
      var jalan = L.geoJSON(data, {
        style: { color: "blue", weight: 2 },
      }).addTo(map);
      overlayMaps["Jalan"] = jalan;
      layerControl.addOverlay(jalan, "Jalan"); // Tambahkan ke kontrol layer
    })
    .catch((error) => console.error("Error loading GeoJSON jalan:", error));

  // Tambahkan layer GeoJSON untuk Administrasi Desa
  fetch("data/KOTA%20DENPASAR/KOTA%20DENPASAR/ADMINISTRASIDESA_AR.geojson")
    .then((response) => response.json())
    .then((data) => {
      var adminDesa = L.geoJSON(data, {
        style: { color: "green", weight: 2, fillOpacity: 0.1 },
      }).addTo(map);
      overlayMaps["Batas Administrasi Desa"] = adminDesa;
      layerControl.addOverlay(adminDesa, "Batas Administrasi Desa"); // Tambahkan ke kontrol layer
    })
    .catch((error) =>
      console.error("Error loading GeoJSON administrasi desa:", error)
    );

  // Tambahkan kontrol skala
  L.control
    .scale({
      position: "bottomright",
      metric: true,
      imperial: false,
    })
    .addTo(map);

  // Simpan referensi map di window agar bisa diakses dari fungsi lain
  window.myMap = map;
}

// Fungsi untuk menghapus data
function deleteData(id) {
  Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Data yang dihapus tidak dapat dikembalikan!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Ya, hapus!",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("delete.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "id=" + encodeURIComponent(id),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            Swal.fire("Berhasil!", data.message, "success").then(() =>
              location.reload()
            ); // Muat ulang halaman setelah berhasil
          } else {
            Swal.fire("Gagal!", data.message, "error");
          }
        })
        .catch((error) => {
          Swal.fire("Gagal!", "Terjadi kesalahan: " + error, "error");
        });
    }
  });
}

// Fungsi untuk mengedit data
function editData(id) {
  fetch("edit.php?id=" + id)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Jaringan bermasalah atau server mengembalikan error.");
      }
      return response.json();
    })
    .then((data) => {
      if (data.status !== "error") {
        // Mengisi form dengan data yang diterima dari server
        document.getElementById("id").value = data.data.id;
        document.getElementById("nama_tempat").value = data.data.nama_tempat;
        document.getElementById("wilayah_desa").value = data.data.wilayah_desa;
        document.getElementById("latitude").value = data.data.latitude;
        document.getElementById("longitude").value = data.data.longitude;
        document.getElementById("gambar").value = data.data.gambar;
        document.getElementById("deskripsi").value = data.data.deskripsi;

        // Menampilkan pesan sukses
        Swal.fire({
          title: "Edit Data",
          text: "Silakan perbarui data di formulir",
          icon: "info",
          confirmButtonText: "OK",
        }).then(() => {
          window.scrollTo(0, document.body.scrollHeight); // Scroll ke bawah agar form terlihat
        });
      } else {
        Swal.fire("Gagal!", data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      Swal.fire(
        "Gagal!",
        "Terjadi kesalahan saat mengambil data: " + error.message,
        "error"
      );
    });
}

// Fungsi untuk menangani submit form
function setupFormSubmission() {
  const form = document.getElementById("input-form");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault(); // Mencegah form dari reload halaman

      const formData = new FormData(this);

      fetch("input.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          console.log(data); // Debugging
          if (data.status === "success") {
            Swal.fire("Berhasil!", data.message, "success").then(() => {
              form.reset(); // Reset form
              location.reload(); // Reload halaman untuk menampilkan data terbaru
            });
          } else {
            Swal.fire("Gagal!", data.message, "error");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          Swal.fire("Gagal!", "Terjadi kesalahan: " + error, "error");
        });
    });
  }
}

// Fungsi untuk menambahkan keamanan pada input HTML
function escapeHtml(text) {
  if (typeof text !== "string") {
    return text;
  }
  const map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  };
  return text.replace(/[&<>"']/g, function (m) {
    return map[m];
  });
}

// Fungsi untuk menangani klik tombol "Lihat Lokasi"
function setupLihatLokasiButtons() {
  var lihatLokasiButtons = document.querySelectorAll(".lihat-lokasi");
  lihatLokasiButtons.forEach(function (button) {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      var id = this.getAttribute("data-id");
      if (id && markerObjects[id]) {
        var marker = markerObjects[id];
        var latLng = marker.getLatLng();
        window.myMap.setView(latLng, 14); // Pan ke marker dengan zoom level 14
        marker.openPopup(); // Buka popup marker
      } else {
        // Jika id tidak ditemukan atau marker tidak ada, fallback
        var lat = this.getAttribute("data-latitude");
        var lng = this.getAttribute("data-longitude");
        var namaTempat =
          this.closest(".destination-card").querySelector(
            ".card-title"
          ).textContent;
        var deskripsi =
          this.closest(".destination-card").querySelector(
            ".card-text"
          ).textContent;
        if (lat && lng) {
          window.myMap.setView([lat, lng], 14);
          // Membuat marker baru sementara dan menampilkan popup
          var tempMarker = L.marker([lat, lng])
            .addTo(window.myMap)
            .bindPopup(
              "<strong>Nama Tempat:</strong> " +
                escapeHtml(namaTempat) +
                "<br>" +
                "<strong>Deskripsi:</strong> " +
                escapeHtml(deskripsi)
            )
            .openPopup();
          // Menghapus marker sementara setelah beberapa detik
          setTimeout(function () {
            window.myMap.removeLayer(tempMarker);
          }, 5000); // 5 detik
        }
      }
    });
  });
}
