// Admin Requests management functionality

document.addEventListener('DOMContentLoaded', function() {
    initRequestManagement();
});

// Initialize request management
function initRequestManagement() {
    setupStatusSelectHandlers();
    setupModalHandlers();
    setupFormHandlers();
}

// Setup status select handlers
function setupStatusSelectHandlers() {
    document.querySelectorAll('.status-select').forEach(selectElement => {
        updateStatusView(selectElement);
        selectElement.addEventListener('change', function() {
            updateStatusView(this);
            handleStatusChange(this);
        });
    });
}

// Update status view based on selected value
function updateStatusView(select) {
    const status = select.value;
    const printButton = document.getElementById(`print-${select.dataset.id}`);

    // Reset kelas warna
    select.classList.remove('status-pending', 'status-approved', 'status-rejected');

    if (status === 'pending') {
        select.classList.add('status-pending');
        if (printButton) printButton.classList.add('hidden');
    } else if (status === 'approved') {
        select.classList.add('status-approved');
        if (printButton) printButton.classList.remove('hidden');
    } else if (status === 'rejected') {
        select.classList.add('status-rejected');
        if (printButton) printButton.classList.add('hidden');
    }
}

// Handle status change
function handleStatusChange(selectElement) {
    const requestId = selectElement.dataset.id.replace('sp-', '');
    const newStatus = selectElement.value;

    // Send AJAX request to update status
    fetch(window.baseUrl + '/api/admin/requests/' + requestId + '/status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'status=' + newStatus + '&csrf_token=' + document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Status berhasil diperbarui', 'success');
            // Update the print button visibility
            updateStatusView(selectElement);
        } else {
            showNotification(data.message || 'Gagal memperbarui status', 'error');
            // Revert the selection
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat memperbarui status', 'error');
        location.reload();
    });
}

// Setup modal handlers
function setupModalHandlers() {
    const btnTambahSurat = document.getElementById('btn-tambah-surat');
    const formModal = document.getElementById('form-modal');
    const closeFormModalBtn = document.getElementById('close-form-modal');
    const deleteModal = document.getElementById('delete-modal');
    const modalConfirmBtn = document.getElementById('modal-confirm');
    const modalCancelBtn = document.getElementById('modal-cancel');

    let itemToDeleteId = null;

    // Show form modal
    if (btnTambahSurat) {
        btnTambahSurat.addEventListener('click', function() {
            formModal.classList.remove('hidden');
        });
    }

    // Hide form modal
    if (closeFormModalBtn) {
        closeFormModalBtn.addEventListener('click', function() {
            formModal.classList.add('hidden');
            document.getElementById('surat-pengantar-form').reset();
        });
    }

    // Hide delete modal
    if (modalCancelBtn) {
        modalCancelBtn.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            itemToDeleteId = null;
        });
    }

    // Handle delete confirmation
    if (modalConfirmBtn) {
        modalConfirmBtn.addEventListener('click', function() {
            if (itemToDeleteId) {
                // Submit the delete form
                const deleteForm = document.querySelector(`form[action*="${itemToDeleteId}/delete"]`);
                if (deleteForm) {
                    deleteForm.submit();
                }
            }
            deleteModal.classList.add('hidden');
        });
    }

    // Setup delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            itemToDeleteId = this.dataset.id;
            const row = this.closest('tr');
            const pemohonName = row.querySelector('[data-name]').dataset.name;

            document.getElementById('modal-item-name').textContent = `Permohonan atas nama: ${pemohonName}`;
            deleteModal.classList.remove('hidden');
        });
    });
}

// Setup form handlers
function setupFormHandlers() {
    const form = document.getElementById('surat-pengantar-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            submitBtn.disabled = true;

            // Send form data
            fetch(window.baseUrl + '/requests/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Permohonan surat berhasil dibuat!', 'success');
                    document.getElementById('form-modal').classList.add('hidden');
                    form.reset();
                    // Reload page to show new data
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'Gagal membuat permohonan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat membuat permohonan', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
}

// Confirm delete function (for form onsubmit)
function confirmDelete(name) {
    if (typeof Swal !== 'undefined') {
        return new Promise((resolve) => {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Permohonan?',
                text: `Yakin ingin menghapus permohonan surat atas nama ${name}?`,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                resolve(result.isConfirmed);
            });
        });
    } else {
        return confirm(`Yakin ingin menghapus permohonan surat atas nama ${name}?`);
    }
}

// Print function
function printSurat(requestId) {
    window.open(window.baseUrl + '/admin/requests/' + requestId + '/download', '_blank');
}

