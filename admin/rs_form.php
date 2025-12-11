<?php
require_once '../config/db_connect.php';

// Cek apakah mode EDIT atau TAMBAH
$id_rs = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;
$mode = $id_rs ? 'edit' : 'tambah';

$data = [
    'id_rs' => '',
    'nama_rs' => '',
    'alamat' => '',
    'wilayah' => '',
    'no_telpon' => '',
    'deskripsi' => '',
    'foto' => 'default_rs.jpg'
];

// Jika mode EDIT, ambil data dari database
if($mode == 'edit') {
    $query = mysqli_query($conn, "SELECT * FROM data_rumah_sakit WHERE id_rs = '$id_rs'");
    if(mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
    } else {
        echo '<div class="text-red-500 text-center py-8">Data tidak ditemukan</div>';
        exit;
    }
}

// Daftar wilayah Jakarta (bisa disesuaikan)
$wilayah_options = [
    'Jakarta Pusat',
    'Jakarta Utara',
    'Jakarta Selatan',
    'Jakarta Timur',
    'Jakarta Barat',
    'Kepulauan Seribu'
];
?>

<div class="p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-hospital text-blue-600"></i>
        </div>
        <?= $mode == 'edit' ? 'Edit Data Rumah Sakit' : 'Tambah Rumah Sakit Baru' ?>
    </h2>

    <form action="rs_proses.php" method="POST" enctype="multipart/form-data" class="space-y-6">
        
        <!-- Hidden input untuk mode -->
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <?php if($mode == 'edit'): ?>
            <input type="hidden" name="id_rs" value="<?= $data['id_rs'] ?>">
            <input type="hidden" name="foto_lama" value="<?= $data['foto'] ?>">
        <?php endif; ?>

        <!-- Nama RS -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Nama Rumah Sakit <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_rs" required 
                   value="<?= htmlspecialchars($data['nama_rs']) ?>"
                   placeholder="Contoh: RSUP Fatmawati"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Alamat -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Alamat Lengkap <span class="text-red-500">*</span>
            </label>
            <textarea name="alamat" required rows="3"
                      placeholder="Contoh: Jl. RS Fatmawati No.1, Cilandak"
                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"><?= htmlspecialchars($data['alamat']) ?></textarea>
        </div>

        <!-- Wilayah & No Telpon -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Wilayah <span class="text-red-500">*</span>
                </label>
                <select name="wilayah" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Wilayah --</option>
                    <?php foreach($wilayah_options as $wilayah): ?>
                        <option value="<?= $wilayah ?>" <?= $data['wilayah'] == $wilayah ? 'selected' : '' ?>>
                            <?= $wilayah ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nomor Telepon <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="no_telpon" required 
                       value="<?= htmlspecialchars($data['no_telpon']) ?>"
                       placeholder="Contoh: 021-7501524"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Deskripsi Rumah Sakit <span class="text-red-500">*</span>
            </label>
            <textarea name="deskripsi" required rows="4"
                      placeholder="Deskripsi singkat tentang rumah sakit, fasilitas, atau keunggulan..."
                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            <p class="text-xs text-gray-500 mt-1">Minimal 50 karakter</p>
        </div>

        <!-- Upload Foto -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Foto Rumah Sakit
            </label>
            
            <?php if($mode == 'edit' && $data['foto']): ?>
                <div class="mb-3">
                    <p class="text-xs text-gray-600 mb-2">Foto saat ini:</p>
                    <img src="../assets/img/<?= htmlspecialchars($data['foto']) ?>" 
                         alt="Foto RS" 
                         class="w-32 h-32 object-cover rounded-xl border border-gray-200"
                         onerror="this.src='../assets/img/default_rs.jpg'">
                </div>
            <?php endif; ?>

            <input type="file" name="foto" accept="image/*"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold hover:file:bg-blue-100">
            <p class="text-xs text-gray-500 mt-1">
                <?= $mode == 'edit' ? 'Kosongkan jika tidak ingin mengubah foto. ' : '' ?>
                Format: JPG, PNG, JPEG (Max 2MB)
            </p>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-info-circle text-blue-600 mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Informasi Penting:</p>
                    <ul class="list-disc list-inside space-y-1 text-blue-700">
                        <li>Pastikan semua data yang diisi sudah benar</li>
                        <li>Nomor telepon harus aktif dan bisa dihubungi</li>
                        <li>Foto akan ditampilkan di halaman publik</li>
                        <li>Data ini akan langsung tampil untuk user</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
            <button type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                <i class="fa-solid fa-save"></i>
                <?= $mode == 'edit' ? 'Simpan Perubahan' : 'Tambah Rumah Sakit' ?>
            </button>
            <button type="button" onclick="window.parent.closeModal()" 
                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-times"></i>
                Batal
            </button>
        </div>

    </form>

</div>

<script>
// Preview image before upload
document.querySelector('input[type="file"]').addEventListener('change', function(e) {
    if(e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(event) {
            // Buat preview jika belum ada
            let preview = document.getElementById('preview-image');
            if(!preview) {
                preview = document.createElement('img');
                preview.id = 'preview-image';
                preview.className = 'w-32 h-32 object-cover rounded-xl border border-gray-200 mt-3';
                e.target.parentElement.appendChild(preview);
            }
            preview.src = event.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});

// Validasi form sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
    const deskripsi = document.querySelector('textarea[name="deskripsi"]').value;
    if(deskripsi.length < 50) {
        e.preventDefault();
        alert('Deskripsi minimal 50 karakter!');
        return false;
    }
});
</script>