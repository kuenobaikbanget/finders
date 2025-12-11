<?php
session_start();
require_once '../config/db_connect.php';

// Cek Login Admin
if(!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// Handle Delete
if(isset($_GET['delete'])) {
    $id_user = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // Cek apakah user memiliki penjadwalan
    $check_jadwal = mysqli_query($conn, "SELECT COUNT(*) as total FROM data_penjadwalan WHERE id_user = '$id_user'");
    $jadwal_count = mysqli_fetch_assoc($check_jadwal)['total'];
    
    if($jadwal_count > 0) {
        $_SESSION['error_message'] = "Tidak dapat menghapus user karena memiliki $jadwal_count penjadwalan aktif";
    } else {
        $query_delete = mysqli_query($conn, "DELETE FROM akun_user WHERE id_user = '$id_user'");
        
        if($query_delete) {
            $_SESSION['success_message'] = "Data user berhasil dihapus";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus data: " . mysqli_error($conn);
        }
    }
    header("Location: users_data.php");
    exit;
}

// Filter dan Search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'terbaru';

// Query dengan filter
$query_sql = "SELECT u.*, COUNT(p.id_penjadwalan) as total_booking
              FROM akun_user u
              LEFT JOIN data_penjadwalan p ON u.id_user = p.id_user
              WHERE 1=1";

if($search) {
    $query_sql .= " AND (u.nama LIKE '%$search%' OR u.email LIKE '%$search%' OR u.no_telpon LIKE '%$search%')";
}

$query_sql .= " GROUP BY u.id_user";

// Sorting
switch($sort) {
    case 'terlama':
        $query_sql .= " ORDER BY u.tanggal_daftar ASC";
        break;
    case 'nama':
        $query_sql .= " ORDER BY u.nama ASC";
        break;
    case 'booking':
        $query_sql .= " ORDER BY total_booking DESC";
        break;
    default:
        $query_sql .= " ORDER BY u.tanggal_daftar DESC";
}

$result = mysqli_query($conn, $query_sql);

// Statistics
$total_users = mysqli_num_rows($result);
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM data_penjadwalan"))['total'];

$page_title = "Data User";
$page_subtitle = "Kelola akun pengguna terdaftar";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User - Admin FindeRS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">
    
    <?php include 'includes/sidebar_admin.php'; ?>

    <main class="flex-1 overflow-y-auto">
        <div class="p-6">
            
            <?php include 'includes/header_admin.php'; ?>

            <!-- Alert Messages -->
            <?php if(isset($_SESSION['success_message'])): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-700"><?= $_SESSION['success_message'] ?></p>
                    </div>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if(isset($_SESSION['error_message'])): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700"><?= $_SESSION['error_message'] ?></p>
                    </div>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <!-- Filter & Search -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                <form method="GET" class="flex flex-wrap gap-3">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                           placeholder="Cari nama, email, atau no telpon..." 
                           class="flex-1 min-w-[250px] px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    
                    <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="terbaru" <?= $sort == 'terbaru' ? 'selected' : '' ?>>Terbaru</option>
                        <option value="terlama" <?= $sort == 'terlama' ? 'selected' : '' ?>>Terlama</option>
                        <option value="nama" <?= $sort == 'nama' ? 'selected' : '' ?>>Nama (A-Z)</option>
                        <option value="booking" <?= $sort == 'booking' ? 'selected' : '' ?>>Paling Aktif</option>
                    </select>
                    
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        <i class="fa-solid fa-search mr-2"></i>Cari
                    </button>
                    
                    <a href="users_data.php" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                        Reset
                    </a>
                </form>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total User</p>
                            <p class="text-2xl font-bold text-gray-800"><?= $total_users ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Booking</p>
                            <p class="text-2xl font-bold text-gray-800"><?= $total_bookings ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-calendar-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Rata-rata Booking</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?= $total_users > 0 ? number_format($total_bookings / $total_users, 1) : 0 ?>
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">No. Telpon</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal Daftar</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Total Booking</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if(mysqli_num_rows($result) > 0): ?>
                                <?php 
                                mysqli_data_seek($result, 0);
                                while($row = mysqli_fetch_assoc($result)): 
                                ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800">#<?= $row['id_user'] ?></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                    <?= strtoupper(substr($row['nama'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($row['nama']) ?></div>
                                                    <div class="text-xs text-gray-500">ID: <?= $row['id_user'] ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <i class="fa-solid fa-envelope text-gray-400"></i>
                                                <?= htmlspecialchars($row['email']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <i class="fa-solid fa-phone text-gray-400"></i>
                                                <?= htmlspecialchars($row['no_telpon']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <?= date('d M Y', strtotime($row['tanggal_daftar'])) ?>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                                <?= $row['total_booking'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-2">
                                                <button onclick="viewUserDetail(<?= $row['id_user'] ?>)" 
                                                        class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition"
                                                        title="Lihat Detail">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button onclick="viewUserBookings(<?= $row['id_user'] ?>)" 
                                                        class="text-green-600 hover:text-green-800 p-2 hover:bg-green-50 rounded-lg transition"
                                                        title="Lihat Booking">
                                                    <i class="fa-solid fa-calendar-days"></i>
                                                </button>
                                                <button onclick="deleteUser(<?= $row['id_user'] ?>, '<?= htmlspecialchars($row['nama']) ?>', <?= $row['total_booking'] ?>)" 
                                                        class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg transition"
                                                        title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <i class="fa-solid fa-users text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500">Tidak ada data user ditemukan</p>
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

    <!-- Modal Overlay -->
    <div id="modalOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[999] hidden">
        <div id="modalContent" class="bg-white w-[90%] max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-xl relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gray-600 bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <div id="modalBody" class="p-6">
                Memuat...
            </div>
        </div>
    </div>

    <script>
    function viewUserDetail(id) {
        openModal('user_detail.php?id=' + id);
    }

    function viewUserBookings(id) {
        openModal('user_bookings.php?id=' + id);
    }

    function deleteUser(id, nama, totalBooking) {
        if(totalBooking > 0) {
            alert('Tidak dapat menghapus user "' + nama + '" karena memiliki ' + totalBooking + ' booking aktif.\n\nHapus semua booking terlebih dahulu.');
            return;
        }
        
        if(confirm('Yakin ingin menghapus user "' + nama + '"?')) {
            window.location.href = 'users_data.php?delete=' + id;
        }
    }

    function openModal(url) {
        document.getElementById("modalOverlay").classList.remove("hidden");
        let target = document.getElementById("modalBody");
        target.innerHTML = '<div class="flex items-center justify-center py-12"><i class="fa-solid fa-spinner fa-spin text-3xl text-blue-600"></i></div>';
        
        fetch(url)
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

    // Close modal when clicking outside
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if(e.target === this) {
            closeModal();
        }
    });
    </script>

</body>
</html>
