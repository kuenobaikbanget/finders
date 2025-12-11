<?php
session_start();
require_once '../config/db_connect.php';

// Cek Login Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// DELETE dengan Prepared Statement
if (isset($_GET['delete'])) {
    $id_layanan = $_GET['delete'];

    $stmt = mysqli_prepare($conn, "DELETE FROM data_layanan_rs WHERE id_layanan = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_layanan);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = "Data layanan berhasil dihapus";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus data";
    }

    mysqli_stmt_close($stmt);
    header("Location: layanan_data.php");
    exit;
}

// Filter dan Search
$search = $_GET['search'] ?? '';
$filter_rs = $_GET['rs'] ?? '';
$filter_kategori = $_GET['kategori'] ?? '';

// Base Query
$query_sql = "
    SELECT l.*, rs.nama_rs, rs.wilayah
    FROM data_layanan_rs l
    JOIN data_rumah_sakit rs ON l.id_rs = rs.id_rs
    WHERE 1=1
";

// Search
if ($search) {
    $safe = mysqli_real_escape_string($conn, $search);
    $query_sql .= " AND (l.nama_layanan LIKE '%$safe%' OR rs.nama_rs LIKE '%$safe%')";
}

// Filter RS
if ($filter_rs) {
    $safe = mysqli_real_escape_string($conn, $filter_rs);
    $query_sql .= " AND l.id_rs = '$safe'";
}

// Filter Kategori
if ($filter_kategori) {
    $safe = mysqli_real_escape_string($conn, $filter_kategori);
    $query_sql .= " AND l.kategori = '$safe'";
}

$query_sql .= " ORDER BY rs.nama_rs ASC, l.nama_layanan ASC";
$result = mysqli_query($conn, $query_sql);

// Dropdown RS
$query_rs_list = mysqli_query($conn, "SELECT id_rs, nama_rs FROM data_rumah_sakit ORDER BY nama_rs ASC");

// Dropdown kategori
$query_kategori_list = mysqli_query($conn, "SELECT DISTINCT kategori FROM data_layanan_rs ORDER BY kategori ASC");

$page_title = "Data Layanan";
$page_subtitle = "Kelola layanan medis di setiap rumah sakit";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Layanan - Admin FindeRS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">
    
<?php include 'includes/sidebar_admin.php'; ?>

<main class="flex-1 overflow-y-auto">
<div class="p-6">

<?php include 'includes/header_admin.php'; ?>

<!-- Alerts -->
<?php if(isset($_SESSION['success_message'])): ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
    <div class="flex items-center">
        <i class="fa-solid fa-check-circle text-green-500 mr-3"></i>
        <p class="text-green-700"><?= $_SESSION['success_message'] ?></p>
    </div>
</div>
<?php unset($_SESSION['success_message']); endif; ?>

<?php if(isset($_SESSION['error_message'])): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
    <div class="flex items-center">
        <i class="fa-solid fa-exclamation-circle text-red-500 mr-3"></i>
        <p class="text-red-700"><?= $_SESSION['error_message'] ?></p>
    </div>
</div>
<?php unset($_SESSION['error_message']); endif; ?>


<!-- Filter & Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <form method="GET" class="flex flex-wrap gap-3 flex-1">

            <input type="text" name="search" 
                   value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Cari nama layanan atau RS..." 
                   class="flex-1 min-w-[200px] px-4 py-2 border rounded-lg">

            <select name="rs" class="px-4 py-2 border rounded-lg">
                <option value="">Semua RS</option>
                <?php while($rs = mysqli_fetch_assoc($query_rs_list)): ?>
                <option value="<?= $rs['id_rs'] ?>" <?= $filter_rs == $rs['id_rs'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($rs['nama_rs']) ?>
                </option>
                <?php endwhile; ?>
            </select>

            <select name="kategori" class="px-4 py-2 border rounded-lg">
                <option value="">Semua Kategori</option>
                <?php while($kat = mysqli_fetch_assoc($query_kategori_list)): ?>
                <option value="<?= htmlspecialchars($kat['kategori']) ?>" 
                        <?= $filter_kategori == $kat['kategori'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($kat['kategori']) ?>
                </option>
                <?php endwhile; ?>
            </select>

            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                <i class="fa-solid fa-search mr-2"></i>Cari
            </button>

            <a href="layanan_data.php" 
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg">
                Reset
            </a>
        </form>

        <button onclick="openModal('layanan_form.php')" 
                class="px-6 py-2 bg-green-600 text-white rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Layanan
        </button>
    </div>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

    <div class="bg-white p-5 rounded-xl shadow-sm border">
        <p class="text-sm text-gray-500">Total Layanan</p>
        <p class="text-2xl font-bold"><?= mysqli_num_rows($result) ?></p>
    </div>

    <?php
    mysqli_data_seek($result, 0);
    $tersedia = 0;
    while($row = mysqli_fetch_assoc($result)) {
        if ($row['ketersediaan_layanan'] === 'Tersedia') $tersedia++;
    }
    mysqli_data_seek($result, 0);
    ?>

    <div class="bg-white p-5 rounded-xl shadow-sm border">
        <p class="text-sm text-gray-500">Layanan Tersedia</p>
        <p class="text-2xl font-bold text-green-600"><?= $tersedia ?></p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border">
        <p class="text-sm text-gray-500">Tidak Tersedia</p>
        <p class="text-2xl font-bold text-red-600"><?= mysqli_num_rows($result) - $tersedia ?></p>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border">
        <p class="text-sm text-gray-500">Kategori</p>
        <p class="text-2xl font-bold text-purple-600">
            <?php
            mysqli_data_seek($query_kategori_list, 0);
            echo mysqli_num_rows($query_kategori_list);
            ?>
        </p>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold">Nama Layanan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold">Rumah Sakit</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold">Kategori</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold">Ketersediaan</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium">#<?= $row['id_layanan'] ?></td>

                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold">
                            <?= htmlspecialchars($row['nama_layanan']) ?>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="text-sm"><?= htmlspecialchars($row['nama_rs']) ?></div>
                        <div class="text-xs text-gray-500"><?= htmlspecialchars($row['wilayah']) ?></div>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs">
                            <?= htmlspecialchars($row['kategori']) ?>
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <?php if ($row['ketersediaan_layanan'] === 'Tersedia'): ?>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs flex items-center gap-1 w-fit">
                            <i class="fa-solid fa-check-circle"></i> Tersedia
                        </span>
                        <?php else: ?>
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs flex items-center gap-1 w-fit">
                            <i class="fa-solid fa-times-circle"></i> Tidak Tersedia
                        </span>
                        <?php endif; ?>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button onclick="editLayanan(<?= $row['id_layanan'] ?>)"
                                class="text-yellow-600 p-2 hover:bg-yellow-50 rounded-lg">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <button onclick="deleteLayanan(<?= $row['id_layanan'] ?>, '<?= htmlspecialchars($row['nama_layanan']) ?>')"
                                class="text-red-600 p-2 hover:bg-red-50 rounded-lg">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>

            <?php else: ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <i class="fa-solid fa-stethoscope text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Tidak ada data layanan ditemukan</p>
                        <button onclick="openModal('layanan_form.php')" 
                            class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg">
                            Tambah Layanan Pertama
                        </button>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</main>

<script>
function editLayanan(id) {
    openModal('layanan_form.php?id=' + id);
}

function deleteLayanan(id, name) {
    if (confirm("Hapus layanan: " + name + " ?")) {
        window.location.href = "layanan_data.php?delete=" + id;
    }
}
</script>

</body>
</html>
