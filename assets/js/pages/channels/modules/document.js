// File: assets/js/pages/channels/modules/document.js

class DocumentHandler {
    constructor() {
        this.viewer = null;
        this.currentDocument = null;
        this.initializeViewer();
        this.setupEventListeners();
    }

    initializeViewer() {
        // Create document viewer modal
        if (!document.getElementById('documentViewerModal')) {
            const viewerTemplate = `
                <div class="modal fade document-viewer-modal" id="documentViewerModal" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="d-flex align-items-center w-100">
                                    <h5 class="modal-title flex-grow-1"></h5>
                                    <div class="viewer-controls me-3">
                                        <button class="btn btn-light btn-sm" id="zoomOut">
                                            <i class="bi bi-zoom-out"></i>
                                        </button>
                                        <span id="zoomLevel">100%</span>
                                        <button class="btn btn-light btn-sm" id="zoomIn">
                                            <i class="bi bi-zoom-in"></i>
                                        </button>
                                    </div>
                                    <div class="page-controls me-3">
                                        <button class="btn btn-light btn-sm" id="prevPage">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                        <span id="pageInfo">Page 1 of 1</span>
                                        <button class="btn btn-light btn-sm" id="nextPage">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                            </div>
                            <div class="modal-body p-0">
                                <div class="document-viewer-wrapper">
                                    <div id="documentViewer"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="document-info flex-grow-1"></div>
                                <div class="document-actions">
                                    <button class="btn btn-light btn-sm" id="fullscreenBtn">
                                        <i class="bi bi-fullscreen"></i>
                                    </button>
                                    <button class="btn btn-light btn-sm" id="downloadBtn">
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                    <button class="btn btn-light btn-sm" id="shareBtn">
                                        <i class="bi bi-share"></i> Share
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', viewerTemplate);
        }

        // Initialize PDF.js viewer
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';
    }

    setupEventListeners() {
        // Document click handler
        document.addEventListener('click', (e) => {
            const documentItem = e.target.closest('.document-item');
            if (documentItem) {
                const previewButton = e.target.closest('.preview-document');
                if (previewButton) {
                    e.preventDefault();
                    this.previewDocument(documentItem.dataset);
                }
            }
        });

        // Viewer controls
        const modal = document.getElementById('documentViewerModal');
        if (modal) {
            modal.querySelector('#zoomIn').addEventListener('click', () => this.zoom('in'));
            modal.querySelector('#zoomOut').addEventListener('click', () => this.zoom('out'));
            modal.querySelector('#prevPage').addEventListener('click', () => this.changePage('prev'));
            modal.querySelector('#nextPage').addEventListener('click', () => this.changePage('next'));
            modal.querySelector('#fullscreenBtn').addEventListener('click', () => this.toggleFullscreen());
            modal.querySelector('#downloadBtn').addEventListener('click', () => this.downloadDocument());
            modal.querySelector('#shareBtn').addEventListener('click', () => this.shareDocument());

            // Keyboard navigation
            modal.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') this.changePage('prev');
                if (e.key === 'ArrowRight') this.changePage('next');
                if (e.key === '+') this.zoom('in');
                if (e.key === '-') this.zoom('out');
            });
        }
    }

    // Continue with more methods...
// File: assets/js/pages/channels/modules/document.js (continued)

    async previewDocument(documentData) {
        this.currentDocument = documentData;
        const modal = document.getElementById('documentViewerModal');
        const viewer = modal.querySelector('#documentViewer');

        // Update modal content
        modal.querySelector('.modal-title').textContent = documentData.title;
        modal.querySelector('.document-info').innerHTML = this.formatDocumentInfo(documentData);

        try {
            // Show loading state
            viewer.innerHTML = '<div class="document-loading"><div class="spinner-border"></div></div>';

            // Load document based on type
            switch (documentData.fileType) {
                case 'pdf':
                    await this.loadPDF(documentData.fileUrl, viewer);
                    break;
                case 'doc':
                case 'docx':
                    await this.loadWord(documentData.fileUrl, viewer);
                    break;
                case 'ppt':
                case 'pptx':
                    await this.loadPresentation(documentData.fileUrl, viewer);
                    break;
                default:
                    throw new Error('Unsupported file type');
            }

            // Show modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();

            // Track view
            this.trackView(documentData.id);

        } catch (error) {
            console.error('Preview error:', error);
            window.channelsManager.showNotification('Failed to load document preview', 'error');
        }
    }

    async loadPDF(url, container) {
        try {
            const loadingTask = pdfjsLib.getDocument(url);
            this.pdfDoc = await loadingTask.promise;
            this.currentPage = 1;
            this.zoom = 1.0;

            await this.renderPage(container);
            this.updatePageInfo();
        } catch (error) {
            throw new Error('Failed to load PDF');
        }
    }

    async renderPage(container) {
        try {
            const page = await this.pdfDoc.getPage(this.currentPage);
            const viewport = page.getViewport({ scale: this.zoom });

            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            container.innerHTML = '';
            container.appendChild(canvas);

            await page.render(renderContext).promise;
        } catch (error) {
            throw new Error('Failed to render page');
        }
    }

    async loadWord(url, container) {
        try {
            const response = await fetch(`/api/channels/document/convert-to-html`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ url })
            });

            if (!response.ok) throw new Error('Conversion failed');
            
            const html = await response.text();
            container.innerHTML = html;
        } catch (error) {
            throw new Error('Failed to load Word document');
        }
    }

    async loadPresentation(url, container) {
        try {
            const response = await fetch(`/api/channels/document/slides-to-images`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ url })
            });

            if (!response.ok) throw new Error('Conversion failed');
            
            const slides = await response.json();
            this.slides = slides;
            this.currentSlide = 0;
            
            await this.renderSlide(container);
        } catch (error) {
            throw new Error('Failed to load presentation');
        }
    }

    formatDocumentInfo(documentData) {
        const metadata = JSON.parse(documentData.metadata || '{}');
        return `
            <div class="document-metadata">
                <div class="metadata-item">
                    <i class="bi bi-file-earmark"></i> 
                    ${this.formatFileSize(documentData.fileSize)}
                </div>
                ${metadata.author ? `
                    <div class="metadata-item">
                        <i class="bi bi-person"></i> 
                        ${metadata.author}
                    </div>
                ` : ''}
                ${metadata.lastModified ? `
                    <div class="metadata-item">
                        <i class="bi bi-calendar"></i> 
                        ${new Date(metadata.lastModified).toLocaleDateString()}
                    </div>
                ` : ''}
                <div class="metadata-item">
                    <i class="bi bi-eye"></i> 
                    ${documentData.views} views
                </div>
            </div>
        `;
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    changePage(direction) {
        if (this.pdfDoc) {
            const newPage = direction === 'next' ? 
                Math.min(this.currentPage + 1, this.pdfDoc.numPages) : 
                Math.max(this.currentPage - 1, 1);

            if (newPage !== this.currentPage) {
                this.currentPage = newPage;
                this.renderPage(document.getElementById('documentViewer'));
                this.updatePageInfo();
            }
        }
    }

    updatePageInfo() {
        const pageInfo = document.getElementById('pageInfo');
        if (pageInfo && this.pdfDoc) {
            pageInfo.textContent = `Page ${this.currentPage} of ${this.pdfDoc.numPages}`;
        }
    }

    zoom(direction) {
        const ZOOM_STEP = 0.25;
        const MIN_ZOOM = 0.5;
        const MAX_ZOOM = 3;

        this.zoom = direction === 'in' ? 
            Math.min(this.zoom + ZOOM_STEP, MAX_ZOOM) : 
            Math.max(this.zoom - ZOOM_STEP, MIN_ZOOM);

        document.getElementById('zoomLevel').textContent = `${Math.round(this.zoom * 100)}%`;
        this.renderPage(document.getElementById('documentViewer'));
    }

    toggleFullscreen() {
        const viewer = document.querySelector('.document-viewer-wrapper');
        if (!document.fullscreenElement) {
            viewer.requestFullscreen().catch(console.error);
        } else {
            document.exitFullscreen();
        }
    }

    async downloadDocument() {
        try {
            const response = await fetch(`/api/channels/document/download/${this.currentDocument.id}`);
            if (!response.ok) throw new Error('Download failed');

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = this.currentDocument.title;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            // Track download
            this.trackDownload(this.currentDocument.id);
        } catch (error) {
            console.error('Download error:', error);
            window.channelsManager.showNotification('Failed to download document', 'error');
        }
    }

    shareDocument() {
        const shareUrl = `${window.location.origin}/document/${this.currentDocument.id}`;
        if (navigator.share) {
            navigator.share({
                title: this.currentDocument.title,
                url: shareUrl
            }).catch(console.error);
        } else {
            navigator.clipboard.writeText(shareUrl)
                .then(() => window.channelsManager.showNotification('Link copied to clipboard', 'success'))
                .catch(() => window.channelsManager.showNotification('Failed to copy link', 'error'));
        }
    }

    // API calls
    async trackView(documentId) {
        try {
            await fetch(`/api/channels/document/view/${documentId}`, { method: 'POST' });
        } catch (error) {
            console.error('Failed to track view:', error);
        }
    }

    async trackDownload(documentId) {
        try {
            await fetch(`/api/channels/document/download/${documentId}/track`, { method: 'POST' });
        } catch (error) {
            console.error('Failed to track download:', error);
        }
    }
}

// Initialize document handler
document.addEventListener('DOMContentLoaded', () => {
    window.documentHandler = new DocumentHandler();
});
