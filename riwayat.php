<?php 
session_start();
include 'config/db_connect.php';

// Cek Login (Halaman ini wajib login)
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// QUERY DATA RIWAYAT
$query = mysqli_query($conn, "SELECT p.*, rs.nama_rs, rs.foto, l.nama_layanan 
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
    <title>Riwayat Kunjungan - FindeRS</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/style_user.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    <?php include 'includes/sidebar.php'; ?>

    <main class="flex-1 h-full overflow-y-auto relative scroll-smooth bg-gray-50">
        
        <!-- Header Section dengan Background -->
        <div class="relative w-full h-64 bg-gradient-to-br from-[#1e3a8a] to-[#1e40af] overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAxOGMtNC40MTggMC04IDMuNTgyLTggOHMzLjU4MiA4IDggOCA4LTMuNTgyIDgtOC0zLjU4Mi04LTgtOHoiIHN0cm9rZT0iI2ZmZiIgc3Ryb2tlLXdpZHRoPSIyIi8+PC9nPjwvc3ZnPg==')] opacity-50"></div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-transparent to-transparent"></div>
            
            <div class="relative z-10 container mx-auto px-6 lg:px-12 h-full flex flex-col justify-center">
                <div class="animate-fade-in-down">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-clock-rotate-left text-2xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-bold text-white">Riwayat Kunjungan</h1>
                            <p class="text-blue-100 mt-1">Pantau status janji temu dan riwayat medis Anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="container mx-auto px-6 lg:px-12 -mt-8 pb-20">

        <!-- Content Section -->
        <div class="container mx-auto px-6 lg:px-12 -mt-8 pb-20">
            
            <div class="space-y-5 animate-fade-in-up max-w-5xl mx-auto">

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

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition-all overflow-hidden flex flex-col md:flex-row group">
                            
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
                                <button onclick='openDetailKunjungan(<?= json_encode($row) ?>)' class="w-full md:w-auto px-4 py-2 bg-white border-2 border-gray-200 text-gray-600 text-xs font-bold rounded-xl hover:border-finders-blue hover:text-finders-blue transition shadow-sm">
                                    <i class="fa-solid fa-info-circle"></i> Detail Kunjungan
                                </button>
                                
                                <?php if($row['status'] == 'Dikonfirmasi'): ?>
                                    <a href="#" class="w-full md:w-auto px-4 py-2 bg-finders-green hover:bg-green-600 text-white text-xs font-bold rounded-xl transition text-center shadow-green-200 shadow-lg flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-print"></i> E-Ticket
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

    <!-- Floating Window Detail Kunjungan -->
    <div id="detailModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeDetailModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full animate-fade-in-up" onclick="event.stopPropagation()">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 rounded-t-3xl">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                                <i class="fa-solid fa-clipboard-list"></i>
                                Detail Kunjungan
                            </h3>
                            <p class="text-blue-100 text-sm mt-1">Informasi lengkap janji temu Anda</p>
                        </div>
                        <button onclick="closeDetailModal()" class="text-white/80 hover:text-white transition">
                            <i class="fa-solid fa-times text-2xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    
                    <!-- Status Badge -->
                    <div class="flex justify-center">
                        <span id="modalStatus" class="px-6 py-2 rounded-full text-sm font-bold border flex items-center gap-2">
                            <i id="modalStatusIcon" class="fa-solid"></i>
                            <span id="modalStatusText"></span>
                        </span>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <!-- Rumah Sakit -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 p-4 rounded-xl border border-blue-200">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-hospital text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-blue-600 font-bold uppercase mb-1">Rumah Sakit</p>
                                    <p id="modalRsName" class="font-bold text-gray-800 text-sm"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Layanan -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100/50 p-4 rounded-xl border border-green-200">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-stethoscope text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-green-600 font-bold uppercase mb-1">Layanan</p>
                                    <p id="modalLayanan" class="font-bold text-gray-800 text-sm"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Pasien -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 p-4 rounded-xl border border-purple-200">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-user text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-purple-600 font-bold uppercase mb-1">Nama Pasien</p>
                                    <p id="modalPasien" class="font-bold text-gray-800 text-sm"></p>
                                </div>
                            </div>
                        </div>

                        <!-- NIK -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100/50 p-4 rounded-xl border border-orange-200">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-id-card text-white"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-orange-600 font-bold uppercase mb-1">NIK</p>
                                    <p id="modalNik" class="font-bold text-gray-800 text-sm"></p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Tanggal Kunjungan -->
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100/50 p-5 rounded-xl border border-indigo-200">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-indigo-500 rounded-xl flex flex-col items-center justify-center text-white">
                                <span id="modalTanggal" class="text-2xl font-bold leading-none"></span>
                                <span id="modalBulan" class="text-xs font-medium"></span>
                            </div>
                            <div>
                                <p class="text-xs text-indigo-600 font-bold uppercase mb-1">Tanggal Kunjungan</p>
                                <p id="modalTanggalLengkap" class="font-bold text-gray-800"></p>
                                <p id="modalHari" class="text-sm text-gray-600"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Queue Number & Estimasi (Jika dikonfirmasi) -->
                    <div id="modalQueueSection" class="hidden">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 p-4 rounded-xl border-2 border-blue-300 text-center">
                                <p class="text-xs text-blue-600 font-bold uppercase mb-2">No. Antrean</p>
                                <p id="modalQueue" class="text-3xl font-bold text-blue-600"></p>
                            </div>
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 p-4 rounded-xl border border-gray-300 text-center">
                                <p class="text-xs text-gray-600 font-bold uppercase mb-2">Estimasi Waktu</p>
                                <p id="modalEstimasi" class="text-lg font-bold text-gray-700"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <p class="text-xs text-gray-600 font-bold uppercase mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-note-sticky"></i> Catatan
                        </p>
                        <p id="modalCatatan" class="text-sm text-gray-700"></p>
                    </div>

                    <!-- Dibuat Pada -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500">
                            Dibuat pada: <span id="modalDibuatPada" class="font-semibold text-gray-700"></span>
                        </p>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 p-4 rounded-b-3xl border-t border-gray-200">
                    <button onclick="closeDetailModal()" class="w-full px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    
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
            document.getElementById('modalNik').textContent = data.no_nik || '-';
            
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
            } else {
                document.getElementById('modalQueueSection').classList.add('hidden');
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
    </script>

</body>
</html>