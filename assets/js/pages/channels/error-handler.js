// File: assets/js/pages/channels/error-handler.js

class ErrorHandler {
    constructor() {
        this.setupGlobalErrorHandling();
    }

    setupGlobalErrorHandling() {
        // Handle all fetch errors
        window.addEventListener('unhandledrejection', (event) => {
            if (event.reason instanceof Response) {
                this.handleFetchError(event.reason);
            } else {
                this.showError('An unexpected error occurred');
            }
        });

        // Handle general JavaScript errors
        window.addEventListener('error', (event) => {
            this.handleJavaScriptError(event);
        });
    }

    handleFetchError(response) {
        switch (response.status) {
            case 401:
                this.showError('Please login to continue', 'auth');
                // Redirect to login if needed
                break;
            case 403:
                this.showError('You don\'t have permission to access this resource');
                break;
            case 404:
                this.showError('The requested resource was not found');
                break;
            case 429:
                this.showError('Too many requests. Please try again later');
                break;
            case 500:
                this.showError('Server error. Please try again later');
                break;
            default:
                this.showError('An error occurred while fetching data');
        }
    }

    handleJavaScriptError(event) {
        console.error('JavaScript Error:', event.error);
        // Only show user-facing error if it's not a script loading error
        if (!event.filename?.includes('.js')) {
            this.showError('An error occurred while processing your request');
        }
    }

    showError(message, type = 'error') {
        // Create error toast
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : 'warning'} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                        data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        // Add to toast container or create one if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        toastContainer.appendChild(toast);

        // Initialize and show toast
        const bsToast = new bootstrap.Toast(toast, {
            delay: 5000,
            animation: true
        });
        bsToast.show();

        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Specific error handlers for different components
    handleContentLoadError() {
        this.showError('Failed to load content. Please try again');
    }

    handleUploadError() {
        this.showError('Failed to upload file. Please try again');
    }

    handleDownloadError() {
        this.showError('Failed to download file. Please try again');
    }

    handlePlaybackError() {
        this.showError('Failed to play media. Please try again');
    }

    handleSaveError() {
        this.showError('Failed to save changes. Please try again');
    }
}

// Initialize error handler
document.addEventListener('DOMContentLoaded', () => {
    window.errorHandler = new ErrorHandler();
});
