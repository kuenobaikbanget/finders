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

    <main class="flex-1 h-full overflow-y-auto relative scroll-smooth p-6 lg:p-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-finders-blue">Temukan Rumah Sakit</h1>
                <p class="text-gray-500">Pilih rumah sakit terbaik untuk kebutuhan kesehatan Anda.</p>
            </div>
            
            <!-- Search Bar -->
            <form action="" method="GET" class="w-full md:w-1/3 relative">
                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" 
                    placeholder="Cari nama RS atau wilayah..." 
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-finders-blue text-white px-4 py-1.5 rounded-lg text-sm hover:bg-blue-800 transition">
                    Cari
                </button>
            </form>
        </div>

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
                                <a href="booking.php?rs_id=<?= $row['id_rs'] ?>" class="flex-1 bg-finders-green hover:bg-green-600 text-white text-xs font-bold py-2.5 rounded-xl text-center transition uppercase tracking-wide shadow-sm flex items-center justify-center gap-2">
                                    <i class="fa-regular fa-calendar-check"></i> Jadwalkan
                                </a>
                                
                                <button onclick="openDetail(<?= $row['id_rs'] ?>)" class="flex-1 bg-blue-50 text-finders-blue text-xs font-bold py-2.5 rounded-xl hover:bg-[#1e3a8a] hover:text-white transition uppercase tracking-wide cursor-pointer shadow-sm">
                                    Detail
                                </button>
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
</body>
</html>