// Admin Users management functionality

document.addEventListener('DOMContentLoaded', function() {
    initUserManagement();
});

// Initialize user management
function initUserManagement() {
    // Add event listeners for user actions
    setupUserActionListeners();
}

// Setup event listeners for user actions
function setupUserActionListeners() {
    // Logout functionality
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
}

// Logout function with SweetAlert2
function logout() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'question',
            title: 'Logout?',
            text: 'Yakin ingin logout?',
            showCancelButton: true,
            confirmButtonColor: '#0b79d0',
            confirmButtonText: 'Ya, logout!',
        }).then(r => {
            if (r.isConfirmed) {
                // Submit the logout form
                const logoutForm = document.querySelector('form[action*="logout"]');
                if (logoutForm) {
                    logoutForm.submit();
                } else {
                    window.location.href = '<?php echo BASE_URL; ?>/login';
                }
            }
        });
    } else {
        // Fallback if SweetAlert2 is not loaded
        if (confirm('Yakin ingin logout?')) {
            const logoutForm = document.querySelector('form[action*="logout"]');
            if (logoutForm) {
                logoutForm.submit();
            } else {
                window.location.href = '<?php echo BASE_URL; ?>/login';
            }
        }
    }
}

// Reset password function
function resetPassword(name, userId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Reset Password',
            text: `Password untuk ${name} berhasil direset!`,
            confirmButtonColor: '#0b79d0'
        });
    } else {
        alert(`Password untuk ${name} berhasil direset!`);
    }

    // Here you could add AJAX call to actually reset the password
    // For now, just show the message
}

// Delete user function
function deleteUser(name) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus User?',
            text: `Yakin ingin menghapus akun ${name}?`,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((r) => {
            if (r.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Dihapus',
                    text: `Akun ${name} berhasil dihapus`,
                    confirmButtonColor: '#0b79d0'
                });
            }
        });
    } else {
        // Fallback if SweetAlert2 is not loaded
        if (confirm(`Yakin ingin menghapus akun ${name}?`)) {
            alert(`Akun ${name} berhasil dihapus`);
        }
    }
}

// Confirm delete function (used in form onsubmit)
function confirmDelete(name) {
    if (typeof Swal !== 'undefined') {
        return new Promise((resolve) => {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus User?',
                text: `Yakin ingin menghapus akun ${name}?`,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                resolve(result.isConfirmed);
            });
        });
    } else {
        return confirm(`Yakin ingin menghapus akun ${name}?`);
    }
}

// Search functionality
function searchUser() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    if (!input) {
        // If empty, show all rows
        document.querySelectorAll("#userTable tr").forEach(r => r.style.display = "");
        return;
    }

    let rows = document.querySelectorAll("#userTable tr");

    rows.forEach(row => {
        if (!row.cells || row.cells.length < 3) return;
        let name = row.cells[1].innerText.toLowerCase();
        let email = row.cells[2].innerText.toLowerCase();
        row.style.display = (name.includes(input) || email.includes(input)) ? "" : "none";
    });
}

// Sort table functionality
function sortTable(col) {
    let table = document.getElementById("userTable");
    let rows = Array.from(table.rows);

    rows.sort((a, b) =>
        a.cells[col].innerText.localeCompare(b.cells[col].innerText)
    );

    rows.forEach(r => table.appendChild(r));
}

// Add SweetAlert2 CDN if not already loaded
if (typeof Swal === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    script.onload = function() {
        console.log('SweetAlert2 loaded');
    };
    document.head.appendChild(script);
}
