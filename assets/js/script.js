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
