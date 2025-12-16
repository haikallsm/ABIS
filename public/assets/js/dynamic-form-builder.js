/**
 * Dynamic Form Builder for ABIS Letter Request System
 * Intelligent form rendering based on letter type configurations
 *
 * @author ABIS Development Team
 * @version 1.0.0
 * @since December 2025
 */

class DynamicFormBuilder {
    constructor(options = {}) {
        this.options = {
            formSelector: '#letter-request-form',
            letterTypeSelect: '#letter_type_id',
            formContainer: '#dynamic-form-container',
            loadingClass: 'loading',
            apiBaseUrl: '/api',
            ...options
        };

        this.userProfile = {};
        this.currentLetterType = null;
        this.formConfig = null;

        this.init();
    }

    /**
     * Initialize the form builder
     */
    init() {
        this.bindEvents();
        this.loadUserProfile();
        console.log('🎨 Dynamic Form Builder initialized');
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        const letterTypeSelect = document.querySelector(this.options.letterTypeSelect);
        if (letterTypeSelect) {
            letterTypeSelect.addEventListener('change', (e) => {
                this.onLetterTypeChange(e.target.value);
            });
        }

        // Auto-load form if letter type is pre-selected
        const preSelectedType = letterTypeSelect?.value;
        if (preSelectedType) {
            this.onLetterTypeChange(preSelectedType);
        }
    }

    /**
     * Load user profile data (no longer used for auto-fill)
     */
    async loadUserProfile() {
        // Profile data no longer auto-fills form fields
        // All fields are now manual entry
        console.log('📝 Form uses manual entry only');
    }

    /**
     * Handle letter type change
     */
    async onLetterTypeChange(letterTypeId) {
        if (!letterTypeId) {
            this.clearForm();
            return;
        }

        this.currentLetterType = letterTypeId;
        this.showLoading();

        try {
            await this.loadFormConfiguration(letterTypeId);
            this.renderForm();
            this.hideLoading();
            console.log('📝 Form rendered for letter type:', letterTypeId);
        } catch (error) {
            console.error('❌ Error loading form configuration:', error);
            this.showError('Gagal memuat konfigurasi formulir');
            this.hideLoading();
        }
    }

    /**
     * Load form configuration from API
     */
    async loadFormConfiguration(letterTypeId) {
        const response = await fetch(`${this.options.apiBaseUrl}/letter-types/${letterTypeId}/fields`);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        this.formConfig = await response.json();
        console.log('⚙️ Form configuration loaded:', this.formConfig);
    }

    /**
     * Render the dynamic form (manual entry only)
     */
    renderForm() {
        if (!this.formConfig) return;

        const container = document.querySelector(this.options.formContainer);
        if (!container) return;

        container.innerHTML = '';

        // Render manual fields category only
        if (this.formConfig.field_categories.manual) {
            const category = this.formConfig.field_categories.manual;
            if (category.fields && category.fields.length > 0) {
                const categorySection = this.createCategorySection('manual', category);
                container.appendChild(categorySection);
            }
        }

        // Add form validation
        this.attachValidation();
    }

    /**
     * Create a category section
     */
    createCategorySection(categoryKey, category) {
        const section = document.createElement('div');
        section.className = `form-category category-${categoryKey} mb-8 p-6 bg-white rounded-lg shadow-sm border`;

        // Category header
        const header = document.createElement('div');
        header.className = 'category-header mb-4';
        header.innerHTML = `
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-${category.icon} mr-2 text-blue-600"></i>
                ${category.title}
            </h3>
            ${category.description ? `<p class="text-sm text-gray-600 mt-1">${category.description}</p>` : ''}
        `;
        section.appendChild(header);

        // Fields container
        const fieldsContainer = document.createElement('div');
        fieldsContainer.className = 'category-fields space-y-4';

        category.fields.forEach(field => {
            const fieldElement = this.createFieldElement(field, categoryKey);
            fieldsContainer.appendChild(fieldElement);
        });

        section.appendChild(fieldsContainer);
        return section;
    }

