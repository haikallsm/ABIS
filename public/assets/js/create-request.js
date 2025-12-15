/**
 * Create Request Page JavaScript
 * Handles form interactions for creating letter requests
 */

// Letter type change handler
document.getElementById('letter_type_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const description = selectedOption.getAttribute('data-description');
    const descriptionElement = document.getElementById('letterTypeDescription');

    if (description) {
        descriptionElement.textContent = description;
        descriptionElement.classList.remove('hidden');
    } else {
        descriptionElement.classList.add('hidden');
    }

    // Load dynamic fields via AJAX if needed
    const letterTypeId = this.value;
    if (letterTypeId) {
        loadDynamicFields(letterTypeId);
    } else {
        document.getElementById('dynamicFields').classList.add('hidden');
    }
});

// Load dynamic fields based on letter type
function loadDynamicFields(letterTypeId) {
    fetch(`${BASE_URL}/api/letter-types/${letterTypeId}/fields`)
        .then(response => response.json())
        .then(data => {
            const fieldsContainer = document.getElementById('fieldsContainer');
            fieldsContainer.innerHTML = '';

            if (data.fields && data.fields.length > 0) {
                data.fields.forEach(field => {
                    const fieldDiv = document.createElement('div');
                    fieldDiv.className = 'mb-4';
                    fieldDiv.innerHTML = `
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ${field.label} ${field.required ? '<span class="text-red-500">*</span>' : ''}
                        </label>
                        <input type="text" name="${field.name}" ${field.required ? 'required' : ''}
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                               placeholder="${field.placeholder || ''}">
                    `;
                    fieldsContainer.appendChild(fieldDiv);
                });
            }

            document.getElementById('dynamicFields').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading fields:', error);
        });
}

// Form submission handler
document.getElementById('requestForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="flex items-center"><svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mengirim...</span>';

    // Re-enable button after 10 seconds as fallback
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }, 10000);
});

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Create request page initialized');
});

