/**
 * Requests Page JavaScript
 * Handles table filtering, search, and request actions
 */

// Search and filter functionality
function filterRequests() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#requestsTable tr');

    rows.forEach(row => {
        // Skip header row
        if (row.cells.length < 4) return;

        const letterType = row.cells[1]?.textContent.toLowerCase() || '';
        const status = row.cells[2]?.textContent.toLowerCase() || '';

        const matchesSearch = letterType.includes(searchTerm);
        const matchesStatus = !statusFilter || status.includes(statusFilter);

        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
}

// Attach event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Search input event listener
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterRequests);
    }

    // Status filter event listener
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', filterRequests);
    }

    console.log('Requests page initialized');
});

// View request details
function viewRequest(requestId) {
    // Implement view request functionality
    console.log('View request:', requestId);

    // For now, redirect to a detail page
    // You can implement AJAX call to load request details in a modal
    window.location.href = `${BASE_URL}/request/${requestId}`;
}

// Download request
function downloadRequest(requestId) {
    // Implement download functionality
    console.log('Download request:', requestId);

    // Redirect to download endpoint
    window.location.href = `${BASE_URL}/request/${requestId}/download`;
}

// Modal functions
function closeModal() {
    const modal = document.getElementById('requestModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Open request detail modal
function openRequestModal(requestId) {
    // This function can be extended to load request details via AJAX
    console.log('Opening modal for request:', requestId);

    const modal = document.getElementById('requestModal');
    if (modal) {
        modal.classList.remove('hidden');
        // Load content via AJAX here
    }
}

