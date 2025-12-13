<?php 
session_start();
include 'config/db_connect.php';

// Cek Login (Halaman ini wajib login)
if(!isset($_SESSION['user_id'])) {
    // Simpan URL tujuan untuk redirect setelah login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// QUERY KATEGORI LAYANAN YANG UNIK
$query_kategori = mysqli_query($conn, "SELECT DISTINCT kategori 
                                       FROM data_layanan_rs 
                                       WHERE kategori IS NOT NULL 
                                       ORDER BY kategori");

// QUERY DATA RIWAYAT
$query = mysqli_query($conn, "SELECT p.*, rs.nama_rs, rs.foto, l.nama_layanan, l.kategori 
                              FROM data_penjadwalan p
                              JOIN data_rumah_sakit rs ON p.id_rs = rs.id_rs
                              JOIN data_layanan_rs l ON p.id_layanan = l.id_layanan
                              WHERE p.id_user = '$id_user'
                              ORDER BY p.dibuat_pada DESC");

// Debug - hapus setelah selesai testing
if(!$query) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Kunjungan</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/style_user.css">
    
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    <?php include 'includes/sidebar.php'; ?>

    <main class="flex-1 h-full overflow-y-auto relative scroll-smooth bg-gray-50">
        
        <!-- Header Section dengan Background Image dan Gradient Transition -->
        <div class="relative w-full bg-gradient-to-br from-[#1e3a8a] to-[#1e40af] overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img src="assets/img/riwayat_background.jpg" alt="Background" class="w-full h-full object-cover">
            </div>
            
            <!-- Dark Overlay untuk Meningkatkan Kontras Teks -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#1e3a8a]/60 to-[#1e40af]/60"></div>
            
            <!-- Gradient Overlay untuk Transisi Smooth ke Body -->
            <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-b from-transparent to-gray-50"></div>
            
            <div class="relative z-10 container mx-auto px-6 lg:px-12 py-16 pb-24">
                <!-- Breadcrumb Navigation -->
                <nav class="flex items-center gap-2 text-sm mb-8">
                    <a href="index.php" class="flex items-center gap-2 text-white/80 hover:text-white transition-colors">
                        <i class="fa-solid fa-home text-lg"></i>
                        <span class="font-medium">Beranda</span>
                    </a>
                    <i class="fa-solid fa-chevron-right text-white/60 text-xs"></i>
                    <span class="text-white font-semibold">Riwayat Pengajuan</span>
                </nav>

                <!-- Header Content -->
                <div>
                    <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6">Riwayat Pengajuan</h1>
                    <p class="text-white/90 text-lg lg:text-xl max-w-3xl leading-relaxed">
                        Detail lengkap pengajuan kunjungan rumah sakit Anda dapat ditemukan di sini. Silakan tinjau status dan informasi terkait setiap pengajuan yang telah Anda buat.
                    </p>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="container mx-auto px-6 lg:px-12 py-8 pb-20">
            
            <!-- Filter dan Search Bar -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar riwayat pengajuan</h2>
                
                <!-- Filter Kategori -->
                <div class="flex flex-wrap gap-3 mb-4">
                    <button onclick="filterByCategory('Semua')" class="filter-btn px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition shadow-md" data-category="Semua">
                        Semua
                    </button>
                    <?php 
                    // Tampilkan tombol kategori secara dinamis dari database
                    while($kategori = mysqli_fetch_array($query_kategori)): 
                    ?>
                        <button onclick="filterByCategory('<?= htmlspecialchars($kategori['kategori']) ?>')" 
                                class="filter-btn px-5 py-2 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-100 transition border border-gray-300" 
                                data-category="<?= htmlspecialchars($kategori['kategori']) ?>">
                            <?= htmlspecialchars($kategori['kategori']) ?>
                        </button>
                    <?php endwhile; ?>
                </div>

                <!-- Search Bar -->
                <div class="flex gap-3">
                    <input type="text" id="searchInput" placeholder="Cari Riwayat Pengajuan" onkeyup="searchRiwayat()"
                        class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none text-gray-700 placeholder-gray-400">
                    <button onclick="searchRiwayat()" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition shadow-md">
                        Cari
                    </button>
                </div>
            </div>

            <div class="space-y-5">

                <?php if(mysqli_num_rows($query) > 0): ?>
                    <?php while($row = mysqli_fetch_array($query)): ?>
                        
                        <?php 
                        // Default (Menunggu) - Kuning
                        $status_badge = "bg-yellow-50 text-yellow-700 border-yellow-200";
                        $border_left = "bg-yellow-500";
                        $icon = "fa-clock";
                        $text_status = "Menunggu Konfirmasi";

                        if($row['status'] == 'Dikonfirmasi') {
                            $status_badge = "bg-blue-50 text-finders-blue border-blue-200";
                            $border_left = "bg-finders-blue";
                            $icon = "fa-calendar-check";
                            $text_status = "Jadwal Dikonfirmasi";
                        } elseif($row['status'] == 'Selesai') {
                            $status_badge = "bg-green-50 text-finders-green border-green-200";
                            $border_left = "bg-finders-green";
                            $icon = "fa-clipboard-check";
                            $text_status = "Kunjungan Selesai";
                        } elseif($row['status'] == 'Dibatalkan') {
                            $status_badge = "bg-red-50 text-red-700 border-red-200";
                            $border_left = "bg-red-500";
                            $icon = "fa-ban";
                            $text_status = "Dibatalkan";
                        }
                        
                        // Format Tanggal Cantik (Contoh: 12 Jan 2025)
                        $tgl = date('d M Y', strtotime($row['tanggal_kunjungan']));
                        $hari = date('l', strtotime($row['tanggal_kunjungan'])); // Nama hari inggris
                        ?>

                        <div onclick='openDetailKunjungan(<?= json_encode($row) ?>)' 
                             class="riwayat-card bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition-all overflow-hidden flex flex-col md:flex-row group cursor-pointer"
                             data-category="<?= htmlspecialchars($row['kategori']) ?>"
                             data-nama-rs="<?= strtolower(htmlspecialchars($row['nama_rs'])) ?>"
                             data-nama-layanan="<?= strtolower(htmlspecialchars($row['nama_layanan'])) ?>"
                             data-nama-pasien="<?= strtolower(htmlspecialchars($row['nama_pasien'])) ?>">
                            
                            <div class="w-full md:w-1.5 h-2 md:h-auto <?= $border_left ?>"></div>

                            <div class="p-5 md:pr-0 flex flex-row md:flex-col items-center justify-between md:justify-center gap-2 md:w-32 bg-gradient-to-br from-gray-50 to-gray-100/50 border-b md:border-b-0 md:border-r border-gray-100">
                                <div class="text-center">
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1"><?= substr($hari, 0, 3) ?></span>
                                    <span class="block text-3xl font-bold text-gray-800 leading-none"><?= date('d', strtotime($row['tanggal_kunjungan'])) ?></span>
                                    <span class="block text-xs font-medium text-gray-500 mt-1"><?= date('M Y', strtotime($row['tanggal_kunjungan'])) ?></span>
                                </div>
                                <div class="md:hidden">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold border <?= $status_badge ?> flex items-center gap-1.5">
                                        <i class="fa-solid <?= $icon ?>"></i> <?= $row['status'] ?>
                                    </span>
                                </div>
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-center">
                                <div class="hidden md:flex mb-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border <?= $status_badge ?> flex items-center gap-1.5 w-fit uppercase tracking-wide">
                                        <i class="fa-solid <?= $icon ?>"></i> <?= $text_status ?>
                                    </span>
                                </div>

                                <h3 class="font-bold text-lg text-gray-800 group-hover:text-finders-green transition">
                                    <?= htmlspecialchars($row['nama_rs']) ?>
                                </h3>
                                
                                <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                                    <i class="fa-solid fa-stethoscope text-finders-green"></i>
                                    Layanan: <span class="font-medium text-gray-700"><?= htmlspecialchars($row['nama_layanan']) ?></span>
                                </p>

                                <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                                    <i class="fa-solid fa-user text-gray-400"></i>
                                    Pasien: <span class="text-gray-600"><?= htmlspecialchars($row['nama_pasien']) ?></span>
                                </p>

                                <?php if($row['status'] == 'Dikonfirmasi' || $row['status'] == 'Selesai'): ?>
                                    <div class="mt-3 flex items-center gap-4">
                                        <div class="bg-blue-50 px-3 py-2 rounded-lg border border-blue-100">
                                            <p class="text-[10px] text-blue-400 uppercase font-bold">No. Antrean</p>
                                            <p class="text-lg font-bold text-finders-blue leading-none">
                                                <?= $row['queue_number'] ?? '-' ?>
                                            </p>
                                        </div>
                                        <div class="bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                                            <p class="text-[10px] text-gray-400 uppercase font-bold">Estimasi</p>
                                            <p class="text-sm font-bold text-gray-700">
                                                <?= $row['estimasi_jam'] ?? '08:00 - 10:00' ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="p-5 flex flex-col justify-center gap-2 border-l border-gray-100 bg-gray-50/30">
                                <button onclick='event.stopPropagation(); openDetailKunjungan(<?= json_encode($row) ?>)' class="w-full md:w-auto px-4 py-2 bg-white border-2 border-gray-200 text-gray-600 text-xs font-bold rounded-xl hover:border-finders-blue hover:text-finders-blue transition shadow-sm flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-info-circle"></i>
                                    <span>Detail Kunjungan</span>
                                </button>

                                <?php if($row['status'] == 'Dikonfirmasi'): ?>
                                    <a href="#" onclick="event.stopPropagation()" class="w-full md:w-auto px-4 py-2 bg-finders-green hover:bg-green-600 text-white text-xs font-bold rounded-xl transition text-center shadow-green-200 shadow-lg flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-print"></i> Cetak Tiket
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                        <?php endwhile; ?>

                <?php else: ?>
                    
                    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200 shadow-sm">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6 border-4 border-gray-100">
                            <i class="fa-regular fa-calendar-xmark text-5xl text-gray-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Riwayat</h3>
                        <p class="text-gray-500 text-sm mb-6 max-w-xs text-center leading-relaxed">
                            Anda belum pernah melakukan pendaftaran kunjungan rumah sakit.
                        </p>
                        <a href="index.php" class="px-6 py-3 bg-finders-green text-white font-bold rounded-xl shadow-lg shadow-green-500/20 hover:bg-green-600 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Buat Janji Baru
                        </a>
                    </div>

                <?php endif; ?>

            </div>
        </div>

    </main>

    <?php include 'detail_riwayat_kunjungan.php'; ?>
    
    <script src="assets/js/script.js"></script>
    <script>
        // Fungsi untuk membuka modal detail kunjungan
        function openDetailKunjungan(data) {
            // Set status badge
            let statusBadge = "bg-yellow-50 text-yellow-700 border-yellow-200";
            let statusIcon = "fa-clock";
            let statusText = "Menunggu Konfirmasi";

            if(data.status == 'Dikonfirmasi') {
                statusBadge = "bg-blue-50 text-blue-600 border-blue-200";
                statusIcon = "fa-calendar-check";
                statusText = "Jadwal Dikonfirmasi";
            } else if(data.status == 'Selesai') {
                statusBadge = "bg-green-50 text-green-600 border-green-200";
                statusIcon = "fa-clipboard-check";
                statusText = "Kunjungan Selesai";
            } else if(data.status == 'Dibatalkan') {
                statusBadge = "bg-red-50 text-red-600 border-red-200";
                statusIcon = "fa-ban";
                statusText = "Dibatalkan";
            }

            document.getElementById('modalStatus').className = "px-6 py-2 rounded-full text-sm font-bold border flex items-center gap-2 " + statusBadge;
            document.getElementById('modalStatusIcon').className = "fa-solid " + statusIcon;
            document.getElementById('modalStatusText').textContent = statusText;

            // Set data
            document.getElementById('modalRsName').textContent = data.nama_rs;
            document.getElementById('modalLayanan').textContent = data.nama_layanan;
            document.getElementById('modalPasien').textContent = data.nama_pasien;
            
            // Format tanggal
            const tanggal = new Date(data.tanggal_kunjungan);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const bulan = tanggal.toLocaleDateString('id-ID', { month: 'short' });
            const hari = tanggal.toLocaleDateString('id-ID', { weekday: 'long' });
            
            document.getElementById('modalTanggal').textContent = tanggal.getDate();
            document.getElementById('modalBulan').textContent = bulan;
            document.getElementById('modalTanggalLengkap').textContent = tanggal.toLocaleDateString('id-ID', options);
            document.getElementById('modalHari').textContent = hari;

            // Queue dan Estimasi (hanya tampil jika dikonfirmasi)
            if(data.status == 'Dikonfirmasi' || data.status == 'Selesai') {
                document.getElementById('modalQueueSection').classList.remove('hidden');
                document.getElementById('modalQueue').textContent = data.queue_number || '-';
                document.getElementById('modalEstimasi').textContent = data.estimasi_jam || '08:00 - 10:00';
                
                // Tampilkan QR Code Section
                document.getElementById('modalQRSection').classList.remove('hidden');
            } else {
                document.getElementById('modalQueueSection').classList.add('hidden');
                document.getElementById('modalQRSection').classList.add('hidden');
            }

            // Catatan
            document.getElementById('modalCatatan').textContent = data.catatan || 'Tidak ada catatan';

            // Dibuat pada
            const dibuatPada = new Date(data.dibuat_pada);
            document.getElementById('modalDibuatPada').textContent = dibuatPada.toLocaleDateString('id-ID', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            // Show modal
            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup modal
        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal dengan ESC key
        document.addEventListener('keydown', function(e) {
            if(e.key === 'Escape') {
                closeDetailModal();
            }
        });

        // Fungsi untuk share ticket
        function shareTicket() {
            if (navigator.share) {
                navigator.share({
                    title: 'E-Ticket Kunjungan RS',
                    text: 'Tiket kunjungan rumah sakit saya',
                }).catch(err => console.log('Error sharing:', err));
            } else {
                alert('Fitur share tidak didukung di browser ini');
            }
        }

        // Fungsi untuk save ticket
        function saveTicket() {
            alert('Fitur simpan tiket akan segera tersedia');
        }

        // Filter by Category
        function filterByCategory(category) {
            const cards = document.querySelectorAll('.riwayat-card');
            const buttons = document.querySelectorAll('.filter-btn');
            
            // Update button styles
            buttons.forEach(btn => {
                if(btn.dataset.category === category) {
                    btn.className = 'filter-btn px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition shadow-md';
                } else {
                    btn.className = 'filter-btn px-5 py-2 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-100 transition border border-gray-300';
                }
            });
            
            // Filter cards
            cards.forEach(card => {
                if(category === 'Semua') {
                    card.style.display = 'flex';
                } else {
                    if(card.dataset.category === category) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        }

        // Search Function
        function searchRiwayat() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.riwayat-card');
            
            cards.forEach(card => {
                const namaRs = card.dataset.namaRs;
                const namaLayanan = card.dataset.namaLayanan;
                const namaPasien = card.dataset.namaPasien;
                
                if(namaRs.includes(searchValue) || namaLayanan.includes(searchValue) || namaPasien.includes(searchValue)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

</body>
</html>