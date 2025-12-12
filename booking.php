<?php 
session_start();
include 'config/db_connect.php';

// Cek Login
if(!isset($_SESSION['user_id'])) {
    // Simpan URL tujuan untuk redirect setelah login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

// Data Pendukung
$selected_rs_id = isset($_GET['rs_id']) ? $_GET['rs_id'] : '';
$id_user = $_SESSION['user_id'];

// Ambil Data User
$query_user = mysqli_query($conn, "SELECT * FROM akun_user WHERE id_user = '$id_user'");
$user = mysqli_fetch_array($query_user);

// Ambil List Rumah Sakit
$query_rs = mysqli_query($conn, "SELECT * FROM data_rumah_sakit ORDER BY nama_rs ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Kunjungan</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/style_user.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    <?php include 'includes/sidebar.php'; ?>

    <main class="flex-1 h-full overflow-y-auto relative scroll-smooth bg-gray-50">
        
        <!-- Hero Image Section -->
        <div class="relative w-full h-80 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900">
            <img src="assets/img/rumahsakit_bg.png" alt="Hospital Building" 
                 class="absolute inset-0 w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/90 via-transparent to-transparent"></div>
            
            <div class="relative z-10 container mx-auto px-6 lg:px-12 h-full flex flex-col justify-center">
                <div>
                    <div class="flex items-center gap-2 text-blue-200 mb-4">
                        <a href="index.php" class="hover:text-white transition">
                            <i class="fa-solid fa-home"></i> Beranda
                        </a>
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                        <span class="text-white font-semibold">Booking Kunjungan</span>
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-white leading-tight drop-shadow-lg mb-4">
                        Pengajuan Kunjungan
                    </h1>
                    <p class="text-blue-100 text-lg max-w-2xl leading-relaxed">
                        Jadwalkan kunjungan Anda dengan mudah. Lengkapi formulir di bawah untuk membuat janji temu dengan dokter spesialis di rumah sakit pilihan Anda.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="container mx-auto px-6 lg:px-12 py-12">
            <div class="max-w-4xl mx-auto">
                
                <!-- Card Container -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                        <h2 class="text-2xl lg:text-3xl font-bold text-white flex items-center gap-3">
                            <i class="fa-solid fa-calendar-check"></i>
                            Formulir Booking Kunjungan
                        </h2>
                        <p class="text-blue-100 text-sm mt-2">
                            Lengkapi data di bawah ini untuk mengajukan jadwal kunjungan
                        </p>
                    </div>

                    <!-- Card Body -->
                    <div class="p-8 lg:p-10">
            
                        <form action="api/booking/create.php" method="POST" class="space-y-6">
                            
                            <!-- Info Alert -->
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-info-circle text-blue-500 mt-1"></i>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-semibold mb-1">Informasi Penting</p>
                                        <p>Harap pastikan data yang Anda masukkan sudah benar. Konfirmasi jadwal akan dikirimkan melalui email dan nomor telepon yang terdaftar.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="fa-solid fa-user text-blue-500 text-xs"></i>
                                        Nama Pasien
                                    </label>
                                    <input type="text" name="nama_pasien" value="<?= htmlspecialchars($user['nama']) ?>" required
                                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border-2 border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-0 text-gray-700 text-sm transition-all placeholder-gray-400"
                                        placeholder="Masukkan nama lengkap pasien">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="fa-solid fa-phone text-blue-500 text-xs"></i>
                                        Nomor Telefon
                                    </label>
                                    <input type="tel" name="no_telpon" value="<?= htmlspecialchars($user['no_telpon']) ?>" required
                                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border-2 border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-0 text-gray-700 text-sm transition-all placeholder-gray-400"
                                        placeholder="Contoh: 08123456789">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <i class="fa-solid fa-hospital text-blue-500 text-xs"></i>
                                    Rumah Sakit Tujuan
                                </label>
                                <div class="relative">
                                    <select name="id_rs" id="id_rs" required
                                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border-2 border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-0 text-gray-700 text-sm appearance-none cursor-pointer transition-all">
                                        <option value="" disabled <?= empty($selected_rs_id) ? 'selected' : '' ?>>Pilih Rumah Sakit</option>
                                        <?php while($rs = mysqli_fetch_array($query_rs)): ?>
                                            <option value="<?= $rs['id_rs'] ?>" <?= ($selected_rs_id == $rs['id_rs']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($rs['nama_rs']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="fa-solid fa-stethoscope text-blue-500 text-xs"></i>
                                        Jenis Layanan
                                    </label>
                                    <div class="relative">
                                        <select name="id_layanan" id="id_layanan" required
                                                class="w-full px-4 py-3 rounded-lg bg-gray-50 border-2 border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-0 text-gray-700 text-sm appearance-none cursor-pointer disabled:bg-gray-100 disabled:text-gray-400 transition-all">
                                            <option value="">Pilih RS Terlebih Dahulu</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                            <i class="fa-solid fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <i class="fa-solid fa-calendar-day text-blue-500 text-xs"></i>
                                        Tanggal Kunjungan
                                    </label>
                                    <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan" required 
                                        min="<?= date('Y-m-d') ?>"
                                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border-2 border-gray-200 focus:border-blue-500 focus:bg-white text-gray-700 text-sm transition-all">
                                </div>
                            </div>

                            <div id="wrapper_sesi" class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <i class="fa-regular fa-clock text-blue-500 text-xs"></i>
                                    Pilih Sesi Kunjungan
                                </label>
                                
                                <div id="grid_sesi" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <div class="col-span-full text-center text-gray-400 text-sm py-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                                        <i class="fa-regular fa-calendar-xmark text-2xl mb-2"></i>
                                        <p>Pilih layanan dan tanggal terlebih dahulu</p>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="jam_mulai" id="jam_mulai" required>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <i class="fa-solid fa-notes-medical text-blue-500 text-xs"></i>
                                    Catatan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span>
                                </label>
                                <textarea name="catatan" rows="4" 
                                        class="w-full px-4 py-3 rounded-lg bg-gray-50 border-2 border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-0 text-gray-700 text-sm transition-all placeholder-gray-400 resize-none"
                                        placeholder="Contoh: Keluhan sesak napas, riwayat alergi obat, atau informasi penting lainnya..."></textarea>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <button type="submit" id="btnSubmitBooking"
                                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-6 rounded-lg shadow-lg transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-3">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span>Konfirmasi Jadwal Kunjungan</span>
                                </button>
                                <p class="text-center text-xs text-gray-500 mt-4 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-shield-halved text-green-500"></i>
                                    Data Anda aman dan terlindungi. Kami menjamin kerahasiaan informasi medis Anda.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>                
            </div>
        </div>
        
        <!-- Modal Booking Berhasil -->
        <div id="modalBookingBerhasil" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
            <div class="bg-white/95 backdrop-blur-md w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row animate-scale-in">
                
                <!-- Left Side - Image -->
                <div class="w-full lg:w-5/12 relative min-h-[200px] lg:min-h-full bg-[#1e3a8a]">
                    <img src="assets/img/rumahsakit_bg.png" alt="Hospital Building" class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-overlay">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#1e3a8a]/90 to-transparent"></div>
                    
                    <div class="absolute top-8 left-8 z-20">
                        <h1 class="text-3xl font-bold text-white leading-tight drop-shadow-md">
                            Pengajuan <br>Kunjungan
                        </h1>
                    </div>
                </div>

                <!-- Right Side - Content -->
                <div class="w-full lg:w-7/12 p-8 lg:p-12 flex flex-col items-center justify-center text-center bg-gray-50/50 relative">
                    
                    <!-- Close Button -->
                    <button onclick="closeModalBooking()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-all">
                        <i class="fa-solid fa-times"></i>
                    </button>
                    
                    <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-6 animate-bounce-slow border-4 border-green-100">
                        <i class="fa-solid fa-check text-4xl text-green-500"></i>
                    </div>

                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2 uppercase tracking-wide">
                        PENDAFTARAN BERHASIL
                    </h2>
                    
                    <p class="text-gray-500 mb-8 max-w-md">
                        Nomor antrian Anda akan dikirimkan melalui WhatsApp dan dapat dicek pada menu Riwayat.
                    </p>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 w-full max-w-md mb-8 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-green-500"></div>
                        <div class="grid grid-cols-2 gap-y-4 text-left text-sm">
                            
                            <div>
                                <span class="block text-gray-400 text-xs font-bold uppercase mb-1">RS Tujuan</span>
                                <span id="modalRsName" class="font-bold text-gray-800 text-base block truncate pr-2">-</span>
                            </div>

                            <div class="text-right">
                                <span class="block text-gray-400 text-xs font-bold uppercase mb-1">Layanan</span>
                                <span id="modalLayananName" class="font-bold text-gray-800 text-base">-</span>
                            </div>

                            <div class="col-span-2 border-t border-gray-100 my-1"></div>

                            <div>
                                <span class="block text-gray-400 text-xs font-bold uppercase mb-1">Tanggal</span>
                                <span id="modalTanggal" class="font-bold text-gray-800 text-base">-</span>
                            </div>

                            <div class="text-right">
                                <span class="block text-gray-400 text-xs font-bold uppercase mb-1">Status</span>
                                <span class="inline-block px-3 py-1 bg-yellow-50 text-yellow-600 border border-yellow-100 text-xs font-bold rounded-lg">
                                    MENUNGGU
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 w-full max-w-md">
                        <a href="index.php" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-green-500/20 transition-all hover:-translate-y-1 text-center flex items-center justify-center gap-2">
                            <i class="fa-solid fa-house"></i> Dashboard
                        </a>
                        
                        <a href="riwayat_pengajuan.php" class="flex-1 bg-white border-2 border-gray-200 hover:border-blue-600 text-gray-600 hover:text-blue-600 font-bold py-3 px-6 rounded-xl transition-all text-center flex items-center justify-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
                        </a>
                    </div>

                </div>
            </div>
        </div>
        
        <?php include 'includes/footer.php'; ?>
    </main>
    
    <style>
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes scale-in {
            from { 
                opacity: 0;
                transform: scale(0.9);
            }
            to { 
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
        
        .animate-scale-in {
            animation: scale-in 0.3s ease-out;
        }
        
        .animate-bounce-slow {
            animation: bounce-slow 2s ease-in-out infinite;
        }
    </style>
    
    <script src="assets/js/script.js"></script>
    <script>
        // Handle form submission dengan AJAX
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('btnSubmitBooking');
            const originalContent = submitBtn.innerHTML;
            
            // Disable button dan ubah text
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Memproses...</span>';
            
            // Kirim data via AJAX
            const formData = new FormData(this);
            
            fetch('api/booking/create.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Tampilkan modal dengan data
                    showModalBooking(data.data);
                    
                    // Reset form
                    this.reset();
                    document.getElementById('grid_sesi').innerHTML = `
                        <div class="col-span-full text-center text-gray-400 text-sm py-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                            <i class="fa-regular fa-calendar-xmark text-2xl mb-2"></i>
                            <p>Pilih layanan dan tanggal terlebih dahulu</p>
                        </div>
                    `;
                } else {
                    // Tampilkan error
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses booking. Silakan coba lagi.');
            })
            .finally(() => {
                // Enable button kembali
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            });
        });
        
        function showModalBooking(data) {
            // Format tanggal
            const date = new Date(data.tanggal);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = date.toLocaleDateString('id-ID', options);
            
            // Isi data ke modal
            document.getElementById('modalRsName').textContent = data.rs_name;
            document.getElementById('modalLayananName').textContent = data.layanan_name;
            document.getElementById('modalTanggal').textContent = formattedDate;
            
            // Tampilkan modal
            document.getElementById('modalBookingBerhasil').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModalBooking() {
            document.getElementById('modalBookingBerhasil').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal dengan ESC key
        document.addEventListener('keydown', function(e) {
            if(e.key === 'Escape') {
                closeModalBooking();
            }
        });
        
        // Close modal dengan click outside
        document.getElementById('modalBookingBerhasil').addEventListener('click', function(e) {
            if(e.target === this) {
                closeModalBooking();
            }
        });
    </script>
</body>
</html>