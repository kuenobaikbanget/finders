<?php
session_start();
require_once '../config/db_connect.php';

// Cek Login Admin
if(!isset($_SESSION['admin_id'])) {
    // Simpan URL tujuan untuk redirect setelah login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: ../login.php");
    exit;
}

// Query statistik
$query_total_penjadwalan = mysqli_query($conn, "SELECT COUNT(*) as total FROM data_penjadwalan");
$total_penjadwalan = mysqli_fetch_assoc($query_total_penjadwalan)['total'];

$query_total_rs = mysqli_query($conn, "SELECT COUNT(*) as total FROM data_rumah_sakit");
$total_rs = mysqli_fetch_assoc($query_total_rs)['total'];

$query_total_layanan = mysqli_query($conn, "SELECT COUNT(*) as total FROM data_layanan_rs");
$total_layanan = mysqli_fetch_assoc($query_total_layanan)['total'];

$query_total_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM akun_user");
$total_users = mysqli_fetch_assoc($query_total_users)['total'];

// Query penjadwalan terbaru
$query_penjadwalan = mysqli_query($conn, "
    SELECT p.*, rs.nama_rs, l.nama_layanan, u.nama as nama_user
    FROM data_penjadwalan p
    JOIN data_rumah_sakit rs ON p.id_rs = rs.id_rs
    JOIN data_layanan_rs l ON p.id_layanan = l.id_layanan
    JOIN akun_user u ON p.id_user = u.id_user
    ORDER BY p.dibuat_pada DESC
    LIMIT 10
");

$page_title = "Dashboard";
$page_subtitle = "Ringkasan sistem dan aktivitas terkini";
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
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { 
            animation: fadeInUp .6s ease-out forwards; 
            opacity: 0; 
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">
    
    <!-- SIDEBAR -->
    <?php include 'includes/sidebar_admin.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto">
        <div class="p-6">
            
            <!-- HEADER -->
            <?php include 'includes/header_admin.php'; ?>

            <!-- STATISTICS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Card: Total Penjadwalan -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition animate-fade-in-up">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-calendar-check text-green-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                            Aktif
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm mb-1">Total Penjadwalan</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_penjadwalan ?></p>
                    <a href="jadwal_data.php" class="text-xs text-blue-600 hover:text-blue-700 mt-2 inline-block">
                        Lihat detail →
                    </a>
                </div>

                <!-- Card: Data Rumah Sakit -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition animate-fade-in-up delay-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-hospital text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm mb-1">Data Rumah Sakit</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_rs ?></p>
                    <a href="rs_data.php" class="text-xs text-blue-600 hover:text-blue-700 mt-2 inline-block">
                        Lihat detail →
                    </a>
                </div>

                <!-- Card: Data Layanan -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition animate-fade-in-up delay-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-stethoscope text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm mb-1">Data Layanan</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_layanan ?></p>
                    <a href="layanan_data.php" class="text-xs text-blue-600 hover:text-blue-700 mt-2 inline-block">
                        Lihat detail →
                    </a>
                </div>

                <!-- Card: Akun User -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition animate-fade-in-up delay-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-users text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm mb-1">Akun User</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_users ?></p>
                    <a href="users_data.php" class="text-xs text-blue-600 hover:text-blue-700 mt-2 inline-block">
                        Lihat detail →
                    </a>
                </div>

            </div>

            <!-- TABEL PENJADWALAN TERBARU -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 animate-fade-in-up">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Penjadwalan Kunjungan Terbaru</h2>
                            <p class="text-sm text-gray-500 mt-1">10 pendaftaran terakhir</p>
                        </div>
                        <a href="jadwal_data.php" 
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium transition flex items-center gap-2">
                            <i class="fa-solid fa-list"></i>
                            Lihat Semua
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Pasien</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rumah Sakit</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Layanan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if(mysqli_num_rows($query_penjadwalan) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($query_penjadwalan)): ?>
                                    <?php
                                    $status_class = 'bg-yellow-100 text-yellow-700';
                                    if($row['status'] == 'Dikonfirmasi') $status_class = 'bg-blue-100 text-blue-700';
                                    elseif($row['status'] == 'Selesai') $status_class = 'bg-green-100 text-green-700';
                                    elseif($row['status'] == 'Dibatalkan') $status_class = 'bg-red-100 text-red-700';
                                    ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800">#<?= $row['id_penjadwalan'] ?></td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-800"><?= htmlspecialchars($row['nama_pasien']) ?></div>
                                            <div class="text-xs text-gray-500">User: <?= htmlspecialchars($row['nama_user']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['nama_rs']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['nama_layanan']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= date('d M Y', strtotime($row['tanggal_kunjungan'])) ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $status_class ?>">
                                                <?= $row['status'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="jadwal_detail.php?id=<?= $row['id_penjadwalan'] ?>" 
                                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500">Belum ada data penjadwalan</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <?php include 'includes/footer_admin.php'; ?>
    </main>

</body>
</html>
