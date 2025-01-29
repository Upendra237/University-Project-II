document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('projectForm');
    const formInputs = form.querySelectorAll('input, textarea, select');

    // Add interaction tracker class to form
    form.classList.add('needs-validation');

    // Remove any initial validation classes
    formInputs.forEach(input => {
        input.classList.remove('is-invalid', 'is-valid');
    });

    // Real-time validation only after user interaction
    formInputs.forEach(input => {
        // Only start validating after first interaction
        input.addEventListener('input', function() {
            if (!this.dataset.touched) {
                this.dataset.touched = 'true';
            }
            if (this.dataset.touched === 'true') {
                validateInput(this);
            }
        });

        // Validate on blur (when input loses focus)
        input.addEventListener('blur', function() {
            this.dataset.touched = 'true';
            validateInput(this);
        });
    });

    function validateInput(input) {
        // Only validate if the field is required or has a value
        if (input.hasAttribute('required') || input.value.trim() !== '') {
            // Remove any existing validation classes first
            input.classList.remove('is-invalid', 'is-valid');
            
            // Special validation for each type
            let isValid = input.checkValidity();
            
            // Additional custom validations
            if (isValid) {
                switch(input.id) {
                    case 'title':
                        isValid = input.value.trim().length >= 5;
                        break;
                    case 'description':
                        isValid = input.value.trim().length >= 50;
                        break;
                    case 'team_members':
                        isValid = input.value.trim().includes(',') || input.value.trim().length > 0;
                        break;
                    case 'project_type':
                    case 'department':
                        isValid = input.value !== 'Select Project Type' && 
                                input.value !== 'Select Department' &&
                                input.value.trim() !== '';
                        break;
                }
            }

            // Only add validation classes if field has been interacted with
            if (input.dataset.touched === 'true') {
                if (isValid) {
                    input.classList.add('is-valid');
                } else if (input.value.trim() !== '' || input.hasAttribute('required')) {
                    input.classList.add('is-invalid');
                }
            }
        }
    }

    // Form submission
    form.addEventListener('submit', function(event) {
        // If this is draft save, don't validate
        if (event.submitter?.name === 'save_draft') {
            return true;
        }

        // Mark all fields as touched for validation
        formInputs.forEach(input => {
            input.dataset.touched = 'true';
            validateInput(input);
        });

        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();

            // Find and focus first invalid field
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                
                // Switch to correct tab if needed
                const tabPane = firstInvalid.closest('.tab-pane');
                if (tabPane) {
                    const tabId = tabPane.id;
                    const tab = document.querySelector(`[data-bs-target="#${tabId}"]`);
                    bootstrap.Tab.getOrCreateInstance(tab).show();
                }
            }
        }
    });

    // Tab switching functionality
    function toggleTabs() {
        const currentTab = document.querySelector('.nav-link.active');
        const tabs = Array.from(document.querySelectorAll('.nav-link'));
        const currentIndex = tabs.indexOf(currentTab);
        const nextIndex = (currentIndex + 1) % tabs.length;
        
        bootstrap.Tab.getOrCreateInstance(tabs[nextIndex]).show();
    }

    // Make toggleTabs available globally
    window.toggleTabs = toggleTabs;

    // Tab change event handler to save state
    const tabElements = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabElements.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (event) {
            const targetId = event.target.getAttribute('data-bs-target').replace('#', '');
            fetch('save-tab-state.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `tab=${targetId}`
            });
        });
    });

    // Add custom styles for validation states
    const style = document.createElement('style');
    style.textContent = `
        .form-control:not(.is-invalid):not(.is-valid),
        .form-select:not(.is-invalid):not(.is-valid) {
            background-image: none;
        }
        
        .form-control.is-valid,
        .form-select.is-valid {
            border-color: #198754;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            padding-right: calc(1.5em + 0.75rem);
            background-repeat: no-repeat;
            background-position: center right calc(0.375em + 0.1875rem);
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            padding-right: calc(1.5em + 0.75rem);
            background-repeat: no-repeat;
            background-position: center right calc(0.375em + 0.1875rem);
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    `;
    document.head.appendChild(style);
});