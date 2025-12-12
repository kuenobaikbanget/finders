<?php 

session_start();
include 'config/db_connect.php';

// 1. Cek Login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// 2. Ambil Data User Terbaru
$query = mysqli_query($conn, "SELECT * FROM akun_user WHERE id_user = '$id_user'");
$user = mysqli_fetch_array($query);

// 3. Ambil Pesan Notifikasi (Jika ada update)
$msg_type = $_SESSION['msg_type'] ?? '';
$msg_content = $_SESSION['msg_content'] ?? '';

// Hapus session pesan agar tidak muncul terus saat refresh
unset($_SESSION['msg_type']);
unset($_SESSION['msg_content']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/style_user.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    <?php include 'includes/sidebar.php'; ?>

    <main class="flex-1 h-full overflow-y-auto bg-gray-50 p-6 lg:p-10 scroll-smooth">
        
        <div class="max-w-5xl mx-auto pb-20">
            
            <div class="mb-8 animate-fade-in-down">
                <h1 class="text-3xl font-bold text-gray-800">Profil Saya</h1>
                <p class="text-gray-500 mt-1">Kelola informasi akun dan keamanan Anda.</p>
            </div>

            <?php if($msg_type): ?>
                <div class="mb-6 p-4 rounded-xl flex items-center gap-3 animate-fade-in-up <?= $msg_type == 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' ?>">
                    <i class="fa-solid <?= $msg_type == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> text-xl"></i>
                    <p class="font-medium"><?= $msg_content ?></p>
                </div>
            <?php endif; ?>

            <!-- Card Profil Gabungan -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden animate-fade-in-up max-w-4xl">
                
                <!-- Background Header Biru -->
                <div class="h-36 bg-gradient-to-r from-blue-600 to-blue-400 relative"></div>
                
                <!-- Konten Profil -->
                <div class="px-10 pb-10 -mt-20 relative">
                    
                    <!-- Header: Avatar + Nama -->
                    <div class="flex items-start mb-10">
                        <div class="flex items-center gap-6">
                            <!-- Avatar Circle -->
                            <div class="w-32 h-32 rounded-full bg-white p-2.5 shadow-xl ring-4 ring-white">
                                <img src="assets/img/profile.png" alt="Profile Picture" class="w-full h-full rounded-full object-cover">
                            </div>
                            
                            <!-- Nama User -->
                            <div class="mt-24">
                                <h2 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($user['nama']) ?></h2>
                                <p class="text-gray-500 text-sm mt-1">Member sejak <?= date('F Y', strtotime($user['tanggal_daftar'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 mb-8"></div>

                    <!-- Informasi Akun -->
                    <div class="space-y-1">
                        
                        <!-- Display Name -->
                        <div class="flex items-center justify-between py-5 border-b border-gray-100 hover:bg-gray-50 px-4 rounded-lg transition-all">
                            <div class="flex-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Nama</label>
                                <p class="text-gray-900 font-semibold text-lg"><?= htmlspecialchars($user['nama']) ?></p>
                            </div>
                            <button type="button" onclick="openEditModal('nama')" 
                                    class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-semibold transition-all w-40">
                                Ubah Nama
                            </button>
                        </div>

                        <!-- E-mail (Hidden with Reveal) -->
                        <div class="flex items-center justify-between py-5 border-b border-gray-100 hover:bg-gray-50 px-4 rounded-lg transition-all">
                            <div class="flex-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Email</label>
                                <div class="flex items-center gap-4">
                                    <p class="text-gray-900 font-semibold text-lg">
                                        <span id="emailHidden">************@example.com</span>
                                        <span id="emailRevealed" style="display: none;"><?= htmlspecialchars($user['email']) ?></span>
                                    </p>
                                    <button type="button" onclick="toggleReveal('email')" 
                                            class="text-blue-600 hover:text-blue-700 text-sm font-bold underline">
                                        <span id="emailRevealText">Perlihatkan</span>
                                    </button>
                                </div>
                            </div>
                            <button type="button" onclick="openEditModal('email')" 
                                    class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-semibold transition-all w-40">
                                Ubah Email
                            </button>
                        </div>

                        <!-- Phone Number (Hidden with Reveal) -->
                        <div class="flex items-center justify-between py-5 border-b border-gray-100 hover:bg-gray-50 px-4 rounded-lg transition-all">
                            <div class="flex-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Nomor Handphone</label>
                                <div class="flex items-center gap-4">
                                    <p class="text-gray-900 font-semibold text-lg">
                                        <span id="phoneHidden">**********<?= substr($user['no_telpon'], -4) ?></span>
                                        <span id="phoneRevealed" style="display: none;"><?= htmlspecialchars($user['no_telpon']) ?></span>
                                    </p>
                                    <button type="button" onclick="toggleReveal('phone')" 
                                            class="text-blue-600 hover:text-blue-700 text-sm font-bold underline">
                                        <span id="phoneRevealText">Perlihatkan</span>
                                    </button>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" onclick="openEditModal('phone')" 
                                        class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-semibold transition-all w-40">
                                    Ubah Nomor HP
                                </button>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="flex items-center justify-between py-5 border-b border-gray-100 hover:bg-gray-50 px-4 rounded-lg transition-all">
                            <div class="flex-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Password</label>
                                <p class="text-gray-900 font-semibold text-lg">••••••••••••</p>
                            </div>
                            <button type="button" onclick="openEditModal('password')" 
                                    class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-semibold transition-all w-40">
                                Ubah Password
                            </button>
                        </div>



                    </div>

                        </div>

                </div>
            </div>

            <!-- Floating Modal untuk Edit Nama -->
            <div id="editNameModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-all duration-300">
                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-8 transform transition-all scale-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Edit Nama</h3>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form action="api/profile_process.php" method="POST" class="space-y-5">
                        <!-- Hidden Fields to keep other data -->
                        <input type="hidden" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                        <input type="hidden" name="no_telpon" value="<?= htmlspecialchars($user['no_telpon']) ?>">

                        <!-- Nama -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                                   placeholder="Nama Lengkap Anda">
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Floating Modal untuk Edit Email -->
            <div id="editEmailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-all duration-300">
                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-8 transform transition-all scale-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Edit Email</h3>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form action="api/profile_process.php" method="POST" class="space-y-5">
                        <!-- Hidden Fields to keep other data -->
                        <input type="hidden" name="nama" value="<?= htmlspecialchars($user['nama']) ?>">
                        <input type="hidden" name="no_telpon" value="<?= htmlspecialchars($user['no_telpon']) ?>">

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                                   placeholder="email@example.com">
                        </div>

                        <div class="border-t border-gray-100 my-4"></div>

                        <!-- Password Lama (Konfirmasi) -->
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <label class="block text-sm font-bold text-blue-800 mb-2">Konfirmasi Password</label>
                            <p class="text-xs text-blue-600 mb-3">Masukkan password Anda saat ini untuk menyimpan perubahan.</p>
                            <input type="password" name="password_lama" required
                                   class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-white"
                                   placeholder="Password Saat Ini">
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Floating Modal untuk Edit Telepon -->
            <div id="editPhoneModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-all duration-300">
                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-8 transform transition-all scale-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Edit Nomor Telepon</h3>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form action="api/profile_process.php" method="POST" class="space-y-5">
                        <!-- Hidden Fields to keep other data -->
                        <input type="hidden" name="nama" value="<?= htmlspecialchars($user['nama']) ?>">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($user['email']) ?>">

                        <!-- No Telepon -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" name="no_telpon" value="<?= htmlspecialchars($user['no_telpon']) ?>" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                                   placeholder="08xxxxxxxxxx">
                        </div>

                        <div class="border-t border-gray-100 my-4"></div>

                        <!-- Password Lama (Konfirmasi) -->
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <label class="block text-sm font-bold text-blue-800 mb-2">Konfirmasi Password</label>
                            <p class="text-xs text-blue-600 mb-3">Masukkan password Anda saat ini untuk menyimpan perubahan.</p>
                            <input type="password" name="password_lama" required
                                   class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-white"
                                   placeholder="Password Saat Ini">
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Floating Modal untuk Ubah Password -->
            <div id="editPasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-all duration-300">
                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-8 transform transition-all scale-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Ubah Password</h3>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form action="api/profile_process.php" method="POST" class="space-y-5" onsubmit="return validatePasswordChange()">
                        <!-- Hidden Fields to keep other data -->
                        <input type="hidden" name="nama" value="<?= htmlspecialchars($user['nama']) ?>">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                        <input type="hidden" name="no_telpon" value="<?= htmlspecialchars($user['no_telpon']) ?>">

                        <!-- Password Lama -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Lama</label>
                            <input type="password" name="password_lama" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                                   placeholder="Masukkan password lama Anda">
                        </div>

                        <div class="border-t border-gray-100 my-4"></div>

                        <!-- Password Baru -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password_baru" id="password_baru" required minlength="6"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                                   placeholder="Masukkan password baru (min. 8 karakter)">
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="konfirmasi_password_baru" id="konfirmasi_password_baru" required minlength="6"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                                   placeholder="Ulangi password baru Anda">
                        </div>

                        <!-- Info Box -->
                        <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                            <div class="flex gap-2">
                                <i class="fa-solid fa-info-circle text-yellow-600 mt-1"></i>
                                <div>
                                    <p class="text-xs font-bold text-yellow-800 mb-1">Syarat Password:</p>
                                    <ul class="text-xs text-yellow-700 space-y-1">
                                        <li>• Minimal 8 karakter</li>
                                        <li>• Disarankan kombinasi huruf, angka, dan simbol</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" onclick="closeEditModal()" class="flex-1 px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                                Simpan Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <script>
        // ==============================================
        // MODAL FUNCTIONS
        // ==============================================
        
        function openEditModal(type) {
            // Close all first
            closeEditModal();
            
            let modalId = '';
            if(type === 'nama') modalId = 'editNameModal';
            if(type === 'email') modalId = 'editEmailModal';
            if(type === 'phone') modalId = 'editPhoneModal';
            if(type === 'password') modalId = 'editPasswordModal';
            
            if(modalId) {
                const modal = document.getElementById(modalId);
                if(modal) {
                    modal.classList.remove('hidden');
                    // Focus input
                    const input = modal.querySelector('input:not([type="hidden"])');
                    if(input) setTimeout(() => input.focus(), 100);
                }
            }
        }
        
        function closeEditModal() {
            const modals = ['editNameModal', 'editEmailModal', 'editPhoneModal', 'editPasswordModal'];
            modals.forEach(id => {
                const el = document.getElementById(id);
                if(el) el.classList.add('hidden');
            });
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.id === 'editNameModal' || 
                event.target.id === 'editEmailModal' || 
                event.target.id === 'editPhoneModal' ||
                event.target.id === 'editPasswordModal') {
                closeEditModal();
            }
        });

        // ==============================================
        // PASSWORD VALIDATION
        // ==============================================
        
        function validatePasswordChange() {
            const passwordBaru = document.getElementById('password_baru').value;
            const konfirmasiPassword = document.getElementById('konfirmasi_password_baru').value;
            
            if (passwordBaru !== konfirmasiPassword) {
                alert('Password baru dan konfirmasi password tidak cocok!');
                return false;
            }
            
            if (passwordBaru.length < 8) {
                alert('Password baru minimal 8 karakter!');
                return false;
            }
            
            return true;
        }

        // ==============================================
        // REVEAL/HIDE FUNCTIONS
        // ==============================================
        
        /**
         * Toggle reveal for email and phone
         * @param {string} type - 'email' or 'phone'
         */
        function toggleReveal(type) {
            if (type === 'email') {
                const hidden = document.getElementById('emailHidden');
                const revealed = document.getElementById('emailRevealed');
                const revealText = document.getElementById('emailRevealText');
                
                if (hidden.style.display === 'none') {
                    hidden.style.display = 'inline';
                    revealed.style.display = 'none';
                    revealText.textContent = 'Perlihatkan';
                } else {
                    hidden.style.display = 'none';
                    revealed.style.display = 'inline';
                    revealText.textContent = 'Sembunyikan';
                }
            } else if (type === 'phone') {
                const hidden = document.getElementById('phoneHidden');
                const revealed = document.getElementById('phoneRevealed');
                const revealText = document.getElementById('phoneRevealText');
                
                if (hidden.style.display === 'none') {
                    hidden.style.display = 'inline';
                    revealed.style.display = 'none';
                    revealText.textContent = 'Perlihatkan';
                } else {
                    hidden.style.display = 'none';
                    revealed.style.display = 'inline';
                    revealText.textContent = 'Sembunyikan';
                }
            }
        }

        // ==============================================
        // ANIMATIONS
        // ==============================================
        
        /**
         * Smooth scroll animations on page load
         */
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation classes
            const elements = document.querySelectorAll('.animate-fade-in-up, .animate-fade-in-down');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>