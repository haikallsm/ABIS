// Admin Requests management functionality

document.addEventListener('DOMContentLoaded', function() {
    initRequestManagement();
});

// Initialize request management
function initRequestManagement() {
    // Only run on letter-requests page, not on users page
    if (window.location.pathname.includes('/admin/letter-requests') ||
        window.location.pathname.includes('/admin/requests')) {

        // Reset all button styles to ensure they are visible
        resetButtonStyles();
        setupStatusSelectHandlers();
        setupModalHandlers();
        setupFormHandlers();

        // Force button visibility after a short delay to ensure DOM is fully loaded
        setTimeout(() => {
            ensureActionButtonsVisibility();
            console.log('Admin requests page fully initialized');
        }, 100);

        // Add continuous monitoring to prevent buttons from disappearing
        startButtonVisibilityMonitor();
    } else {
        console.log('Admin requests JS loaded but not on requests page - skipping initialization');
    }
}

// Monitor button visibility and text content continuously to prevent hiding
function startButtonVisibilityMonitor() {
    // Store original text content to restore if lost
    const originalTexts = new Map();

    // Initialize original texts on first run
    function initializeOriginalTexts() {
        // Store button texts
        const buttons = document.querySelectorAll('button[id^="approve-btn-"], button[id^="reject-btn-"]');
        buttons.forEach(btn => {
            if (!originalTexts.has(btn.id)) {
                originalTexts.set(btn.id, btn.textContent.trim());
            }
        });

        // Store status span texts
        const statusSpans = document.querySelectorAll('td.text-center span.px-3.py-1\\.5.rounded-full');
        statusSpans.forEach((span, index) => {
            const key = `status-span-${index}`;
            const text = span.textContent.trim();
            if (!originalTexts.has(key) && text) {
                originalTexts.set(key, text);
                // Store original text as data attribute for CSS fallback
                span.setAttribute('data-original-text', text);
            }
        });
    }

    // Check every 1 second if content is still intact
    setInterval(() => {
        let fixedCount = 0;

        // Check buttons
        const buttons = document.querySelectorAll('button[id^="approve-btn-"], button[id^="reject-btn-"]');
        buttons.forEach(btn => {
            const originalText = originalTexts.get(btn.id);
            const currentText = btn.textContent.trim();

            // Check if button is visible
            const computedStyle = window.getComputedStyle(btn);
            const isVisible = computedStyle.display !== 'none' &&
                            computedStyle.visibility !== 'hidden' &&
                            parseFloat(computedStyle.opacity) > 0;

            if (!isVisible) {
                fixedCount++;
                // Force show the button
                btn.style.setProperty('display', 'inline-flex', 'important');
                btn.style.setProperty('visibility', 'visible', 'important');
                btn.style.setProperty('opacity', '1', 'important');
                btn.disabled = false;
                console.log(`Fixed visibility for button: ${btn.id}`);
            }

            // Check if text content is missing or changed unexpectedly
            if (originalText && (currentText === '' || currentText === 'undefined' || currentText === null)) {
                fixedCount++;
                // Restore original text
                btn.innerHTML = originalText.includes('Setujui') ?
                    '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Setujui' :
                    '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Tolak';
                console.log(`Restored text for button: ${btn.id}`);
            }
        });

        // Check status spans
        const statusSpans = document.querySelectorAll('td.text-center span.px-3.py-1\\.5.rounded-full');
        statusSpans.forEach((span, index) => {
            const key = `status-span-${index}`;
            const originalText = originalTexts.get(key);
            const currentText = span.textContent.trim();

            // Check if span text is missing
            if (originalText && (currentText === '' || currentText === 'undefined' || currentText === null)) {
                fixedCount++;
                // Restore original status text
                span.textContent = originalText;
                span.setAttribute('data-original-text', originalText);
                console.log(`Restored text for status span ${index}: ${originalText}`);
            }
        });

        if (fixedCount > 0) {
            console.log(`Fixed ${fixedCount} missing texts/elements`);
        }
    }, 1000); // Check every 1 second

    // Initialize original texts after a short delay
    setTimeout(() => {
        initializeOriginalTexts();
        console.log('Text content monitor initialized');
    }, 500);

    // Also monitor for DOM changes that might affect content
    const observer = new MutationObserver((mutations) => {
        let shouldReset = false;
        mutations.forEach((mutation) => {
            // Check for text content changes
            if (mutation.type === 'childList') {
                const target = mutation.target;
                if (target.id && (target.id.startsWith('approve-btn-') || target.id.startsWith('reject-btn-'))) {
                    if (target.textContent.trim() === '') {
                        shouldReset = true;
                    }
                }
            }
            // Check for attribute changes
            if (mutation.type === 'attributes' &&
                (mutation.attributeName === 'style' || mutation.attributeName === 'class')) {
                const target = mutation.target;
                if (target.id && (target.id.startsWith('approve-btn-') || target.id.startsWith('reject-btn-'))) {
                    const computedStyle = window.getComputedStyle(target);
                    if (computedStyle.display === 'none' || computedStyle.visibility === 'hidden' || computedStyle.opacity === '0') {
                        shouldReset = true;
                    }
                }
            }
        });

        if (shouldReset) {
            console.log('DOM mutation detected - resetting content');
            resetButtonStyles();
        }
    });

    // Start observing changes to button elements
    const buttons = document.querySelectorAll('button[id^="approve-btn-"], button[id^="reject-btn-"]');
    buttons.forEach(btn => {
        observer.observe(btn, {
            attributes: true,
            attributeFilter: ['style', 'class'],
            childList: true,
            subtree: true
        });
    });

    console.log('Button and text content monitor started');
}

