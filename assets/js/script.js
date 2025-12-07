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
        fetch('rs_detail.php?id=' + id)
            .then(response => {
                // Cek apakah koneksi sukses
                if (!response.ok) {
                    throw new Error('Gagal mengambil data');
                }
                return response.text(); // Ubah respon menjadi teks HTML
            })
            .then(html => {
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