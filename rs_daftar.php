<?php
session_start();
require_once 'config/db_connect.php';

// Logika Pencarian
$search = "";
$query_sql = "SELECT * FROM data_rumah_sakit ORDER BY nama_rs ASC";

if (isset($_GET['q'])) {
    $search = mysqli_real_escape_string($conn, $_GET['q']);
    $query_sql = "SELECT * FROM data_rumah_sakit 
                  WHERE nama_rs LIKE '%$search%' 
                  OR wilayah LIKE '%$search%' 
                  ORDER BY nama_rs ASC";
}

$result = mysqli_query($conn, $query_sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Rumah Sakit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/style_user.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    <?php include 'includes/sidebar.php'; ?>

    <main class="flex-1 h-full overflow-y-auto relative scroll-smooth bg-gray-50">
        
        <!-- Header Section dengan Background Image dan Gradient -->
        <div class="relative w-full bg-gradient-to-br from-[#1e3a8a] to-[#1e40af] overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img src="assets/img/daftarrs_background.jpg" alt="Background" class="w-full h-full object-cover opacity-50">
            </div>
            
            <!-- Dark Overlay untuk Meningkatkan Kontras Teks -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#1e3a8a]/70 to-[#1e40af]/70"></div>
            
            <!-- Gradient Overlay untuk Transisi Smooth ke Body -->
            <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-b from-transparent to-gray-50"></div>
            
            <div class="relative z-10 container mx-auto px-6 lg:px-12 py-16 pb-24">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex-1">
                        <!-- Breadcrumb Navigation -->
                        <nav class="flex items-center gap-2 text-sm mb-8">
                            <a href="index.php" class="flex items-center gap-2 text-white/80 hover:text-white transition-colors">
                                <i class="fa-solid fa-home text-lg"></i>
                                <span class="font-medium">Beranda</span>
                            </a>
                            <i class="fa-solid fa-chevron-right text-white/60 text-xs"></i>
                            <span class="text-white font-semibold">Daftar Rumah Sakit</span>
                        </nav>

                        <!-- Header Content -->
                        <div>
                            <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6">Temukan Rumah Sakit</h1>
                            <p class="text-white/90 text-lg lg:text-xl max-w-3xl leading-relaxed">
                                Pilih rumah sakit terbaik untuk kebutuhan kesehatan Anda.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Search Bar -->
                    <form action="" method="GET" class="w-full md:w-1/3 relative">
                        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" 
                            placeholder="Cari nama RS atau wilayah..." 
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-white/20 bg-white/10 backdrop-blur-sm text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/20 transition shadow-lg">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-white/70"></i>
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white text-blue-700 px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-blue-50 transition shadow-md">
                            Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="container mx-auto px-6 lg:px-12 py-8 pb-20">
        
        <!-- Grid List RS -->
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col h-full">
                        
                        <div class="h-48 w-full overflow-hidden rounded-t-2xl relative bg-gray-100 isolate">
                            <img src="assets/img/<?= $row['foto'] ?>" 
                                alt="<?= $row['nama_rs'] ?>" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                style="transform: translateZ(0);">
                        </div>
                        
                        <div class="p-5 flex flex-col flex-1 bg-white rounded-b-2xl">
                            <div class="text-xs font-bold text-finders-green mb-1 uppercase tracking-wide">
                                <?= htmlspecialchars($row['wilayah']) ?>
                            </div>
                            
                            <h3 class="font-bold text-lg text-gray-800 mb-2 group-hover:text-finders-blue transition line-clamp-1">
                                <?= htmlspecialchars($row['nama_rs']) ?>
                            </h3>
                            
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2 flex-1">
                                <?= htmlspecialchars($row['deskripsi']) ?>
                            </p>
                            
                            <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                                <button onclick="openDetail(<?= $row['id_rs'] ?>)" class="flex-1 bg-blue-50 text-finders-blue text-xs font-bold py-2.5 rounded-xl hover:bg-[#1e3a8a] hover:text-white transition uppercase tracking-wide cursor-pointer shadow-sm">
                                    Detail
                                </button>
                                
                                <a href="booking.php?rs_id=<?= $row['id_rs'] ?>" class="flex-1 bg-finders-green hover:bg-green-600 text-white text-xs font-bold py-2.5 rounded-xl text-center transition uppercase tracking-wide shadow-sm flex items-center justify-center gap-2">
                                    buat Janji
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20">
                <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                    <i class="fa-solid fa-hospital-user text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-600">Rumah Sakit Tidak Ditemukan</h3>
                <p class="text-gray-500">Coba kata kunci lain atau hubungi admin.</p>
            </div>
        <?php endif; ?>
        
        </div>
        <!-- End Content Section -->

    </main>

    <!-- Modal Overlay -->
    <div id="modalOverlay" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

        <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
            <div id="modalContent" class="w-full flex justify-center">
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    
    <script>
    // Fallback jika fungsi belum tersedia dari script.js
    if (typeof openJadwal === 'undefined') {
        console.warn('openJadwal not found in script.js, defining fallback');
        window.openJadwal = function(id) {
            const overlay = document.getElementById('modalOverlay');
            const content = document.getElementById('modalContent');
            
            if (overlay) {
                overlay.classList.remove('hidden');
            }
            
            if (content) {
                content.innerHTML = `
                    <div class="bg-white p-6 rounded-2xl shadow-xl flex items-center gap-3 animate-pulse">
                        <div class="w-6 h-6 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                        <span class="font-medium text-gray-600">Memuat jadwal...</span>
                    </div>
                `;

                fetch('rs_jadwal.php?id=' + id)
                    .then(response => response.text())
                    .then(html => {
                        content.innerHTML = html;
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        content.innerHTML = '<div class="bg-white p-4 rounded-xl text-red-500">Gagal memuat jadwal. Silakan coba lagi.</div>';
                    });
            }
        };
    }
    </script>
</body>
</html>