// Restore button text if it's missing
function restoreButtonText(btn) {
    const currentText = btn.textContent.trim();

    if (currentText === '' || currentText === 'undefined' || currentText === null) {
        if (btn.id.includes('approve')) {
            btn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Setujui';
        } else if (btn.id.includes('reject')) {
            btn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Tolak';
        }
        console.log(`Restored text for button: ${btn.id}`);
    }
}

// Restore status text if it's missing
function restoreStatusText(span) {
    const currentText = span.textContent.trim();

    if (currentText === '' || currentText === 'undefined' || currentText === null) {
        // Determine status from existing CSS classes (preserve current styling)
        let restoredText = '';
        if (span.classList.contains('bg-green-100') || span.classList.contains('bg-green-600')) {
            restoredText = 'Disetujui';
        } else if (span.classList.contains('bg-red-100') || span.classList.contains('bg-red-600')) {
            restoredText = 'Ditolak';
        } else if (span.classList.contains('bg-yellow-100') || span.classList.contains('bg-yellow-600')) {
            restoredText = 'Menunggu';
        } else if (span.classList.contains('bg-blue-100') || span.classList.contains('bg-blue-600')) {
            restoredText = 'Diproses';
        } else {
            // Default fallback if no status classes found
            restoredText = 'Menunggu';
            span.className = 'px-3 py-1.5 rounded-full text-xs font-semibold border bg-yellow-100 text-yellow-700 border-yellow-200';
        }

        span.textContent = restoredText;
        span.setAttribute('data-original-text', restoredText);
        console.log(`Restored text for status span: ${restoredText}`);
    }
}

