/**
 * Form Validation Utilities for PPKS Dinsos Application
 * Provides client-side validation for Indonesian format requirements
 */

class FormValidator {
    constructor(formId, options = {}) {
        this.form = document.getElementById(formId);
        this.options = {
            showErrorSummary: true,
            realTimeValidation: true,
            ...options
        };
        this.errors = {};

        if (this.form) {
            this.init();
        }
    }

    init() {
        // Add event listeners for real-time validation
        if (this.options.realTimeValidation) {
            this.form.addEventListener('input', (e) => this.handleInput(e));
            this.form.addEventListener('blur', (e) => this.handleBlur(e));
        }

        // Add form submission validation
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    handleInput(e) {
        const field = e.target;
        if (field && field.name) {
            this.validateField(field);
        }
    }

    handleBlur(e) {
        const field = e.target;
        if (field && field.name) {
            this.validateField(field);
        }
    }

    handleSubmit(e) {
        // Clear previous errors
        this.clearAllErrors();

        // Validate all fields
        const isValid = this.validateAll();

        if (!isValid) {
            e.preventDefault();
            this.showErrors();

            // Scroll to first error
            const firstErrorField = this.form.querySelector('.is-invalid');
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstErrorField.focus();
            }
        }

        return isValid;
    }

    validateField(field) {
        const fieldName = field.name;
        const value = field.value.trim();
        let error = null;

        // Get validation rules from data attributes
        const rules = this.getFieldRules(field);

        // Check each rule
        for (const rule of rules) {
            error = this.checkRule(value, rule, field);
            if (error) break;
        }

        // Update field UI
        this.updateFieldUI(field, error);

        // Store error
        if (error) {
            this.errors[fieldName] = error;
        } else {
            delete this.errors[fieldName];
        }

        return !error;
    }

    getFieldRules(field) {
        const rules = [];

        // Check data-validate attribute
        if (field.dataset.validate) {
            rules.push(...field.dataset.validate.split('|'));
        }

        // Check HTML5 attributes
        if (field.required) rules.push('required');
        if (field.maxLength) rules.push(`max:${field.maxLength}`);
        if (field.minLength) rules.push(`min:${field.minLength}`);
        if (field.type === 'email') rules.push('email');
        if (field.pattern) rules.push(`pattern:${field.pattern}`);

        // Check for specific field types
        if (field.classList.contains('nik-input')) rules.push('nik');
        if (field.classList.contains('nama-input')) rules.push('nama');
        if (field.classList.contains('alamat-input')) rules.push('alamat');
        if (field.classList.contains('indonesian-text')) rules.push('indonesian_text');

        return rules;
    }

    checkRule(value, rule, field) {
        const [ruleName, ...params] = rule.split(':');

        switch (ruleName) {
            case 'required':
                return this.validateRequired(value);
            case 'max':
                return this.validateMax(value, parseInt(params[0]));
            case 'min':
                return this.validateMin(value, parseInt(params[0]));
            case 'email':
                return this.validateEmail(value);
            case 'nik':
                return this.validateNIK(value);
            case 'nama':
                return this.validateNama(value);
            case 'alamat':
                return this.validateAlamat(value);
            case 'indonesian_text':
                return this.validateIndonesianText(value);
            case 'pattern':
                return this.validatePattern(value, params[0]);
            case 'date':
                return this.validateDate(value);
            case 'in':
                return this.validateIn(value, params);
            default:
                return null;
        }
    }

    validateRequired(value) {
        if (!value || value.length === 0) {
            return 'Field ini wajib diisi';
        }
        return null;
    }

    validateMax(value, max) {
        if (value.length > max) {
            return `Maksimal ${max} karakter`;
        }
        return null;
    }

    validateMin(value, min) {
        if (value.length > 0 && value.length < min) {
            return `Minimal ${min} karakter`;
        }
        return null;
    }

