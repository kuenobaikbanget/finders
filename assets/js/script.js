// ========================================
// FORM VALIDATION FOR REGISTER PAGE
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // Check if we're on the register page
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('no_telpon');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('konfirmasi_password');

    // Only run validations if elements exist (register page)
    if (emailInput && phoneInput && passwordInput && confirmPasswordInput) {
        
        // ========================================
        // EMAIL VALIDATION
        // ========================================
        const emailError = document.getElementById('email-error');

        emailInput.addEventListener('blur', function() {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailPattern.test(this.value)) {
                emailError.classList.remove('hidden');
                this.classList.add('border-red-500');
            } else {
                emailError.classList.add('hidden');
                this.classList.remove('border-red-500');
            }
        });

        emailInput.addEventListener('input', function() {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailPattern.test(this.value)) {
                emailError.classList.add('hidden');
                this.classList.remove('border-red-500');
            }
        });

        // ========================================
        // PHONE NUMBER VALIDATION (Only Numbers)
        // ========================================
        const phoneError = document.getElementById('phone-error');

        phoneInput.addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            if (this.value && /[^0-9]/.test(e.data)) {
                phoneError.classList.remove('hidden');
                this.classList.add('border-red-500');
            } else {
                phoneError.classList.add('hidden');
                this.classList.remove('border-red-500');
            }
        });

        phoneInput.addEventListener('keypress', function(e) {
            // Only allow numbers
            if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab' && e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') {
                e.preventDefault();
                phoneError.classList.remove('hidden');
                this.classList.add('border-red-500');
                setTimeout(() => {
                    phoneError.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }, 2000);
            }
        });

        // ========================================
        // PASSWORD VALIDATION (Minimum 8 characters)
        // ========================================
        const passwordError = document.getElementById('password-error');

        passwordInput.addEventListener('input', function() {
            if (this.value.length > 0 && this.value.length < 8) {
                passwordError.classList.remove('hidden');
                this.classList.add('border-red-500');
            } else {
                passwordError.classList.add('hidden');
                this.classList.remove('border-red-500');
            }

            // Also check confirm password when password changes
            if (confirmPasswordInput.value.length > 0) {
                validateConfirmPassword();
            }
        });

        passwordInput.addEventListener('blur', function() {
            if (this.value.length > 0 && this.value.length < 8) {
                passwordError.classList.remove('hidden');
                this.classList.add('border-red-500');
            }
        });

        // ========================================
        // CONFIRM PASSWORD VALIDATION
        // ========================================
        const confirmPasswordError = document.getElementById('confirm-password-error');

        function validateConfirmPassword() {
            if (confirmPasswordInput.value.length > 0) {
                if (confirmPasswordInput.value !== passwordInput.value) {
                    confirmPasswordError.classList.remove('hidden');
                    confirmPasswordInput.classList.add('border-red-500');
                    return false;
                } else {
                    confirmPasswordError.classList.add('hidden');
                    confirmPasswordInput.classList.remove('border-red-500');
                    return true;
                }
            }
            return true;
        }

        confirmPasswordInput.addEventListener('input', function() {
            validateConfirmPassword();
        });

        confirmPasswordInput.addEventListener('blur', function() {
            validateConfirmPassword();
        });

        // ========================================
        // FORM SUBMIT VALIDATION
        // ========================================
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Validate email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value)) {
                emailError.classList.remove('hidden');
                emailInput.classList.add('border-red-500');
                isValid = false;
            }

            // Validate phone
            if (!/^[0-9]+$/.test(phoneInput.value)) {
                phoneError.classList.remove('hidden');
                phoneInput.classList.add('border-red-500');
                isValid = false;
            }

            // Validate password length
            if (passwordInput.value.length < 8) {
                passwordError.classList.remove('hidden');
                passwordInput.classList.add('border-red-500');
                isValid = false;
            }

            // Validate password match
            if (!validateConfirmPassword()) {
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
});

// =================================================================
// LOGIKA MODAL DETAIL RUMAH SAKIT (POP-UP)
// =================================================================

/**
 * Fungsi untuk membuka Modal (Floating Window)
 * Dipanggil saat tombol "Detail" diklik
 * @param {integer} id - ID Rumah Sakit yang ingin ditampilkan
 */