// Edit function
function editSurat(requestId) {
    showNotification('Fitur edit akan segera hadir', 'info');
}

// Edit function
function editSurat(requestId) {
    showNotification('Fitur edit akan segera hadir', 'info');
}

// Approve request function
function approveRequest(requestId, userName) {
    if (confirm(`Apakah Anda yakin ingin menyetujui permohonan surat atas nama ${userName}?`)) {
        // Show loading state and disable all buttons in this row
        const button = event.target.closest('button');
        const buttonContainer = button.closest('.flex');
        const allButtons = buttonContainer.querySelectorAll('button');

        // Disable all buttons
        allButtons.forEach(btn => {
            btn.disabled = true;
            btn.style.opacity = '0.5';
        });

        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
        button.disabled = true;

        // Prepare form data
        const formData = new FormData();
        formData.append('notes', 'Disetujui oleh admin');

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            formData.append('csrf_token', csrfToken);
        }

        // Send AJAX request
        fetch(`${window.baseUrl}/admin/requests/${requestId}/approve`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Permohonan berhasil disetujui!', 'success');
                // Force reload page to show updated status (bypass cache)
                setTimeout(() => {
                    window.location.reload(true);
                }, 1500);
            } else {
                showNotification('Gagal menyetujui permohonan: ' + (data.message || 'Unknown error'), 'error');

                // Re-enable all buttons
                allButtons.forEach(btn => {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                });

                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat memproses permohonan', 'error');

            // Re-enable all buttons
            allButtons.forEach(btn => {
                btn.disabled = false;
                btn.style.opacity = '1';
            });

            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

// Reject request function
function rejectRequest(requestId, userName) {
    const notes = prompt(`Masukkan alasan penolakan untuk permohonan surat atas nama ${userName}:`);

    if (notes !== null && notes.trim() !== '') {
        // Show loading state and disable all buttons in this row
        const button = event.target.closest('button');
        const buttonContainer = button.closest('.flex');
        const allButtons = buttonContainer.querySelectorAll('button');

        // Disable all buttons
        allButtons.forEach(btn => {
            btn.disabled = true;
            btn.style.opacity = '0.5';
        });

        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
        button.disabled = true;

        // Prepare form data
        const formData = new FormData();
        formData.append('notes', notes.trim());

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            formData.append('csrf_token', csrfToken);
        }

        // Send AJAX request
        fetch(`${window.baseUrl}/admin/requests/${requestId}/reject`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Permohonan berhasil ditolak!', 'success');
                // Force reload page to show updated status (bypass cache)
                setTimeout(() => {
                    window.location.reload(true);
                }, 1500);
            } else {
                showNotification('Gagal menolak permohonan: ' + (data.message || 'Unknown error'), 'error');

                // Re-enable all buttons
                allButtons.forEach(btn => {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                });

                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat memproses permohonan', 'error');

            // Re-enable all buttons
            allButtons.forEach(btn => {
                btn.disabled = false;
                btn.style.opacity = '1';
            });

            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

// Download PDF function
function downloadPDF(requestId) {
    window.open(`${window.baseUrl}/admin/requests/${requestId}/download`, '_blank');
}

// Notification system
function showNotification(message, type = 'info') {
    // Wait for SweetAlert2 to load if it's being loaded
    if (typeof Swal === 'undefined') {
        // Check if we're currently loading it
        const existingScript = document.querySelector('script[src*="sweetalert2"]');
        if (!existingScript) {
            // Load SweetAlert2 synchronously
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
            script.onload = function() {
                showSwalNotification(message, type);
            };
            script.onerror = function() {
                alert(message); // Fallback
            };
            document.head.appendChild(script);
        } else {
            // Wait a bit and try again
            setTimeout(() => showNotification(message, type), 100);
        }
    } else {
        showSwalNotification(message, type);
    }
}

function showSwalNotification(message, type) {
    const title = type === 'success' ? 'Berhasil' :
                 type === 'error' ? 'Error' :
                 type === 'warning' ? 'Peringatan' : 'Info';

    Swal.fire({
        icon: type,
        title: title,
        text: message,
        confirmButtonColor: '#0b79d0'
    });
}

// Initialize global variables
window.baseUrl = '<?php echo BASE_URL; ?>';

// Add CSRF token meta tag if not exists
if (!document.querySelector('meta[name="csrf-token"]')) {
    const meta = document.createElement('meta');
    meta.name = 'csrf-token';
    meta.content = '<?php echo generateCSRFToken(); ?>';
    document.head.appendChild(meta);
}

// SweetAlert2 loading is now handled by showNotification function
