// Login Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const loginForms = document.querySelectorAll('.login-form');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons and forms
            tabButtons.forEach(btn => btn.classList.remove('active'));
            loginForms.forEach(form => form.classList.remove('active'));
            
            // Add active class to clicked button
            button.classList.add('active');
            
            // Show corresponding form
            const tabId = button.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Form validation
    const loginForm = document.getElementById('masuk');
    const registerForm = document.getElementById('daftar');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi.');
                return false;
            }
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const name = document.getElementById('reg-name').value;
            const email = document.getElementById('reg-email').value;
            const phone = document.getElementById('reg-phone').value;
            const password = document.getElementById('reg-password').value;
            const confirmPassword = document.getElementById('reg-password-confirm').value;
            
            if (!name || !email || !phone || !password || !confirmPassword) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi.');
                return false;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok.');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password harus minimal 6 karakter.');
                return false;
            }
        });
    }

    // Add CSRF token to all forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (csrfToken) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = '_token';
            hiddenInput.value = csrfToken;
            form.appendChild(hiddenInput);
        }
    });
    
    // Add CSRF token to all AJAX requests
    document.addEventListener('submit', function(e) {
        if (e.target.tagName === 'FORM') {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if (csrfToken) {
                const existingToken = e.target.querySelector('input[name="_token"]');
                if (!existingToken) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = '_token';
                    hiddenInput.value = csrfToken;
                    e.target.appendChild(hiddenInput);
                }
            }
        }
    });
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    forms.forEach(form => {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = '_token';
        hiddenInput.value = csrfToken;
        form.appendChild(hiddenInput);
    });
});