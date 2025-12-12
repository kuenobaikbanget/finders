<?php
session_start();
require_once '../config/db_connect.php';

// Cek Login Admin
if(!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// Ambil ID dari URL
$id_jadwal = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;

if(!$id_jadwal) {
    $_SESSION['error_message'] = "ID Penjadwalan tidak valid";
    header("Location: index.php");
    exit;
}

// Query data penjadwalan lengkap
$query = mysqli_query($conn, "
    SELECT p.*, 
           rs.nama_rs, rs.alamat as alamat_rs, rs.wilayah, rs.no_telpon as telpon_rs, rs.foto as foto_rs,
           l.nama_layanan, l.kategori,
           u.nama as nama_user, u.email as email_user, u.no_telpon as telpon_user, u.tanggal_daftar
    FROM data_penjadwalan p
    JOIN data_rumah_sakit rs ON p.id_rs = rs.id_rs
    JOIN data_layanan_rs l ON p.id_layanan = l.id_layanan
    JOIN akun_user u ON p.id_user = u.id_user
    WHERE p.id_penjadwalan = '$id_jadwal'
");

if(mysqli_num_rows($query) == 0) {
    $_SESSION['error_message'] = "Data penjadwalan tidak ditemukan";
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// Hitung total booking user ini
$query_total_booking = mysqli_query($conn, "
    SELECT COUNT(*) as total FROM data_penjadwalan WHERE id_user = '{$data['id_user']}'
");
$total_booking_user = mysqli_fetch_assoc($query_total_booking)['total'];

// Status styling
$status_config = [
    'Menunggu' => [
        'bg' => 'bg-yellow-50',
        'text' => 'text-yellow-700',
        'border' => 'border-yellow-200',
        'icon' => 'fa-clock',
        'label' => 'Menunggu Konfirmasi'
    ],
    'Dikonfirmasi' => [
        'bg' => 'bg-blue-50',
        'text' => 'text-blue-700',
        'border' => 'border-blue-200',
        'icon' => 'fa-calendar-check',
        'label' => 'Jadwal Dikonfirmasi'
    ],
    'Selesai' => [
        'bg' => 'bg-green-50',
        'text' => 'text-green-700',
        'border' => 'border-green-200',
        'icon' => 'fa-clipboard-check',
        'label' => 'Kunjungan Selesai'
    ],
    'Dibatalkan' => [
        'bg' => 'bg-red-50',
        'text' => 'text-red-700',
        'border' => 'border-red-200',
        'icon' => 'fa-ban',
        'label' => 'Dibatalkan'
    ]
];

$status_style = $status_config[$data['status']] ?? $status_config['Menunggu'];

$page_title = "Detail Penjadwalan #" . $data['id_penjadwalan'];
$page_subtitle = "Informasi lengkap penjadwalan kunjungan pasien";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penjadwalan #<?= $data['id_penjadwalan'] ?> - Admin FindeRS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">
    
    <?php include 'includes/sidebar_admin.php'; ?>

    <main class="flex-1 overflow-y-auto">
        <div class="p-6">
            
            <?php include 'includes/header_admin.php'; ?>

            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm mb-6">
                <a href="index.php" class="text-gray-500 hover:text-blue-600 transition">Dashboard</a>
                <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                <a href="jadwal_data.php" class="text-gray-500 hover:text-blue-600 transition">Data Penjadwalan</a>
                <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                <span class="text-gray-800 font-semibold">Detail #<?= $data['id_penjadwalan'] ?></span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Content - Left Side (2 columns) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Status Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                                    Penjadwalan #<?= $data['id_penjadwalan'] ?>
                                </h2>
                                <p class="text-sm text-gray-500">
                                    Dibuat pada: <?= date('d F Y, H:i', strtotime($data['dibuat_pada'])) ?> WIB
                                </p>
                            </div>
                            <div class="flex gap-3">
                                <span class="px-4 py-2 <?= $status_style['bg'] ?> <?= $status_style['text'] ?> border-2 <?= $status_style['border'] ?> rounded-xl font-bold text-sm flex items-center gap-2">
                                    <i class="fa-solid <?= $status_style['icon'] ?>"></i>
                                    <?= $status_style['label'] ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pasien -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-user text-blue-600"></i>
                            </div>
                            Informasi Pasien
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Nama Pasien</label>
                                    <p class="text-base font-semibold text-gray-800 mt-1"><?= htmlspecialchars($data['nama_pasien']) ?></p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">NIK</label>
                                    <p class="text-base font-medium text-gray-800 mt-1"><?= $data['no_nik'] ?: '-' ?></p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">No. Telepon</label>
                                    <p class="text-base font-medium text-gray-800 mt-1 flex items-center gap-2">
                                        <i class="fa-solid fa-phone text-green-600 text-sm"></i>
                                        <?= htmlspecialchars($data['no_telpon']) ?>
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Tanggal Kunjungan</label>
                                    <p class="text-base font-medium text-gray-800 mt-1 flex items-center gap-2">
                                        <i class="fa-solid fa-calendar text-blue-600 text-sm"></i>
                                        <?= date('d F Y', strtotime($data['tanggal_kunjungan'])) ?>
                                    </p>
                                </div>
                            </div>

                            <?php if($data['catatan']): ?>
                            <div class="pt-4 border-t border-gray-200">
                                <label class="text-xs font-semibold text-gray-500 uppercase">Catatan Pasien</label>
                                <div class="mt-2 bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <p class="text-sm text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($data['catatan'])) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Informasi Rumah Sakit & Layanan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-hospital text-green-600"></i>
                            </div>
                            Rumah Sakit & Layanan
                        </h3>
                        
                        <div class="flex gap-4 mb-6">
                            <img src="../assets/img/<?= htmlspecialchars($data['foto_rs']) ?>" 
                                 alt="<?= htmlspecialchars($data['nama_rs']) ?>"
                                 class="w-24 h-24 rounded-xl object-cover border border-gray-200"
                                 onerror="this.src='../assets/img/default_rs.jpg'">
                            <div class="flex-1">
                                <h4 class="text-xl font-bold text-gray-800 mb-1"><?= htmlspecialchars($data['nama_rs']) ?></h4>
                                <p class="text-sm text-gray-600 mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-location-dot text-red-500"></i>
                                    <?= htmlspecialchars($data['wilayah']) ?>
                                </p>
                                <p class="text-sm text-gray-600"><?= htmlspecialchars($data['alamat_rs']) ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Layanan</label>
                                <p class="text-base font-semibold text-gray-800 mt-1"><?= htmlspecialchars($data['nama_layanan']) ?></p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Kategori</label>
                                <p class="text-sm mt-1">
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full font-semibold">
                                        <?= htmlspecialchars($data['kategori']) ?>
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Kontak RS</label>
                                <p class="text-base font-medium text-gray-800 mt-1 flex items-center gap-2">
                                    <i class="fa-solid fa-phone text-green-600 text-sm"></i>
                                    <?= htmlspecialchars($data['telpon_rs']) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Antrian (Jika Dikonfirmasi) -->
                    <?php if($data['status'] == 'Dikonfirmasi' || $data['status'] == 'Selesai'): ?>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl border-2 border-blue-200 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-ticket text-white"></i>
                            </div>
                            Informasi Antrian
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white rounded-xl p-4 border border-blue-200">
                                <label class="text-xs font-semibold text-gray-500 uppercase">Nomor Antrian</label>
                                <p class="text-3xl font-bold text-blue-600 mt-2"><?= $data['queue_number'] ?: '-' ?></p>
                            </div>
                            <div class="bg-white rounded-xl p-4 border border-blue-200">
                                <label class="text-xs font-semibold text-gray-500 uppercase">Estimasi Waktu</label>
                                <p class="text-xl font-bold text-gray-800 mt-2"><?= $data['estimasi_jam'] ?: '-' ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>

                <!-- Sidebar - Right Side (1 column) -->
                <div class="space-y-6">
                    
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                        <div class="space-y-3">
                            <button onclick="openEditModal(<?= $data['id_penjadwalan'] ?>)" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Update Status
                            </button>
                            
                            <button onclick="printTicket()" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-print"></i>
                                Cetak Tiket
                            </button>
                            
                            <?php if($data['status'] == 'Menunggu'): ?>
                            <button onclick="cancelJadwal(<?= $data['id_penjadwalan'] ?>)" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-xl transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-ban"></i>
                                Batalkan Jadwal
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Informasi User Pendaftar -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-user-circle text-gray-600"></i>
                            User Pendaftar
                        </h3>
                        
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                <?= strtoupper(substr($data['nama_user'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($data['nama_user']) ?></p>
                                <p class="text-xs text-gray-500">ID User: #<?= $data['id_user'] ?></p>
                            </div>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fa-solid fa-envelope w-4"></i>
                                <span class="break-all"><?= htmlspecialchars($data['email_user']) ?></span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fa-solid fa-phone w-4"></i>
                                <span><?= htmlspecialchars($data['telpon_user']) ?></span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fa-solid fa-calendar-plus w-4"></i>
                                <span>Bergabung: <?= date('d M Y', strtotime($data['tanggal_daftar'])) ?></span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fa-solid fa-clipboard-check w-4"></i>
                                <span>Total Booking: <strong><?= $total_booking_user ?></strong></span>
                            </div>
                        </div>

                        <a href="user_bookings.php?id=<?= $data['id_user'] ?>" 
                           class="mt-4 block text-center text-blue-600 hover:text-blue-700 font-semibold text-sm">
                            Lihat Semua Booking User →
                        </a>
                    </div>

                    <!-- Timeline Status -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Timeline</h3>
                        
                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </div>
                                    <?php if($data['status'] != 'Menunggu'): ?>
                                    <div class="w-0.5 h-full bg-gray-300 mt-1"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 pb-4">
                                    <p class="font-semibold text-gray-800">Booking Dibuat</p>
                                    <p class="text-xs text-gray-500"><?= date('d M Y, H:i', strtotime($data['dibuat_pada'])) ?></p>
                                </div>
                            </div>

                            <?php if($data['status'] != 'Menunggu'): ?>
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 <?= $data['status'] == 'Dibatalkan' ? 'bg-red-500' : 'bg-blue-500' ?> rounded-full flex items-center justify-center text-white">
                                        <i class="fa-solid <?= $data['status'] == 'Dibatalkan' ? 'fa-times' : 'fa-check' ?> text-xs"></i>
                                    </div>
                                    <?php if($data['status'] == 'Selesai'): ?>
                                    <div class="w-0.5 h-full bg-gray-300 mt-1"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 pb-4">
                                    <p class="font-semibold text-gray-800"><?= $status_style['label'] ?></p>
                                    <p class="text-xs text-gray-500">
                                        <?= $data['diperbarui_pada'] ? date('d M Y, H:i', strtotime($data['diperbarui_pada'])) : 'Belum diupdate' ?>
                                    </p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if($data['status'] == 'Selesai'): ?>
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white">
                                        <i class="fa-solid fa-flag-checkered text-xs"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">Kunjungan Selesai</p>
                                    <p class="text-xs text-gray-500">Kunjungan telah diselesaikan</p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <!-- Modal Overlay -->
    <div id="modalOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[999] hidden">
        <div id="modalContent" class="bg-white w-[90%] max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-xl relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gray-600 bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <div id="modalBody" class="p-6">
                Memuat...
            </div>
        </div>
    </div>

    <script>
    function openEditModal(id) {
        document.getElementById("modalOverlay").classList.remove("hidden");
        let target = document.getElementById("modalBody");
        target.innerHTML = '<div class="flex items-center justify-center py-12"><i class="fa-solid fa-spinner fa-spin text-3xl text-blue-600"></i></div>';
        
        fetch('jadwal_form.php?id=' + id)
            .then(response => response.text())
            .then(data => {
                target.innerHTML = data;
            })
            .catch(err => {
                target.innerHTML = '<div class="text-red-500 text-center py-8">Gagal memuat data.</div>';
            });
    }

    function closeModal() {
        document.getElementById("modalOverlay").classList.add("hidden");
    }

    function printTicket() {
        window.print();
    }

    function cancelJadwal(id) {
        if(confirm('Yakin ingin membatalkan jadwal ini?\n\nPasien akan menerima notifikasi pembatalan.')) {
            // Implementasi pembatalan
            fetch('jadwal_form.php?id=' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'status=Dibatalkan&queue_number=&estimasi_jam=&catatan_admin=Jadwal dibatalkan oleh admin'
            })
            .then(response => response.text())
            .then(data => {
                alert('Jadwal berhasil dibatalkan');
                location.reload();
            })
            .catch(err => {
                alert('Gagal membatalkan jadwal');
            });
        }
    }

    // Close modal when clicking outside
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if(e.target === this) {
            closeModal();
        }
    });
    </script>

</body>
</html>