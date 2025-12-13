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

// Handle Delete
if(isset($_GET['delete'])) {
    $id_rs = mysqli_real_escape_string($conn, $_GET['delete']);
    $query_delete = mysqli_query($conn, "DELETE FROM data_rumah_sakit WHERE id_rs = '$id_rs'");
    
    if($query_delete) {
        $_SESSION['success_message'] = "Data rumah sakit berhasil dihapus";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus data: " . mysqli_error($conn);
    }
    header("Location: rs_data.php");
    exit;
}

// Filter dan Search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter_wilayah = isset($_GET['wilayah']) ? mysqli_real_escape_string($conn, $_GET['wilayah']) : '';

// Query dengan filter
$query_sql = "SELECT * FROM data_rumah_sakit WHERE 1=1";

if($search) {
    $query_sql .= " AND (nama_rs LIKE '%$search%' OR alamat LIKE '%$search%')";
}

if($filter_wilayah) {
    $query_sql .= " AND wilayah = '$filter_wilayah'";
}

$query_sql .= " ORDER BY nama_rs ASC";
$result = mysqli_query($conn, $query_sql);

// Query untuk dropdown wilayah
$query_wilayah = mysqli_query($conn, "SELECT DISTINCT wilayah FROM data_rumah_sakit ORDER BY wilayah ASC");

$page_title = "Data Rumah Sakit";
$page_subtitle = "Kelola data rumah sakit terdaftar dalam sistem";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Rumah Sakit - Admin FindeRS</title>
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
                <div class="flex flex-wrap gap-3 items-center justify-between">
                    <form method="GET" class="flex flex-wrap gap-3 flex-1">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                               placeholder="Cari nama RS atau alamat..." 
                               class="flex-1 min-w-[250px] px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        
                        <select name="wilayah" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Wilayah</option>
                            <?php while($w = mysqli_fetch_assoc($query_wilayah)): ?>
                                <option value="<?= htmlspecialchars($w['wilayah']) ?>" 
                                        <?= $filter_wilayah == $w['wilayah'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($w['wilayah']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                            <i class="fa-solid fa-search mr-2"></i>Cari
                        </button>
                        
                        <a href="rs_data.php" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                            Reset
                        </a>
                    </form>

                    <button onclick="openModal('rs_form.php')" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        Tambah RS
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total RS</p>
                            <p class="text-2xl font-bold text-gray-800"><?= mysqli_num_rows($result) ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-hospital text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Wilayah Tercakup</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php
                                mysqli_data_seek($query_wilayah, 0);
                                echo mysqli_num_rows($query_wilayah);
                                ?>
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-map-location-dot text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">RS Aktif</p>
                            <p class="text-2xl font-bold text-gray-800"><?= mysqli_num_rows($result) ?></p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-check-circle text-purple-600 text-xl"></i>
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
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Foto</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama RS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Alamat</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Wilayah</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kontak</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if(mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800">#<?= $row['id_rs'] ?></td>
                                        <td class="px-6 py-4">
                                            <img src="../assets/img/<?= htmlspecialchars($row['foto']) ?>" 
                                                 alt="<?= htmlspecialchars($row['nama_rs']) ?>"
                                                 class="w-16 h-16 rounded-lg object-cover border border-gray-200"
                                                 onerror="this.src='../assets/img/default_rs.jpg'">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($row['nama_rs']) ?></div>
                                            <div class="text-xs text-gray-500">ID: <?= $row['id_rs'] ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600 max-w-xs truncate">
                                                <?= htmlspecialchars($row['alamat']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                                <?= htmlspecialchars($row['wilayah']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-phone text-green-600"></i>
                                                <?= htmlspecialchars($row['no_telpon']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-2">
                                                <button onclick="viewDetail(<?= $row['id_rs'] ?>)" 
                                                        class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition"
                                                        title="Lihat Detail">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button onclick="editRS(<?= $row['id_rs'] ?>)" 
                                                        class="text-yellow-600 hover:text-yellow-800 p-2 hover:bg-yellow-50 rounded-lg transition"
                                                        title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button onclick="deleteRS(<?= $row['id_rs'] ?>, '<?= htmlspecialchars($row['nama_rs']) ?>')" 
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
                                        <i class="fa-solid fa-hospital text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500">Tidak ada data rumah sakit ditemukan</p>
                                        <button onclick="openModal('rs_form.php')" 
                                                class="mt-4 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                                            Tambah RS Pertama
                                        </button>
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

    function viewDetail(id) {
        window.open('../rs_detail.php?id=' + id, '_blank');
    }

    function editRS(id) {
        openModal('rs_form.php?id=' + id);
    }

    function deleteRS(id, nama) {
        if(confirm('Yakin ingin menghapus RS "' + nama + '"?\n\nPeringatan: Semua data layanan dan jadwal terkait akan ikut terhapus!')) {
            window.location.href = 'rs_data.php?delete=' + id;
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
