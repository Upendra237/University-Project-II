// File: assets/js/pages/channels/forms/channel-form.js

class ChannelForm {
    constructor() {
        this.form = document.getElementById('channelForm');
        this.templateSelect = this.form.querySelector('[name="template_type"]');
        this.optionsContainer = this.form.querySelector('.display-options-container');
        this.metadataContainer = this.form.querySelector('[data-fields-container]');
        
        this.setupEventListeners();
        this.initializeForm();
    }

    setupEventListeners() {
        // Template type change
        this.templateSelect?.addEventListener('change', () => {
            this.updateTemplateOptions();
        });

        // Add metadata field
        document.getElementById('addMetadataField')?.addEventListener('click', () => {
            this.addMetadataField();
        });

        // Remove metadata field
        this.metadataContainer?.addEventListener('click', (e) => {
            if (e.target.closest('.remove-field')) {
                e.target.closest('.metadata-field-row').remove();
            }
        });

        // Form submission
        this.form?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSubmit();
        });
    }

    initializeForm() {
        if (this.templateSelect?.value) {
            this.updateTemplateOptions();
        }
    }

    updateTemplateOptions() {
        const templateType = this.templateSelect.value;
        const template = document.getElementById(`${templateType}OptionsTemplate`);
        
        if (template) {
            this.optionsContainer.innerHTML = template.innerHTML;
        } else {
            this.optionsContainer.innerHTML = '<p class="text-muted">No specific options for this template type.</p>';
        }
    }

    addMetadataField() {
        const index = this.metadataContainer.children.length;
        const fieldHtml = `
            <div class="metadata-field-row">
                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" 
                               name="metadata_fields[${index}][name]"
                               placeholder="Field name" required>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="metadata_fields[${index}][type]">
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="date">Date</option>
                            <option value="select">Select</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" 
                               name="metadata_fields[${index}][options]"
                               placeholder="Options (comma-separated)">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-field">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        this.metadataContainer.insertAdjacentHTML('beforeend', fieldHtml);
    }

    async handleSubmit() {
        try {
            const formData = new FormData(this.form);
            
            // Show loading state
            const submitButton = this.form.querySelector('[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';
            submitButton.disabled = true;

            const response = await fetch(this.form.action, {
                method: this.form.method,
                body: formData
            });

            if (!response.ok) {
                throw new Error('Failed to save channel');
            }

            const result = await response.json();
            
            // Show success message
            window.channelsManager.showNotification('Channel saved successfully', 'success');
            
            // Redirect to channel view
            window.location.href = `/channels/view.php?id=${result.channelId}`;

        } catch (error) {
            console.error('Form submission error:', error);
            window.channelsManager.showNotification('Failed to save channel', 'error');
            
            // Reset submit button
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }
    }
}

// Initialize form
document.addEventListener('DOMContentLoaded', () => {
    new ChannelForm();
});
