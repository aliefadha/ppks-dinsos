/**
 * Form Handler Utilities for PPKS Dinsos Application
 * Provides loading states and form submission handling
 */

class FormHandler {
    constructor(formId, options = {}) {
        this.form = document.getElementById(formId);
        this.options = {
            showLoading: true,
            loadingText: 'Memproses...',
            confirmBeforeSubmit: false,
            confirmMessage: 'Apakah Anda yakin ingin mengirim data ini?',
            ...options
        };

        if (this.form) {
            this.init();
        }
    }

    init() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    handleSubmit(e) {
        // Skip if form is already submitting
        if (this.form.dataset.submitting === 'true') {
            e.preventDefault();
            return false;
        }

        // Show confirmation dialog if required
        if (this.options.confirmBeforeSubmit) {
            e.preventDefault();
            this.showConfirmation().then(confirmed => {
                if (confirmed) {
                    this.submitForm();
                }
            });
            return false;
        }

        // Show loading state and submit
        this.showLoadingState();
        return true;
    }

    showConfirmation() {
        return Swal.fire({
            title: 'Konfirmasi',
            text: this.options.confirmMessage,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4e73df',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal'
        }).then((result) => {
            return result.isConfirmed;
        });
    }

    showLoadingState() {
        if (!this.options.showLoading) return;

        const submitBtn = this.form.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        // Store original content
        submitBtn.dataset.originalText = submitBtn.innerHTML;

        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${this.options.loadingText}`;

        // Mark form as submitting
        this.form.dataset.submitting = 'true';

        // Add timeout to re-enable in case of errors
        setTimeout(() => {
            this.resetLoadingState();
        }, 30000); // 30 seconds timeout
    }

    resetLoadingState() {
        const submitBtn = this.form.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        // Restore original content if available
        if (submitBtn.dataset.originalText) {
            submitBtn.innerHTML = submitBtn.dataset.originalText;
        }

        // Re-enable button
        submitBtn.disabled = false;

        // Remove submitting flag
        delete this.form.dataset.submitting;
    }

    // Static method to initialize all forms with form handling
    static initializeAll() {
        const forms = document.querySelectorAll('form[data-form-handler]');
        forms.forEach(form => {
            const formId = form.id || `form-${Math.random().toString(36).substr(2, 9)}`;
            if (!form.id) form.id = formId;

            const options = {
                showLoading: form.dataset.showLoading !== 'false',
                loadingText: form.dataset.loadingText || 'Memproses...',
                confirmBeforeSubmit: form.dataset.confirm === 'true',
                confirmMessage: form.dataset.confirmMessage || 'Apakah Anda yakin ingin mengirim data ini?'
            };

            new FormHandler(formId, options);
        });
    }
}

// Auto-save functionality for forms
class AutoSave {
    constructor(formId, options = {}) {
        this.form = document.getElementById(formId);
        this.options = {
            enabled: true,
            interval: 30000, // 30 seconds
            storageKey: `autosave_${formId}`,
            ...options
        };

        if (this.form && this.options.enabled) {
            this.init();
        }
    }

    init() {
        // Load saved data
        this.loadSavedData();

        // Save data on input changes
        this.form.addEventListener('input', () => this.saveData());

        // Clear saved data on successful submission
        this.form.addEventListener('submit', () => this.clearSavedData());

        // Set up periodic saving
        setInterval(() => this.saveData(), this.options.interval);
    }

    saveData() {
        const formData = new FormData(this.form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        localStorage.setItem(this.options.storageKey, JSON.stringify(data));
        this.showAutoSaveIndicator();
    }

    loadSavedData() {
        const savedData = localStorage.getItem(this.options.storageKey);
        if (!savedData) return;

        try {
            const data = JSON.parse(savedData);

            // Fill form fields with saved data
            Object.keys(data).forEach(key => {
                const field = this.form.querySelector(`[name="${key}"]`);
                if (field) {
                    field.value = data[key];
                    // Trigger change event for any listeners
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });

            this.showRestoreIndicator();
        } catch (e) {
            console.error('Error loading saved data:', e);
        }
    }

    clearSavedData() {
        localStorage.removeItem(this.options.storageKey);
    }

    showAutoSaveIndicator() {
        // Create or update auto-save indicator
        let indicator = document.getElementById('autosave-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'autosave-indicator';
            indicator.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #28a745;
                color: white;
                padding: 8px 16px;
                border-radius: 4px;
                font-size: 14px;
                z-index: 9999;
                opacity: 0;
                transition: opacity 0.3s;
            `;
            document.body.appendChild(indicator);
        }

        indicator.textContent = 'Data tersimpan otomatis';
        indicator.style.opacity = '1';

        setTimeout(() => {
            indicator.style.opacity = '0';
        }, 2000);
    }

    showRestoreIndicator() {
        Swal.fire({
            title: 'Data Tersimpan',
            text: 'Data form sebelumnya telah ditemukan. Apakah Anda ingin memulihkannya?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Pulihkan',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (!result.isConfirmed) {
                this.clearSavedData();
            }
        });
    }
}

// Initialize form handlers when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    // Initialize all forms with data-form-handler attribute
    FormHandler.initializeAll();

    // Initialize auto-save for forms with data-autosave attribute
    const autosaveForms = document.querySelectorAll('form[data-autosave]');
    autosaveForms.forEach(form => {
        const formId = form.id || `form-${Math.random().toString(36).substr(2, 9)}`;
        if (!form.id) form.id = formId;

        new AutoSave(formId, {
            enabled: form.dataset.autosave !== 'false',
            interval: parseInt(form.dataset.autosaveInterval) || 30000
        });
    });
});

// Export for global access
window.FormHandler = FormHandler;
window.AutoSave = AutoSave;