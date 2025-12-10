<?php 
session_start(); 
include 'config/db_connect.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/style_user.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    <?php include 'includes/sidebar.php'; ?>


    <main class="flex-1 h-full overflow-y-auto relative scroll-smooth bg-gray-50">
        
        <div class="relative w-full">
            <div class="absolute inset-0 w-full h-full">
                <img src="assets/img/home_background.jpg" alt="Hospital Background" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-blue-950/60 mix-blend-multiply"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-black/40 via-transparent to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-gray-50/20 to-transparent"></div>
            </div>
            
            <div class="relative z-10 container mx-auto px-6 lg:px-12 pt-28 pb-24 xl:pb-12 flex flex-col justify-center">
                <div class="w-full lg:w-2/3 animate-fade-in-down">
                    <h1 class="text-4xl lg:text-6xl font-bold text-white leading-tight mb-4 drop-shadow-lg">
                        Finde<span class="text-finders-green">RS</span>
                    </h1>
                    <p class="text-white text-lg lg:text-xl mb-8 max-w-xl leading-relaxed drop-shadow-md font-medium">
                        Temukan rumah sakit dan layanan medis terbaik yang Anda butuhkan dengan cepat, tepat, dan mudah.
                    </p>

                    <form action="layanan.php" method="GET" class="w-full">
                        <div class="glass-card p-2 rounded-2xl flex items-center shadow-2xl max-w-2xl transform hover:scale-[1.01] transition-transform duration-300">
                            <div class="pl-4 text-gray-400">
                                <i class="fa-solid fa-magnifying-glass text-xl"></i>
                            </div>
                            <input type="text" name="keyword" required
                                placeholder="Cari: misal 'Kanker', 'Gigi', 'Jantung'..." 
                                class="w-full bg-transparent border-none focus:ring-0 focus:outline-none text-gray-800 placeholder-gray-500 px-4 py-3 text-base">
                            <button type="submit" class="bg-finders-blue hover:bg-finders-green text-white px-6 lg:px-8 py-3 rounded-xl font-semibold transition-all shadow-lg flex items-center gap-2 whitespace-nowrap">
                                <i class="fa-solid fa-search"></i>
                                <span class="hidden sm:inline">Cari</span>
                            </button>
                        </div>
                    </form>

                    <div class="flex flex-wrap gap-6 mt-8 mb-6">
                        <div class="flex items-center gap-2 text-white">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <i class="fa-solid fa-hospital text-finders-green"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-white drop-shadow-md">500+</p>
                                <p class="text-xs text-white font-medium drop-shadow-md">Rumah Sakit</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-white">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center backdrop-blur-sm shadow-lg">
                                <i class="fa-solid fa-user-doctor text-finders-green"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-white drop-shadow-md">10K+</p>
                                <p class="text-xs text-white font-medium drop-shadow-md">Dokter</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-white">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center backdrop-blur-sm shadow-lg">
                                <i class="fa-solid fa-users text-finders-green"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-white drop-shadow-md">1M+</p>
                                <p class="text-xs text-white font-medium drop-shadow-md">Pengguna</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute z-20 flex flex-col gap-4 items-end bottom-8 right-6 lg:bottom-10 lg:right-10 xl:top-20 xl:right-10 xl:bottom-auto">
                <div class="hidden xl:block w-80 animate-fade-in-down" style="animation-delay: 0.2s;">
                    <div class="glass-card rounded-2xl p-5 shadow-2xl border-t-4 border-t-finders-green transition hover:-translate-y-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-blue-100 p-2 rounded-lg text-finders-blue">
                                <i class="fa-solid fa-bolt text-xl"></i>
                            </div>
                            <h3 class="font-bold text-gray-800 text-lg">Fitur Cepat</h3>
                        </div>
                        <div class="w-full h-32 bg-gradient-to-br from-blue-100 to-green-100 rounded-xl mb-3 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fa-solid fa-stethoscope text-4xl text-finders-blue/40 mb-2"></i>
                                <p class="text-xs text-gray-400">Gambar Fitur</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 mb-4 leading-relaxed font-medium">Akses cepat ke penjadwalan, informasi layanan rumah sakit, dan dashboard admin.</p>
                        <div class="flex gap-2">
                            <button class="flex-1 bg-finders-green text-white py-2 rounded-lg text-xs font-bold shadow-md hover:bg-green-600 transition">Buka Dashboard</button>
                            <button class="flex-1 border border-gray-300 text-gray-600 py-2 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">Info Lanjut</button>
                        </div>
                    </div>
                </div>

                <div class="animate-bounce-slow">
                    <div class="bg-red-600 text-white rounded-2xl p-4 shadow-red-500/50 shadow-xl flex items-center gap-4 hover:bg-red-700 transition cursor-pointer group transform hover:scale-105">
                        <div class="text-left">
                            <p class="text-[10px] font-bold uppercase opacity-90 tracking-wider">Kontak Darurat</p>
                            <p class="text-xl font-bold leading-none">112 / 119</p>
                        </div>
                        <div class="bg-white/20 w-10 h-10 rounded-xl flex items-center justify-center group-hover:rotate-12 transition">
                            <i class="fa-solid fa-phone-volume text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative z-10 container mx-auto px-6 lg:px-12 mt-12 pb-32">
                <div class="w-full animate-fade-in-up" style="animation-delay: 0.3s;">
                    <h2 class="text-2xl font-bold text-white mb-8 drop-shadow-md flex items-center gap-2">
                        Cari Layanan Kesehatan
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        <?php 
                        $query_kat = mysqli_query($conn, "SELECT kategori, COUNT(*) as total FROM data_layanan_rs GROUP BY kategori ORDER BY total DESC LIMIT 4");

                        function getServiceStyle($kategori) {
                            $kategori = strtolower($kategori);
                            $style = [
                                'image' => 'default_service.jpg',
                                'gradient' => 'from-gray-500/90 via-gray-600/90 to-gray-800/90', 
                                'border' => 'border-gray-400/50'
                            ];

                            if (strpos($kategori, 'bedah') !== false) {
                                $style = [
                                    'image' => 'layanan_bedah.jpg',
                                    'gradient' => 'from-green-500/90 via-green-600/80 to-green-900/90',
                                    'border' => 'border-green-400/50'
                                ];
                            }
                            elseif (strpos($kategori, 'darurat') !== false || strpos($kategori, 'ugd') !== false) {
                                $style['image'] = 'layanan_igd.jpg';
                            }
                            elseif (strpos($kategori, 'penunjang') !== false || strpos($kategori, 'radiologi') !== false) {
                                $style['image'] = 'layanan_penunjang.png';
                            }
                            elseif (strpos($kategori, 'spesialis') !== false) {
                                $style['image'] = 'layanan_spesialis.jpg';
                            }

                            return $style;
                        }

                        if(mysqli_num_rows($query_kat) > 0) {
                            while($kat = mysqli_fetch_array($query_kat)) {
                                $style = getServiceStyle($kat['kategori']);
                                $nama_kategori = htmlspecialchars($kat['kategori']);
                        ?>

                        <a href="layanan.php?kategori=<?= urlencode($kat['kategori']) ?>" class="block h-full">
                            <div class="relative h-44 rounded-2xl border border-gray-200 shadow-lg overflow-hidden transition-transform hover:scale-[1.02] cursor-pointer group">
                                <img src="assets/img/<?= $style['image'] ?>" 
                                    alt="<?= $nama_kategori ?>" 
                                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80"></div>
                                <span class="absolute bottom-4 left-4 text-white font-bold text-xl drop-shadow-md z-10 tracking-wide">
                                    <?= $nama_kategori ?>
                                </span>
                            </div>
                        </a>

                        <?php 
                            } 
                        } else {
                            $dummies = ['Penunjang', 'Gawat Darurat', 'Bedah', 'Spesialis'];
                            foreach($dummies as $d) {
                                $style = getServiceStyle($d);
                                echo '
                                <div class="relative h-44 rounded-2xl border border-gray-200 shadow-lg overflow-hidden group">
                                    <img src="assets/img/'.$style['image'].'" class="absolute inset-0 w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80"></div>
                                    <span class="absolute bottom-4 left-4 text-white font-bold text-xl drop-shadow-md z-10">'.$d.'</span>
                                </div>';
                            }
                        }
                        ?>

                    </div>
            </div>
        </div>
        </div>

        <div class="relative px-6 lg:px-12 mt-8 z-20 pb-0">
            
            <div class="animate-fade-in-up mt-8" style="animation-delay: 0.5s;">
                <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-1">
                            Rumah Sakit Terdekat
                        </h2>
                        <p class="text-gray-500 text-sm">Rekomendasi rumah sakit terbaik di sekitar Anda berdasarkan rating.</p>
                    </div>
                    <a href="rs_daftar.php" class="text-gray-500 font-semibold text-sm hover:text-gray-900 transition flex items-center gap-1 group">
                        Lihat Semua <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    
                    <?php 
                    $query_rs = mysqli_query($conn, "SELECT * FROM data_rumah_sakit ORDER BY nama_rs ASC LIMIT 6");
                    while($d = mysqli_fetch_array($query_rs)) {
                    ?>
                    
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col h-full">
                        
                        <div class="h-48 w-full overflow-hidden rounded-t-2xl relative bg-gray-100 isolate">
                            
                            <img src="assets/img/<?= $d['foto'] ?>" 
                                alt="<?= $d['nama_rs'] ?>" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                style="transform: translateZ(0);">
                        </div>
                        
                        <div class="p-5 flex flex-col flex-1 bg-white rounded-b-2xl">
                            <div class="text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">
                                <?= htmlspecialchars($d['wilayah']) ?>
                            </div>
                            
                            <h3 class="font-bold text-lg text-gray-800 mb-2 group-hover:text-finders-blue transition line-clamp-1">
                                <?= htmlspecialchars($d['nama_rs']) ?>
                            </h3>
                            
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2 flex-1">
                                <?= htmlspecialchars($d['deskripsi']) ?>
                            </p>
                            
                            <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                                <a href="booking.php?rs_id=<?= $d['id_rs'] ?>" class="flex-1 bg-finders-green hover:bg-green-600 text-white text-xs font-bold py-2.5 rounded-xl text-center transition uppercase tracking-wide shadow-sm flex items-center justify-center gap-2">
                                    <i class="fa-regular fa-calendar-check"></i> Jadwalkan
                                </a>
                                
                                <button onclick="openDetail(<?= $d['id_rs'] ?>)" class="flex-1 bg-blue-50 text-finders-blue text-xs font-bold py-2.5 rounded-xl hover:bg-[#1e3a8a] hover:text-white transition uppercase tracking-wide cursor-pointer shadow-sm">
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="mt-20 animate-fade-in-up" style="animation-delay: 0.7s;">
                <div class="text-center mb-12">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Mengapa Memilih FindeRS?</h2>
                    <p class="text-gray-500 text-sm">Platform terpercaya untuk kebutuhan kesehatan Anda</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:shadow-lg transition group">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-[#1e3a8a] transition">
                            <i class="fa-solid fa-magnifying-glass-location text-2xl text-finders-blue group-hover:text-white transition"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Pencarian Mudah</h3>
                        <p class="text-gray-500 text-sm">Temukan rumah sakit dan dokter dengan cepat berdasarkan lokasi dan kebutuhan.</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:shadow-lg transition group">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-[#00D348] transition">
                            <i class="fa-solid fa-calendar-check text-2xl text-finders-green group-hover:text-white transition"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Booking Online</h3>
                        <p class="text-gray-500 text-sm">Reservasi jadwal dokter secara online tanpa perlu antri di rumah sakit.</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:shadow-lg transition group">
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-600 transition">
                            <i class="fa-solid fa-shield-heart text-2xl text-purple-600 group-hover:text-white transition"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Terpercaya</h3>
                        <p class="text-gray-500 text-sm">Data rumah sakit dan dokter terverifikasi dengan rating dari pengguna.</p>
                    </div>
                </div>
            </div>

            <div class="mt-16">
                <?php include 'includes/footer.php'; ?>
            </div>
        </div>
    </main>
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