    validateEmail(value) {
        if (!value) return null;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(value)) {
            return 'Format email tidak valid';
        }
        return null;
    }

    validateNIK(value) {
        if (!value) return null;

        // Remove any non-digit characters
        const cleanValue = value.replace(/\D/g, '');

        if (cleanValue.length !== 16) {
            return 'NIK harus 16 digit angka';
        }

        if (!/^[0-9]{16}$/.test(cleanValue)) {
            return 'NIK hanya boleh berisi angka';
        }

        return null;
    }

    validateNama(value) {
        if (!value) return null;

        // Allow letters, spaces, hyphens, apostrophes, and periods
        const namaPattern = /^[a-zA-Z\s\-\.\']+$/;
        if (!namaPattern.test(value)) {
            return 'Nama hanya boleh berisi huruf, spasi, tanda hubung (-), titik (.), dan apostrof (\')';
        }

        if (value.length < 2) {
            return 'Nama minimal 2 karakter';
        }

        return null;
    }

    validateAlamat(value) {
        if (!value) return null;

        // Allow letters, numbers, spaces, common punctuation
        const alamatPattern = /^[a-zA-Z0-9\s\-\.\,\#\:\;\(\)\/]+$/;
        if (!alamatPattern.test(value)) {
            return 'Alamat mengandung karakter tidak valid';
        }

        if (value.length < 5) {
            return 'Alamat minimal 5 karakter';
        }

        return null;
    }

    validateIndonesianText(value) {
        if (!value) return null;

        // Allow Indonesian text characters including common punctuation
        const indonesianPattern = /^[a-zA-Z\s\-\.\,\:\;\(\)\/\&\@\!\?]+$/;
        if (!indonesianPattern.test(value)) {
            return 'Format teks tidak valid';
        }

        return null;
    }

    validatePattern(value, pattern) {
        if (!value) return null;

        try {
            const regex = new RegExp(pattern);
            if (!regex.test(value)) {
                return 'Format tidak valid';
            }
        } catch (e) {
            console.error('Invalid regex pattern:', pattern);
        }

        return null;
    }

    validateDate(value) {
        if (!value) return null;

        const date = new Date(value);
        if (isNaN(date.getTime())) {
            return 'Tanggal tidak valid';
        }

        return null;
    }

    validateIn(value, allowedValues) {
        if (!value) return null;

        if (!allowedValues.includes(value)) {
            return 'Nilai tidak valid';
        }

        return null;
    }

    validateAll() {
        let isValid = true;
        const fields = this.form.querySelectorAll('input, select, textarea');

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    updateFieldUI(field, error) {
        // Find or create error container
        let errorContainer = field.parentNode.querySelector('.invalid-feedback');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.className = 'invalid-feedback';
            field.parentNode.appendChild(errorContainer);
        }

        if (error) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            errorContainer.textContent = error;
            errorContainer.style.display = 'block';
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            errorContainer.textContent = '';
            errorContainer.style.display = 'none';
        }
    }

    clearAllErrors() {
        this.errors = {};
        const fields = this.form.querySelectorAll('input, select, textarea');

        fields.forEach(field => {
            field.classList.remove('is-invalid', 'is-valid');
            const errorContainer = field.parentNode.querySelector('.invalid-feedback');
            if (errorContainer) {
                errorContainer.textContent = '';
                errorContainer.style.display = 'none';
            }
        });
    }

    showErrors() {
        if (this.options.showErrorSummary && Object.keys(this.errors).length > 0) {
            const errorMessages = Object.values(this.errors);
            const errorHtml = errorMessages.map(msg => `• ${msg}`).join('<br>');

            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                html: 'Mohon perbaiki kesalahan berikut:<br><br>' + errorHtml,
                confirmButtonText: 'OK',
                confirmButtonColor: '#f6c23e'
            });
        }
    }

    // Static method to initialize all forms with validation
    static initializeAll() {
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            const formId = form.id || `form-${Math.random().toString(36).substr(2, 9)}`;
            if (!form.id) form.id = formId;

            new FormValidator(formId, {
                showErrorSummary: true,
                realTimeValidation: true
            });
        });
    }
}

// NIK Input Handler for automatic formatting
class NIKInputHandler {
    constructor(inputId) {
        this.input = document.getElementById(inputId);
        if (this.input) {
            this.init();
        }
    }

    init() {
        this.input.addEventListener('input', (e) => {
            // Remove non-digit characters
            let value = e.target.value.replace(/\D/g, '');

            // Limit to 16 digits
            if (value.length > 16) {
                value = value.substring(0, 16);
            }

            // Update value
            e.target.value = value;

            // Add visual feedback
            if (value.length > 0 && value.length < 16) {
                e.target.classList.add('is-invalid');
                this.showError(e.target, 'NIK harus 16 digit angka');
            } else if (value.length === 16) {
                e.target.classList.remove('is-invalid');
                e.target.classList.add('is-valid');
                this.clearError(e.target);
            } else {
                e.target.classList.remove('is-invalid', 'is-valid');
                this.clearError(e.target);
            }
        });
    }

    showError(input, message) {
        let errorContainer = input.parentNode.querySelector('.invalid-feedback');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.className = 'invalid-feedback';
            input.parentNode.appendChild(errorContainer);
        }
        errorContainer.textContent = message;
        errorContainer.style.display = 'block';
    }

    clearError(input) {
        const errorContainer = input.parentNode.querySelector('.invalid-feedback');
        if (errorContainer) {
            errorContainer.textContent = '';
            errorContainer.style.display = 'none';
        }
    }
}

// Initialize validation when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    // Initialize all forms with data-validate attribute
    FormValidator.initializeAll();

    // Initialize NIK inputs
    const nikInputs = document.querySelectorAll('.nik-input');
    nikInputs.forEach(input => {
        if (input.id) {
            new NIKInputHandler(input.id);
        }
    });
});

// Export for global access
window.FormValidator = FormValidator;
window.NIKInputHandler = NIKInputHandler;