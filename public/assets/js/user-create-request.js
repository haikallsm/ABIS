// User Create Request JavaScript Functionality

// Function to show/hide form based on letter type selection
document.getElementById('letter_type_id').addEventListener('change', function() {
    const selectedType = this.value;
    const dynamicFields = document.getElementById('dynamicFields');
    const allForms = document.querySelectorAll('.surat-form');

    // Hide all forms first
    allForms.forEach(form => {
        form.style.display = 'none';
    });

    // Show selected form and dynamic fields container
    if (selectedType) {
        const selectedForm = document.getElementById(`form-${selectedType}`);
        if (selectedForm) {
            selectedForm.style.display = 'block';
            dynamicFields.classList.remove('hidden');
        }
    } else {
        dynamicFields.classList.add('hidden');
    }

    // Update form action URL with type parameter
    const form = document.getElementById('requestForm');
    if (selectedType) {
        form.action = `${BASE_URL}/requests/create?type=${selectedType}`;
    } else {
        form.action = `${BASE_URL}/requests/create`;
    }
});

// Pre-select letter type if URL has type parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const typeParam = urlParams.get('type');

    if (typeParam) {
        const selectElement = document.getElementById('letter_type_id');
        selectElement.value = typeParam;

        // Trigger change event to show the form
        const event = new Event('change');
        selectElement.dispatchEvent(event);
    }

    // Set minimum date for birth date input
    const today = new Date().toISOString().split('T')[0];
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        if (input.id === 'tanggal_lahir') {
            // For birth date, set max to today
            input.max = today;
        }
    });
});

// Form validation before submit
document.getElementById('requestForm').addEventListener('submit', function(e) {
    const letterType = document.getElementById('letter_type_id').value;

    if (!letterType) {
        e.preventDefault();
        alert('Pilih jenis surat terlebih dahulu');
        return;
    }

    // Validate required fields based on letter type
    let isValid = true;
    let message = '';

    if (letterType === 'keterangan') {
        const keperluan = document.getElementById('keperluan').value.trim();
        if (!keperluan) {
            isValid = false;
            message = 'Isi keperluan surat terlebih dahulu';
        }
    } else if (letterType === 'domisili') {
        const alamat = document.getElementById('alamat').value.trim();
        if (!alamat) {
            isValid = false;
            message = 'Isi alamat lengkap terlebih dahulu';
        }
    } else if (letterType === 'tidak-mampu') {
        const penghasilan = document.getElementById('penghasilan').value.trim();
        const alasan = document.getElementById('alasan').value.trim();
        if (!penghasilan) {
            isValid = false;
            message = 'Isi penghasilan terlebih dahulu';
        } else if (!alasan) {
            isValid = false;
            message = 'Isi alasan permohonan terlebih dahulu';
        }
    } else if (letterType === 'usaha') {
        const namaUsaha = document.getElementById('nama_usaha').value.trim();
        const jenisUsaha = document.getElementById('jenis_usaha').value.trim();
        const alamatUsaha = document.getElementById('alamat_usaha').value.trim();
        if (!namaUsaha || !jenisUsaha || !alamatUsaha) {
            isValid = false;
            message = 'Isi semua field yang wajib diisi untuk surat usaha';
        }
    } else if (letterType === 'kelahiran') {
        const namaBayi = document.getElementById('nama_bayi').value.trim();
        const tanggalLahir = document.getElementById('tanggal_lahir').value;
        const namaOrtu = document.getElementById('nama_ortu').value.trim();
        const jenisKelamin = document.getElementById('jenis_kelamin').value;
        if (!namaBayi || !tanggalLahir || !namaOrtu || !jenisKelamin) {
            isValid = false;
            message = 'Isi semua field yang wajib diisi untuk surat kelahiran';
        }
    }

    if (!isValid) {
        e.preventDefault();
        alert(message);
        return;
    }

    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = `
        <span class="flex items-center">
            <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Mengirim...
        </span>
    `;
    submitBtn.disabled = true;

    // Re-enable after 3 seconds (in case of error)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});