function openDetail(id) {
    // 1. Ambil elemen Overlay dan Content Modal dari HTML
    const overlay = document.getElementById('modalOverlay');
    const content = document.getElementById('modalContent');
    
    console.log('Opening detail for RS ID:', id); // Debug
    
    // 2. Tampilkan Overlay (Hapus class 'hidden' bawaan Tailwind)
    if (overlay) {
        overlay.classList.remove('hidden');
    }
    
    // 3. Tampilkan Animasi Loading Sementara
    // Ini agar user tahu sistem sedang memproses data sebelum konten asli muncul
    if (content) {
        content.innerHTML = `
            <div class="bg-white p-6 rounded-2xl shadow-xl flex items-center gap-3 animate-pulse">
                <div class="w-6 h-6 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                <span class="font-medium text-gray-600">Memuat data...</span>
            </div>
        `;

        // 4. Request Data ke Server (AJAX Fetch)
        // Memanggil file rs_detail.php tanpa me-reload halaman
        const url = 'rs_detail.php?id=' + id;
        console.log('Fetching URL:', url); // Debug
        
        fetch(url)
            .then(response => {
                console.log('Response status:', response.status); // Debug
                // Cek apakah koneksi sukses
                if (!response.ok) {
                    throw new Error('Gagal mengambil data');
                }
                return response.text(); // Ubah respon menjadi teks HTML
            })
            .then(html => {
                console.log('HTML received, length:', html.length); // Debug
                // 5. Masukkan HTML yang diterima ke dalam Modal Content
                content.innerHTML = html;
            })
            .catch(err => {
                // 6. Error Handling: Tampilkan pesan error jika gagal
                console.error('Error:', err);
                content.innerHTML = '<div class="bg-white p-4 rounded-xl text-red-500">Gagal memuat data. Silakan coba lagi.</div>';
            });
    }
}

/**
 * Fungsi untuk menutup Modal
 * Dipanggil saat tombol Close (X), klik background gelap, atau tekan ESC
 */
function closeModal() {
    const overlay = document.getElementById('modalOverlay');
    
    // Sembunyikan overlay dengan menambahkan kembali class 'hidden'
    if (overlay) {
        overlay.classList.add('hidden');
    }
}

// =================================================================
// EVENT LISTENERS (PENGINTAI AKSI USER)
// =================================================================

document.addEventListener('DOMContentLoaded', function() {
    
    // Event Listener untuk tombol Keyboard
    document.addEventListener('keydown', function(event) {
        // Jika user menekan tombol ESC (Escape)
        if (event.key === "Escape") {
            closeModal(); // Panggil fungsi tutup modal
        }
    });

});


// ... (Kode modal detail RS sebelumnya biarkan di atas) ...

// =================================================================
// LOGIKA HALAMAN BOOKING (booking.php)
// =================================================================

/**
 * Fungsi untuk mengambil data layanan via AJAX
 * Dipanggil saat dropdown RS berubah atau saat halaman dimuat
 */
function loadLayanan(rsId) {
    const layananSelect = document.getElementById('id_layanan');
    
    // Pastikan elemen ada (Mencegah error jika script dipanggil di halaman lain)
    if (!layananSelect) return;

    console.log('Loading layanan for RS ID:', rsId); // Debug

    // Reset Dropdown & Tampilkan Loading
    layananSelect.innerHTML = '<option>Memuat layanan...</option>';
    layananSelect.disabled = true;

    // Fetch Data ke API PHP
    fetch(`api/booking/get_layanan.php?id_rs=${rsId}`)
        .then(response => {
            console.log('Response status:', response.status); // Debug
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Layanan data received:', data); // Debug
            
            // Reset Opsi
            layananSelect.innerHTML = '<option value="">Pilih Layanan</option>';
            
            if(data && data.length > 0) {
                // Loop data layanan dan masukkan ke dropdown
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id_layanan;
                    option.textContent = item.nama_layanan + ' (' + item.kategori + ')';
                    layananSelect.appendChild(option);
                });
                layananSelect.disabled = false;
                console.log('✅ Loaded', data.length, 'layanan'); // Debug
            } else {
                layananSelect.innerHTML = '<option value="">Tidak ada layanan tersedia</option>';
                console.warn('⚠️ No layanan found for RS ID:', rsId); // Debug
            }
        })
        .catch(error => {
            console.error('❌ Error loading layanan:', error);
            layananSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            layananSelect.disabled = false; // Biarkan user bisa retry dengan ganti RS
        });
}