    /**
     * Create a field element
     */
    createFieldElement(field, category) {
        const fieldWrapper = document.createElement('div');
        fieldWrapper.className = 'field-wrapper';

        // Field label
        const label = document.createElement('label');
        label.className = 'block text-sm font-medium text-gray-700 mb-2';
        label.innerHTML = `
            ${field.label}
            ${field.required ? '<span class="text-red-500 ml-1">*</span>' : ''}
        `;

        // Field input container
        const inputContainer = document.createElement('div');
        inputContainer.className = 'relative';

        // Create input element based on type
        const input = this.createInputElement(field, category);

        // Add icon if specified
        if (field.icon) {
            const iconWrapper = document.createElement('div');
            iconWrapper.className = 'absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none';
            iconWrapper.innerHTML = `<i class="fas fa-${field.icon} text-gray-400"></i>`;
            inputContainer.appendChild(iconWrapper);
            input.classList.add('pl-10');
        }

        inputContainer.appendChild(input);

        // Validation message container
        const validationMsg = document.createElement('div');
        validationMsg.className = 'validation-message text-red-500 text-sm mt-1 hidden';

        // Assemble field
        fieldWrapper.appendChild(label);
        fieldWrapper.appendChild(inputContainer);
        fieldWrapper.appendChild(validationMsg);

        return fieldWrapper;
    }

    /**
     * Create input element based on field type
     */
    createInputElement(field, category) {
        let input;

        switch (field.type) {
            case 'textarea':
                input = document.createElement('textarea');
                input.rows = 3;
                break;

            case 'select':
                input = document.createElement('select');
                input.innerHTML = '<option value="">Pilih ' + field.label + '</option>';
                if (field.options) {
                    field.options.forEach(option => {
                        const optionEl = document.createElement('option');
                        optionEl.value = option;
                        optionEl.textContent = option;
                        input.appendChild(optionEl);
                    });
                }
                break;

            default:
                input = document.createElement('input');
                input.type = field.type || 'text';
                break;
        }

        // Common attributes (all manual entry, no readonly)
        input.name = field.name;
        input.id = `field_${field.name}`;
        input.className = `form-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white`;

        // Field-specific attributes
        if (field.placeholder) input.placeholder = field.placeholder;
        if (field.required) input.required = true;
        if (field.min !== undefined) input.min = field.min;
        if (field.max !== undefined) input.max = field.max;

        // No auto-fill - all manual entry

        return input;
    }

