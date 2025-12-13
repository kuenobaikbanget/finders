<?php 
if(!isset($conn)) {
    include 'config/db_connect.php';
}

$id_rs = $_GET['id'] ?? null;

if(!$id_rs) {
    echo '<div class="p-4 text-red-500 bg-white rounded-xl">ID Rumah Sakit tidak ditemukan.</div>';
    exit;
}

// Query rumah sakit
$query_rs = mysqli_query($conn, "SELECT nama_rs, wilayah FROM data_rumah_sakit WHERE id_rs = '$id_rs'");

if(!$query_rs || mysqli_num_rows($query_rs) == 0) {
    echo '<div class="p-4 text-red-500 bg-white rounded-xl">Data Rumah Sakit tidak ditemukan.</div>';
    exit;
}

$rs = mysqli_fetch_array($query_rs);
?>

<div class="bg-white w-full max-w-4xl rounded-[2rem] shadow-2xl overflow-hidden relative animate-fade-in-up mx-4 flex flex-col max-h-[90vh]">
    
    <!-- Close Button -->
    <button onclick="closeModal()" class="absolute top-4 right-4 z-30 w-10 h-10 bg-black/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white hover:text-red-500 transition-all shadow-lg cursor-pointer border border-white/20">
        <i class="fa-solid fa-xmark text-xl"></i>
    </button>

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 shrink-0">
        <div class="flex items-center gap-3 text-white/80 text-sm mb-3">
            <i class="fa-solid fa-location-dot"></i>
            <span><?= htmlspecialchars($rs['wilayah']) ?></span>
        </div>
        <h1 class="text-2xl lg:text-3xl font-bold text-white mb-2">
            Jadwal Layanan
        </h1>
        <p class="text-blue-100 text-sm">
            <?= htmlspecialchars($rs['nama_rs']) ?>
        </p>
    </div>

    <!-- Content Section -->
    <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
        
        <!-- Loading State -->
        <div id="jadwalLoading" class="text-center py-12">
            <i class="fa-solid fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
            <p class="text-gray-500">Memuat jadwal...</p>
        </div>

        <!-- Content Container -->
        <div id="jadwalContent" class="hidden">
            <!-- Akan diisi dengan JavaScript -->
        </div>

        <!-- Empty State -->
        <div id="jadwalEmpty" class="hidden text-center py-12">
            <i class="fa-solid fa-calendar-xmark text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600 mb-2">Jadwal Tidak Tersedia</h3>
            <p class="text-gray-500 text-sm">Belum ada jadwal layanan untuk rumah sakit ini.</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="p-6 border-t border-gray-100 bg-gray-50 flex gap-3 shrink-0">
        <button onclick="closeModal()" class="flex-1 bg-white border border-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl text-center transition hover:bg-gray-50 hover:border-gray-300 text-sm">
            Kembali
        </button>
        <a href="booking.php?rs_id=<?= $id_rs ?>" class="flex-1 bg-finders-green hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-xl text-center transition shadow-lg shadow-green-200 text-sm">
            Buat Janji
        </a>
    </div>

</div>
