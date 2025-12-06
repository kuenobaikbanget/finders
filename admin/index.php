<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - FindeRS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/styles/style_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Animasi halus seperti UI utama FindeRS */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp .6s ease-out forwards; opacity:0; }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fadeInDown .6s ease-out forwards; opacity:0; }
    </style>
</head>

<body class="bg-gray-50 flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <?php include 'includes/sidebar_admin.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto px-6 py-6">

        <!-- HEADER -->
        <?php include 'includes/header_admin.php'; ?>

        <!-- ==============================
             SECTION: DASHBOARD OVERVIEW
        =============================== -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up">

            <!-- CARD: TOTAL PENJADWALAN -->
            <a href="jadwal_data.php" class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex items-center gap-4 transition hover:-translate-y-1">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-calendar-check text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Penjadwalan</p>
                    <p class="text-xl font-bold text-gray-800">
                        <!-- INSERT PHP + DATABASE -->
                        -
                    </p>
                </div>
            </a>

            <!-- CARD: TOTAL RUMAH SAKIT -->
            <a href="rs_data.php" class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex items-center gap-4 transition hover:-translate-y-1">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-hospital text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Data Rumah Sakit</p>
                    <p class="text-xl font-bold text-gray-800">-</p>
                </div>
            </a>

            <!-- CARD: TOTAL LAYANAN -->
            <a href="layanan_data.php" class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex items-center gap-4 transition hover:-translate-y-1">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-stethoscope text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Data Layanan</p>
                    <p class="text-xl font-bold text-gray-800">-</p>
                </div>
            </a>

            <!-- CARD: TOTAL USER -->
            <a href="users_data.php" class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex items-center gap-4 transition hover:-translate-y-1">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-red-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Akun User</p>
                    <p class="text-xl font-bold text-gray-800">-</p>
                </div>
            </a>

        </div>

        <!-- ==============================
             SECTION: TABEL PENJADWALAN TERBARU
        =============================== -->
        <div class="mt-10 bg-white p-6 rounded-2xl shadow-md border border-gray-100 animate-fade-in-up">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-700">Penjadwalan Kunjungan Terbaru</h2>

                <a href="#" 
                    onclick="openModal('jadwal_form.php')" 
                    class="px-4 py-2 bg-green-600 text-white rounded-xl text-sm hover:bg-green-700 transition">
                        + Tambah Jadwal
                </a>

            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-700 text-xs">
                        <tr>
                            <th class="py-3 px-4">ID</th>
                            <th class="py-3 px-4">Nama Pasien</th>
                            <th class="py-3 px-4">Rumah Sakit</th>
                            <th class="py-3 px-4">Layanan</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-600">
                        <!-- INSERT PHP + DATABASE -->
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-400 text-sm">
                                Belum ada data atau belum terhubung ke database.
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

        </div>

        <?php include 'includes/footer_admin.php'; ?>
    </main>

    <!-- OVERLAY (Modal) -->
    <div id="modalOverlay" 
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-[999] hidden">

        <div id="modalContent"
            class="bg-white w-[90%] max-w-xl p-6 rounded-2xl shadow-xl animate-fade-in-up relative">
            
            <!-- Close Button -->
            <button onclick="closeModal()" 
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>

            <!-- Dynamic Content Loaded Here -->
            <div id="modalBody" class="mt-4 text-gray-700 text-sm">
                Memuat...
            </div>

        </div>
    </div>

    <script>
    function openModal(url) {
        // Tampilkan overlay dulu
        document.getElementById("modalOverlay").classList.remove("hidden");

        // Tempat menaruh HTML dari file eksternal
        let target = document.getElementById("modalBody");
        target.innerHTML = "Memuat...";

        // AJAX load file PHP (form tambah / update)
        fetch(url)
            .then(response => response.text())
            .then(data => {
                target.innerHTML = data;
            })
            .catch(err => {
                target.innerHTML = "Gagal memuat data.";
            });
    }

    function closeModal() {
        document.getElementById("modalOverlay").classList.add("hidden");
    }
    </script>
</body>
</html>