// Event Listener Otomatis saat Halaman Booking Dimuat
document.addEventListener('DOMContentLoaded', function() {
    
    // Cari elemen dropdown RS
    const rsSelect = document.getElementById('id_rs');

    // Cek apakah kita sedang berada di halaman booking
    if (rsSelect) {
        
        // 1. Cek Nilai Awal (Auto-load)
        // Jika user datang dari tombol "Buat Janji" di detail RS, 
        // dropdown RS sudah terpilih otomatis lewat PHP. Kita perlu load layanannya.
        if (rsSelect.value) {
            loadLayanan(rsSelect.value);
        }

        // 2. Event Listener Perubahan (Change)
        // Saat user mengganti pilihan RS secara manual
        rsSelect.addEventListener('change', function() {
            loadLayanan(this.value);
        });
    }
});

// =================================================================
// LOGIKA MODAL DETAIL KUNJUNGAN (Floating Window)
// =================================================================

/**
 * Modal Detail Kunjungan
 * Fungsi untuk menampilkan detail lengkap riwayat kunjungan dalam floating window
 */

/**
 * Fungsi untuk membuka modal detail kunjungan
 * @param {Object} data - Data riwayat kunjungan dari database
 */
function openDetailKunjungan(data) {
    // Set status badge berdasarkan status kunjungan
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

    // Update status badge di modal
    document.getElementById('modalStatus').className = "px-6 py-2 rounded-full text-sm font-bold border flex items-center gap-2 " + statusBadge;
    document.getElementById('modalStatusIcon').className = "fa-solid " + statusIcon;
    document.getElementById('modalStatusText').textContent = statusText;

    // Set informasi rumah sakit dan layanan
    document.getElementById('modalRsName').textContent = data.nama_rs;
    document.getElementById('modalLayanan').textContent = data.nama_layanan;
    document.getElementById('modalPasien').textContent = data.nama_pasien;
    document.getElementById('modalNik').textContent = data.no_nik || '-';
    
    // Format tanggal kunjungan
    const tanggal = new Date(data.tanggal_kunjungan);
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const bulan = tanggal.toLocaleDateString('id-ID', { month: 'short' });
    const hari = tanggal.toLocaleDateString('id-ID', { weekday: 'long' });
    
    document.getElementById('modalTanggal').textContent = tanggal.getDate();
    document.getElementById('modalBulan').textContent = bulan;
    document.getElementById('modalTanggalLengkap').textContent = tanggal.toLocaleDateString('id-ID', options);
    document.getElementById('modalHari').textContent = hari;

    // Tampilkan nomor antrean dan estimasi waktu (hanya jika dikonfirmasi atau selesai)
    if(data.status == 'Dikonfirmasi' || data.status == 'Selesai') {
        document.getElementById('modalQueueSection').classList.remove('hidden');
        document.getElementById('modalQueue').textContent = data.queue_number || '-';
        document.getElementById('modalEstimasi').textContent = data.estimasi_jam || '08:00 - 10:00';
    } else {
        document.getElementById('modalQueueSection').classList.add('hidden');
    }

    // Set catatan
    document.getElementById('modalCatatan').textContent = data.catatan || 'Tidak ada catatan';

    // Format waktu dibuat booking
    const dibuatPada = new Date(data.dibuat_pada);
    document.getElementById('modalDibuatPada').textContent = dibuatPada.toLocaleDateString('id-ID', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    // Tampilkan modal
    document.getElementById('detailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

/**
 * Fungsi untuk menutup modal detail kunjungan
 */
function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    if(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Event listener untuk menutup modal dengan tombol ESC (sudah ada di atas, tapi kita tambahkan check untuk detailModal)
document.addEventListener('keydown', function(e) {
    if(e.key === 'Escape') {
        const detailModal = document.getElementById('detailModal');
        if(detailModal && !detailModal.classList.contains('hidden')) {
            closeDetailModal();
        }
    }
});

// Prevent scroll pada body ketika modal terbuka
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('detailModal');
    if(modal) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if(mutation.attributeName === 'class') {
                    if(!modal.classList.contains('hidden')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = 'auto';
                    }
                }
            });
        });
        
        observer.observe(modal, {
            attributes: true
        });
    }
});