// Reset all button styles to ensure visibility
function resetButtonStyles() {
    // Reset opacity for all action buttons - ULTRA AGGRESSIVE
    const allActionButtons = document.querySelectorAll('button');
    allActionButtons.forEach(btn => {
        // Only affect buttons in the table actions
        if (btn.closest('td.text-center') || btn.id.startsWith('approve-btn-') || btn.id.startsWith('reject-btn-')) {
            // Force all visibility properties using setProperty
            btn.style.setProperty('opacity', '1', 'important');
            btn.style.setProperty('display', 'inline-flex', 'important');
            btn.style.setProperty('visibility', 'visible', 'important');
            btn.style.setProperty('pointer-events', 'auto', 'important');
            btn.style.setProperty('transform', 'none', 'important');
            btn.disabled = false;

            // Remove any transition/animation effects
            btn.style.setProperty('transition', 'none', 'important');
            btn.style.setProperty('animation', 'none', 'important');

            // Mark as processed to prevent re-hiding
            btn.classList.add('processed');

            // Clear any existing event listeners that might hide
            btn.onmouseover = null;
            btn.onmouseout = null;

            console.log(`Reset button: ${btn.id || 'unnamed'}`);
        }
    });

    // Reset status spans
    const statusSpans = document.querySelectorAll('td.text-center span.px-3.py-1\\.5.rounded-full');
    statusSpans.forEach(span => {
        span.style.setProperty('opacity', '1', 'important');
        span.style.setProperty('visibility', 'visible', 'important');
        span.style.setProperty('display', 'inline-block', 'important');

        // Restore status text if missing, but preserve existing status
        const currentText = span.textContent.trim();
        if (currentText === '' || currentText === 'undefined' || currentText === null) {
            // Determine status from existing CSS classes (don't guess from buttons)
            let restoredText = '';
            if (span.classList.contains('bg-green-100') || span.classList.contains('bg-green-600')) {
                restoredText = 'Disetujui';
            } else if (span.classList.contains('bg-red-100') || span.classList.contains('bg-red-600')) {
                restoredText = 'Ditolak';
            } else if (span.classList.contains('bg-yellow-100') || span.classList.contains('bg-yellow-600')) {
                restoredText = 'Menunggu';
            } else if (span.classList.contains('bg-blue-100') || span.classList.contains('bg-blue-600')) {
                restoredText = 'Diproses';
            } else {
                // Default fallback if no status classes found
                restoredText = 'Menunggu';
                span.className = 'px-3 py-1.5 rounded-full text-xs font-semibold border bg-yellow-100 text-yellow-700 border-yellow-200';
            }

            span.textContent = restoredText;
            span.setAttribute('data-original-text', restoredText);
            console.log('Restored missing status text:', restoredText);
        }
    });

    // Additional check: ensure action buttons in table are visible
    ensureActionButtonsVisibility();
}

