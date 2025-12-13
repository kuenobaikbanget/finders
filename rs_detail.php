<?php 
// Include koneksi database (Path relatif disesuaikan karena dipanggil dari index.php)
// Cek path: Jika file ini dipanggil via AJAX dari index.php, path-nya tetap relatif dari index.php
// Namun untuk aman, kita cek koneksi dulu
if(!isset($conn)) {
    include 'config/db_connect.php';
}

$id_rs = $_GET['id'] ?? null;

if(!$id_rs) {
    echo '<div class="p-4 text-red-500 bg-white rounded-xl">ID Rumah Sakit tidak ditemukan.</div>';
    exit;
}

// Query rumah sakit
$query_rs = mysqli_query($conn, "SELECT * FROM data_rumah_sakit WHERE id_rs = '$id_rs'");

if(!$query_rs || mysqli_num_rows($query_rs) == 0) {
    echo '<div class="p-4 text-red-500 bg-white rounded-xl">Data Rumah Sakit tidak ditemukan.</div>';
    exit;
}

$rs = mysqli_fetch_array($query_rs);

// Query layanan - pastikan menggunakan id_rs yang sama
$query_layanan = mysqli_query($conn, "SELECT * FROM data_layanan_rs WHERE id_rs = '$id_rs'");

// Debug: Cek apakah query layanan berhasil
if(!$query_layanan) {
    error_log("Error query layanan: " . mysqli_error($conn));
    echo "<!-- DEBUG: Query layanan error: " . mysqli_error($conn) . " -->";
} else {
    $jumlah_layanan = mysqli_num_rows($query_layanan);
    echo "<!-- DEBUG: Query layanan sukses. Jumlah layanan ditemukan: $jumlah_layanan untuk RS ID: $id_rs -->";
}
?>

<div class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden relative animate-fade-in-up mx-4 flex flex-col max-h-[90vh]">
    
    <!-- Close Button -->
    <button onclick="closeModal()" class="absolute top-4 right-4 z-30 w-10 h-10 bg-black/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white hover:text-red-500 transition-all shadow-lg cursor-pointer border border-white/20">
        <i class="fa-solid fa-xmark text-xl"></i>
    </button>

    <!-- Image Section -->
    <div class="w-full h-64 relative shrink-0">
        <img src="assets/img/<?= $rs['foto'] ?>" 
             alt="<?= $rs['nama_rs'] ?>" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
        
        <!-- Floating Badge -->
        <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg text-sm font-bold text-finders-blue flex items-center gap-2 shadow-lg">
            <i class="fa-solid fa-location-dot text-red-500"></i>
            <?= htmlspecialchars($rs['wilayah']) ?>
        </div>
    </div>

    <!-- Content Section -->
    <div class="w-full flex flex-col bg-white overflow-hidden">
        
        <!-- Scrollable Content -->
        <div class="p-6 overflow-y-auto custom-scrollbar">
            
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <h1 class="text-2xl font-bold text-gray-800 leading-tight">
                    <?= htmlspecialchars($rs['nama_rs']) ?>
                </h1>
                <button class="text-gray-300 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-bookmark text-2xl"></i>
                </button>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 gap-4 mb-6">
                <div class="flex items-start gap-3 text-gray-600">
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center shrink-0 text-finders-blue">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <p class="text-sm leading-relaxed pt-1"><?= htmlspecialchars($rs['alamat']) ?></p>
                </div>
                <div class="flex items-center gap-3 text-gray-600">
                    <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center shrink-0 text-green-600">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <p class="text-sm font-medium"><?= htmlspecialchars($rs['no_telpon']) ?></p>
                </div>
                <div class="flex items-center gap-3 text-gray-600">
                    <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center shrink-0 text-purple-600">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <p class="text-sm font-medium">Buka 24 Jam</p>
                </div>
            </div>

            <!-- Services -->
            <div class="mb-4">
                <h3 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-hospital-user text-finders-blue"></i>
                    Layanan Unggulan
                </h3>
                
                <?php 
                // Debug info
                $debug_jumlah = $query_layanan ? mysqli_num_rows($query_layanan) : 0;
                echo "<!-- DEBUG LAYANAN: id_rs=$id_rs, jumlah_row=$debug_jumlah -->";
                
                if($query_layanan && mysqli_num_rows($query_layanan) > 0): 
                    $layanan_count = 0;
                ?>
                    <div class="flex flex-wrap gap-2">
                        <?php while($lay = mysqli_fetch_array($query_layanan)): 
                            $layanan_count++;
                            echo "<!-- LAYANAN #$layanan_count: {$lay['nama_layanan']} -->";
                        ?>
                            <span class="px-3 py-1.5 bg-gradient-to-r from-blue-50 to-green-50 text-gray-700 rounded-full text-xs font-medium border border-blue-200 hover:border-finders-blue transition">
                                <i class="fa-solid fa-check-circle text-green-500 mr-1"></i>
                                <?= htmlspecialchars($lay['nama_layanan']) ?>
                            </span>
                        <?php endwhile; ?>
                    </div>
                    <p class="text-gray-400 text-xs mt-4">
                        <i class="fa-solid fa-info-circle"></i> Total <?= $layanan_count ?> layanan tersedia
                    </p>
                <?php else: ?>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                        <i class="fa-solid fa-circle-info text-gray-300 text-3xl mb-2"></i>
                        <p class="text-gray-400 italic text-sm">Data layanan belum tersedia untuk rumah sakit ini.</p>
                        <p class="text-gray-400 text-xs mt-1">Silakan hubungi rumah sakit untuk informasi lebih lanjut.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="p-6 border-t border-gray-100 bg-gray-50 flex gap-3 shrink-0">
            <button onclick="openJadwal(<?= $rs['id_rs'] ?>)" class="flex-1 bg-white border border-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl text-center transition hover:bg-gray-50 hover:border-gray-300 flex items-center justify-center gap-2 text-sm cursor-pointer">
                Lihat Jadwal
            </button>
            <a href="booking.php?rs_id=<?= $rs['id_rs'] ?>" class="flex-1 bg-finders-green hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-xl text-center transition shadow-lg shadow-green-200 flex items-center justify-center gap-2 text-sm">
                Buat Janji
            </a>
        </div>

    </div>
</div>