    /**
     * Attach form validation
     */
    attachValidation() {
        const form = document.querySelector(this.options.formSelector);
        if (!form) return;

        form.addEventListener('submit', (e) => {
            // Prevent multiple submissions
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && submitBtn.disabled) {
                e.preventDefault();
                return;
            }

            if (!this.validateForm()) {
                e.preventDefault();
                // Show error message
                this.showGlobalError('Mohon lengkapi semua field yang wajib diisi sebelum mengajukan surat.');
                return;
            }

            // Disable submit button to prevent double submission
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            }
        });

        // Real-time validation
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldValidation(input));
        });
    }

    /**
     * Validate entire form
     */
    validateForm() {
        let isValid = true;
        let firstInvalidField = null;
        const inputs = document.querySelectorAll(`${this.options.formSelector} input, ${this.options.formSelector} textarea, ${this.options.formSelector} select`);

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
                if (!firstInvalidField) {
                    firstInvalidField = input;
                }
            }
        });

        // Focus on first invalid field
        if (firstInvalidField && !isValid) {
            firstInvalidField.focus();
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        return isValid;
    }

    /**
     * Show global error message
     */
    showGlobalError(message) {
        // Remove existing global error
        const existingError = document.querySelector('.global-form-error');
        if (existingError) {
            existingError.remove();
        }

        // Create new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'global-form-error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        // Insert at top of form
        const form = document.querySelector(this.options.formSelector);
        if (form) {
            form.insertBefore(errorDiv, form.firstChild);
            // Scroll to error
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    /**
     * Validate individual field
     */
    validateField(input) {
        const fieldName = input.name;
        const value = input.value.trim();
        const validationMsg = input.closest('.field-wrapper').querySelector('.validation-message');

        // Clear previous validation
        this.clearFieldValidation(input);

        // Required field validation
        if (input.required && !value) {
            this.showFieldError(input, 'Field ini wajib diisi dan tidak boleh kosong');
            return false;
        }

        // Additional validation for specific field types
        if (input.name === 'nik' && input.required && value) {
            if (value.length !== 16 || !/^\d+$/.test(value)) {
                this.showFieldError(input, 'NIK harus 16 digit angka');
                return false;
            }
        }

        if (input.name === 'nama' && input.required && value) {
            if (value.length < 2) {
                this.showFieldError(input, 'Nama harus minimal 2 karakter');
                return false;
            }
        }

        // Type-specific validation
        switch (input.type) {
            case 'email':
                if (value && !this.isValidEmail(value)) {
                    this.showFieldError(input, 'Format email tidak valid');
                    return false;
                }
                break;

            case 'number':
                if (value && isNaN(value)) {
                    this.showFieldError(input, 'Harus berupa angka');
                    return false;
                }
                if (input.min && parseFloat(value) < parseFloat(input.min)) {
                    this.showFieldError(input, `Minimal nilai ${input.min}`);
                    return false;
                }
                if (input.max && parseFloat(value) > parseFloat(input.max)) {
                    this.showFieldError(input, `Maksimal nilai ${input.max}`);
                    return false;
                }
                break;
        }

        return true;
    }

    /**
     * Show field validation error
     */
    showFieldError(input, message) {
        const fieldWrapper = input.closest('.field-wrapper');
        const validationMsg = fieldWrapper.querySelector('.validation-message');

        fieldWrapper.classList.add('field-error');
        input.classList.add('border-red-500');
        validationMsg.textContent = message;
        validationMsg.classList.remove('hidden');
    }

    /**
     * Clear field validation
     */
    clearFieldValidation(input) {
        const fieldWrapper = input.closest('.field-wrapper');
        const validationMsg = fieldWrapper.querySelector('.validation-message');

        fieldWrapper.classList.remove('field-error');
        input.classList.remove('border-red-500');
        validationMsg.classList.add('hidden');
    }

    /**
     * Utility functions
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * Clear form
     */
    clearForm() {
        const container = document.querySelector(this.options.formContainer);
        if (container) {
            container.innerHTML = '<div class="text-center text-gray-500 py-8">Pilih jenis surat terlebih dahulu</div>';
        }
        this.formConfig = null;
        this.currentLetterType = null;
    }

    /**
     * Show loading state
     */
    showLoading() {
        const container = document.querySelector(this.options.formContainer);
        if (container) {
            container.classList.add(this.options.loadingClass);
            container.innerHTML = `
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-gray-600">Memuat formulir...</span>
                </div>
            `;
        }
    }

    /**
     * Hide loading state
     */
    hideLoading() {
        const container = document.querySelector(this.options.formContainer);
        if (container) {
            container.classList.remove(this.options.loadingClass);
        }
    }

    /**
     * Show error message
     */
    showError(message) {
        const container = document.querySelector(this.options.formContainer);
        if (container) {
            container.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Error</h3>
                            <div class="mt-2 text-sm text-red-700">${message}</div>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Get current form data
     */
    getFormData() {
        const form = document.querySelector(this.options.formSelector);
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        return data;
    }

    /**
     * Set form data (for editing)
     */
    setFormData(data) {
        Object.entries(data).forEach(([key, value]) => {
            const input = document.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = value;
            }
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Auto-initialize if container exists
    const formContainer = document.querySelector('#dynamic-form-container');
    if (formContainer) {
        window.dynamicFormBuilder = new DynamicFormBuilder();
    }
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DynamicFormBuilder;
}
