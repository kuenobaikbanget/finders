<?php 
session_start();
include 'config/db_connect.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$sql = "SELECT l.*, rs.nama_rs, rs.foto as foto_rs, rs.wilayah, rs.alamat
        FROM data_layanan_rs l
        JOIN data_rumah_sakit rs ON l.id_rs = rs.id_rs
        WHERE 1=1";

if (!empty($keyword)) {

    $sql .= " AND (l.nama_layanan LIKE '%$keyword%' OR rs.nama_rs LIKE '%$keyword%')";
}

if (!empty($kategori_filter)) {
    $sql .= " AND l.kategori = '$kategori_filter'";
}

$sql .= " ORDER BY rs.nama_rs ASC";

$query = mysqli_query($conn, $sql);
$jumlah_data = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Layanan</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/style_user.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    <?php include 'includes/sidebar.php'; ?>

    <main class="flex-1 h-full overflow-y-auto relative scroll-smooth bg-gray-50">
        
        <div class="relative w-full h-[28rem] bg-[#1e3a8a] overflow-hidden">
            <img src="assets/img/search_background.jpg" class="absolute inset-0 w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-transparent to-transparent"></div>
            
            <div class="relative z-10 container mx-auto px-6 h-full flex flex-col justify-center items-center text-center">
                <h1 class="text-3xl font-bold text-white mb-12">Cari Layanan Medis</h1>
                
                <form action="layanan.php" method="GET" class="w-full max-w-2xl">
                    <div class="bg-white p-1.5 rounded-xl flex items-center shadow-lg">
                        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>"
                            placeholder="Cari layanan, dokter, atau rumah sakit..." 
                            class="w-full bg-transparent border-none focus:ring-0 text-gray-800 px-4 py-2 text-sm">
                        <button type="submit" class="bg-finders-blue hover:bg-finders-green text-white px-6 py-2 rounded-lg font-bold transition-all duration-300 transform hover:scale-105">
                            Cari
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-sm text-blue-100">
                    <?php if($keyword || $kategori_filter): ?>
                        Menampilkan <b><?= $jumlah_data ?></b> hasil untuk "<b><?= htmlspecialchars($keyword ?: $kategori_filter) ?></b>"
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-6 lg:px-12 py-8 pb-20">
            
            <?php if ($jumlah_data > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    
                    <?php while($d = mysqli_fetch_array($query)): ?>
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col h-full overflow-hidden">
                            
                            <div class="h-32 bg-gray-100 relative overflow-hidden">
                                <img src="assets/img/<?= $d['foto_rs'] ?>" 
                                     alt="<?= $d['nama_rs'] ?>" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                
                                <div class="absolute top-3 left-3">
                                    <span class="px-2 py-1 bg-white/90 backdrop-blur text-finders-blue text-[10px] font-bold uppercase rounded-md shadow-sm border border-blue-100">
                                        <?= htmlspecialchars($d['kategori']) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="p-5 flex flex-col flex-1">
                                <h3 class="font-bold text-lg text-gray-800 mb-1 leading-tight group-hover:text-finders-green transition">
                                    <?= htmlspecialchars($d['nama_layanan']) ?>
                                </h3>
                                
                                <div class="flex items-start gap-2 text-gray-500 text-xs mb-3">
                                    <i class="fa-solid fa-hospital mt-0.5 text-finders-green"></i>
                                    <span><?= htmlspecialchars($d['nama_rs']) ?></span>
                                </div>

                                <div class="border-t border-gray-100 my-2"></div>
                                
                                <div class="mt-auto space-y-2">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-400">Wilayah</span>
                                        <span class="font-medium text-gray-700"><?= htmlspecialchars($d['wilayah']) ?></span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-400">Status</span>
                                        <span class="text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">
                                            <?= htmlspecialchars($d['ketersediaan_layanan']) ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 mt-5">
                                    <button onclick="openDetail(<?= $d['id_rs'] ?>)" 
                                            class="px-3 py-2 border border-gray-200 text-gray-600 rounded-lg text-xs font-bold hover:border-finders-blue hover:text-finders-blue transition">
                                        Detail RS
                                    </button>
                                    
                                    <a href="booking.php?rs_id=<?= $d['id_rs'] ?>&layanan_id=<?= $d['id_layanan'] ?>" 
                                       class="px-3 py-2 bg-finders-green text-white rounded-lg text-xs font-bold text-center hover:bg-green-600 transition shadow-lg shadow-green-200">
                                        Buat Janji
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>

                </div>

            <?php else: ?>
                
                <div class="flex flex-col items-center justify-center py-20 text-center animate-fade-in-up">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-magnifying-glass text-4xl text-gray-300"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Layanan Tidak Ditemukan</h2>
                    <p class="text-gray-500 max-w-md mx-auto mb-8">
                        Maaf, kami tidak dapat menemukan layanan dengan kata kunci "<b><?= htmlspecialchars($keyword) ?></b>". Coba gunakan kata kunci lain.
                    </p>
                    <a href="index.php" class="px-6 py-3 bg-white border border-gray-300 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition">
                        Kembali ke Beranda
                    </a>
                </div>

            <?php endif; ?>

        </div>

        <?php include 'includes/footer.php'; ?>
    </main>

    <div id="modalOverlay" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
            <div id="modalContent" class="w-full flex justify-center"></div>
        </div>
    </div>
    
    <script src="assets/js/script.js"></script>

</body>
</html>