// Ensure action buttons in the requests table remain visible
function ensureActionButtonsVisibility() {
    // Find all table cells with action buttons (both Setujui/Tolak and Aksi columns)
    const actionCells = document.querySelectorAll('td.text-center .flex.items-center.justify-center.gap-2');

    actionCells.forEach(cell => {
        const buttons = cell.querySelectorAll('button');
        buttons.forEach(btn => {
            // Force visibility for all action buttons
            btn.style.opacity = '1 !important';
            btn.style.display = 'inline-flex !important';
            btn.style.visibility = 'visible !important';

            // Prevent any future hiding by adding a class
            btn.classList.add('force-visible');
        });
    });

    // Also ensure buttons in all table cells are protected (including empty cells)
    const allTableCells = document.querySelectorAll('td.text-center');
    allTableCells.forEach(cell => {
        const buttons = cell.querySelectorAll('button');
        buttons.forEach(btn => {
            if (!btn.classList.contains('force-visible')) {
                btn.classList.add('force-visible');
                btn.style.opacity = '1 !important';
                btn.style.display = 'inline-flex !important';
                btn.style.visibility = 'visible !important';
            }
        });
    });

    console.log('Action buttons visibility ensured for', actionCells.length, 'action cells and all table buttons protected');

    // Add CSS rule to prevent hiding of force-visible buttons - MORE AGGRESSIVE
    if (!document.getElementById('force-visible-styles')) {
        const style = document.createElement('style');
        style.id = 'force-visible-styles';
        style.textContent = `
            .force-visible,
            button[id^="approve-btn-"],
            button[id^="reject-btn-"] {
                opacity: 1 !important;
                display: inline-flex !important;
                visibility: visible !important;
                pointer-events: auto !important;
                transform: none !important;
                animation: none !important;
                transition: none !important;
            }
            .force-visible:hover,
            button[id^="approve-btn-"]:hover,
            button[id^="reject-btn-"]:hover {
                opacity: 1 !important;
                display: inline-flex !important;
                visibility: visible !important;
                transform: none !important;
            }
            /* Override any hiding animations or transitions */
            button[id^="approve-btn-"],
            button[id^="reject-btn-"] {
                animation-duration: 0s !important;
                transition-duration: 0s !important;
                transition-delay: 0s !important;
            }
            /* Prevent any CSS that might hide buttons after delay */
            button[id^="approve-btn-"]:not(.processed),
            button[id^="reject-btn-"]:not(.processed) {
                opacity: 1 !important;
                display: inline-flex !important;
                visibility: visible !important;
            }
            /* Protect status spans from losing text */
            td.text-center span.px-3.py-1\.5.rounded-full {
                opacity: 1 !important;
                visibility: visible !important;
                display: inline-block !important;
                /* Prevent text from being hidden or removed */
                content: attr(data-original-text) !important;
            }
            /* Protect all text content in status column */
            td.text-center:has(span.px-3.py-1\.5.rounded-full),
            td.text-center span.px-3.py-1\.5.rounded-full {
                opacity: 1 !important;
                visibility: visible !important;
            }
            /* Extra protection for status spans */
            td.text-center span.px-3.py-1\.5.rounded-full::before,
            td.text-center span.px-3.py-1\.5.rounded-full::after {
                content: none !important;
            }
            /* Prevent any script from clearing text content */
            td.text-center span.px-3.py-1\.5.rounded-full:empty {
                content: "Menunggu" !important;
                background-color: rgb(254 240 138) !important;
                color: rgb(67 56 202) !important;
                border-color: rgb(252 211 77) !important;
            }
        `;
        document.head.appendChild(style);
    }
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
        // Get buttons for THIS SPECIFIC REQUEST only
        const approveBtn = document.getElementById(`approve-btn-${requestId}`);
        const rejectBtn = document.getElementById(`reject-btn-${requestId}`);

        if (!approveBtn) {
            showNotification('Tombol approve tidak ditemukan', 'error');
            return;
        }

        // Disable buttons for this request only
        if (approveBtn) {
            approveBtn.disabled = true;
            approveBtn.style.opacity = '0.5';
        }
        if (rejectBtn) {
            rejectBtn.disabled = true;
            rejectBtn.style.opacity = '0.5';
        }

        const originalText = approveBtn.innerHTML;
        approveBtn.innerHTML = '<svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';

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

                // Update approve button to show success state
                approveBtn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Disetujui';
                approveBtn.className = 'px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-medium force-visible';
                approveBtn.disabled = true;
                approveBtn.style.opacity = '1 !important';
                approveBtn.style.display = 'inline-flex !important';
                approveBtn.style.visibility = 'visible !important';

                // Hide reject button for this request only
                if (rejectBtn) {
                    rejectBtn.style.display = 'none';
                    rejectBtn.style.visibility = 'hidden';
                }

                // Update status badge in the status column
                updateStatusBadge(requestId, 'approved');

                // Force refresh button visibility for all rows
                setTimeout(() => {
                    ensureActionButtonsVisibility();
                }, 100);

            } else {
                showNotification('Gagal menyetujui permohonan: ' + (data.message || 'Unknown error'), 'error');

                // Re-enable buttons for this request
                if (approveBtn) {
                    approveBtn.disabled = false;
                    approveBtn.style.opacity = '1';
                    approveBtn.innerHTML = originalText;
                }
                if (rejectBtn) {
                    rejectBtn.disabled = false;
                    rejectBtn.style.opacity = '1';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat memproses permohonan', 'error');

            // Re-enable buttons for this request
            if (approveBtn) {
                approveBtn.disabled = false;
                approveBtn.style.opacity = '1';
                approveBtn.innerHTML = originalText;
            }
            if (rejectBtn) {
                rejectBtn.disabled = false;
                rejectBtn.style.opacity = '1';
            }
        });
    }
}

// Reject request function
function rejectRequest(requestId, userName) {
    const notes = prompt(`Masukkan alasan penolakan untuk permohonan surat atas nama ${userName}:`);

    if (notes !== null && notes.trim() !== '') {
        // Get buttons for THIS SPECIFIC REQUEST only
        const approveBtn = document.getElementById(`approve-btn-${requestId}`);
        const rejectBtn = document.getElementById(`reject-btn-${requestId}`);

        if (!rejectBtn) {
            showNotification('Tombol reject tidak ditemukan', 'error');
            return;
        }

        // Disable buttons for this request only
        if (approveBtn) {
            approveBtn.disabled = true;
            approveBtn.style.opacity = '0.5';
        }
        if (rejectBtn) {
            rejectBtn.disabled = true;
            rejectBtn.style.opacity = '0.5';
        }

        const originalText = rejectBtn.innerHTML;
        rejectBtn.innerHTML = '<svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';

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

                // Update reject button to show rejection state
                rejectBtn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Ditolak';
                rejectBtn.className = 'px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-medium force-visible';
                rejectBtn.disabled = true;
                rejectBtn.style.opacity = '1 !important';
                rejectBtn.style.display = 'inline-flex !important';
                rejectBtn.style.visibility = 'visible !important';

                // Hide approve button for this request only
                if (approveBtn) {
                    approveBtn.style.display = 'none';
                    approveBtn.style.visibility = 'hidden';
                }

                // Update status badge in the status column
                updateStatusBadge(requestId, 'rejected');

                // Force refresh button visibility for all rows
                setTimeout(() => {
                    ensureActionButtonsVisibility();
                }, 100);

            } else {
                showNotification('Gagal menolak permohonan: ' + (data.message || 'Unknown error'), 'error');

                // Re-enable buttons for this request
                if (approveBtn) {
                    approveBtn.disabled = false;
                    approveBtn.style.opacity = '1';
                }
                if (rejectBtn) {
                    rejectBtn.disabled = false;
                    rejectBtn.style.opacity = '1';
                    rejectBtn.innerHTML = originalText;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat memproses permohonan', 'error');

            // Re-enable buttons for this request
            if (approveBtn) {
                approveBtn.disabled = false;
                approveBtn.style.opacity = '1';
            }
            if (rejectBtn) {
                rejectBtn.disabled = false;
                rejectBtn.style.opacity = '1';
                rejectBtn.innerHTML = originalText;
            }
        });
    }
}

// Download PDF function
function downloadPDF(requestId) {
    try {
        console.log('Attempting to download PDF for request:', requestId);
        console.log('Base URL:', window.baseUrl);

        const downloadUrl = `${window.baseUrl}/admin/requests/${requestId}/download`;
        console.log('Download URL:', downloadUrl);

        // Use fetch to download the file
        fetch(downloadUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin' // Include cookies for authentication
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.blob();
        })
        .then(blob => {
            // Create download link from blob
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;

            // Get filename from response or use default
            const filename = `surat_${requestId}.pdf`;
            link.download = filename;

            document.body.appendChild(link);
            link.click();

            // Clean up
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);

            console.log('PDF download completed');
        })
        .catch(error => {
            console.error('Error downloading PDF:', error);
            showNotification('Gagal mengunduh PDF. Pastikan Anda sudah login sebagai admin.', 'error');
        });

    } catch (error) {
        console.error('Error in downloadPDF function:', error);
        showNotification('Terjadi kesalahan saat mengunduh PDF.', 'error');
    }
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

// Global variables are set in layout

// Helper function to update status badge
function updateStatusBadge(requestId, newStatus) {
    // Find the status cell for this request
    const statusCells = document.querySelectorAll('td.text-center');
    let targetCell = null;

    // Look for the cell containing the status badge for this request
    for (const cell of statusCells) {
        const statusSpan = cell.querySelector('.px-3.py-1\\.5.rounded-full');
        if (statusSpan && cell.closest('tr')) {
            // Check if this row contains the request ID
            const row = cell.closest('tr');
            const approveBtn = row.querySelector(`#approve-btn-${requestId}`);
            if (approveBtn) {
                targetCell = cell;
                break;
            }
        }
    }

    if (targetCell) {
        const statusSpan = targetCell.querySelector('.px-3.py-1\\.5.rounded-full');
        if (statusSpan) {
            // Update status text and classes
            statusSpan.className = 'px-3 py-1.5 rounded-full text-xs font-semibold border';

            switch (newStatus) {
                case 'approved':
                    statusSpan.className += ' bg-green-100 text-green-700 border-green-200';
                    statusSpan.textContent = 'Disetujui';
                    break;
                case 'rejected':
                    statusSpan.className += ' bg-red-100 text-red-700 border-red-200';
                    statusSpan.textContent = 'Ditolak';
                    break;
                case 'pending':
                    statusSpan.className += ' bg-yellow-100 text-yellow-700 border-yellow-200';
                    statusSpan.textContent = 'Menunggu';
                    break;
                default:
                    statusSpan.className += ' bg-gray-100 text-gray-700 border-gray-200';
                    statusSpan.textContent = newStatus;
            }
        }
    }
}

// Add CSRF token meta tag if not exists
if (!document.querySelector('meta[name="csrf-token"]')) {
    const meta = document.createElement('meta');
    meta.name = 'csrf-token';
    meta.content = '<?php echo generateCSRFToken(); ?>';
    document.head.appendChild(meta);
}

// SweetAlert2 loading is now handled by showNotification function
