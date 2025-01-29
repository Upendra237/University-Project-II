// File: assets/js/pages/channels/modules/gallery.js

class GalleryHandler {
    constructor() {
        this.lightbox = null;
        this.initializeLightbox();
        this.setupEventListeners();
    }

    initializeLightbox() {
        // Create lightbox modal if it doesn't exist
        if (!document.getElementById('galleryLightbox')) {
            const lightboxTemplate = `
                <div class="modal fade gallery-lightbox" id="galleryLightbox" tabindex="-1">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title text-white"></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body d-flex align-items-center justify-content-center">
                                <div class="lightbox-content position-relative">
                                    <img src="" alt="" class="img-fluid">
                                    <button class="btn btn-prev"><i class="bi bi-chevron-left"></i></button>
                                    <button class="btn btn-next"><i class="bi bi-chevron-right"></i></button>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <div class="image-metadata text-white"></div>
                                <div class="image-actions">
                                    <button class="btn btn-light btn-sm download-btn">
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                    <button class="btn btn-light btn-sm share-btn">
                                        <i class="bi bi-share"></i> Share
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', lightboxTemplate);
        }

        this.lightbox = new bootstrap.Modal(document.getElementById('galleryLightbox'));
    }

    setupEventListeners() {
        document.addEventListener('click', (e) => {
            const galleryItem = e.target.closest('.gallery-item');
            if (galleryItem) {
                const viewButton = e.target.closest('.view-image');
                if (viewButton) {
                    e.preventDefault();
                    this.openLightbox(galleryItem.dataset);
                }
            }
        });

        // Lightbox navigation
        const lightboxElement = document.getElementById('galleryLightbox');
        if (lightboxElement) {
            lightboxElement.querySelector('.btn-prev').addEventListener('click', () => this.navigate('prev'));
            lightboxElement.querySelector('.btn-next').addEventListener('click', () => this.navigate('next'));
            lightboxElement.querySelector('.download-btn').addEventListener('click', () => this.downloadImage());
            lightboxElement.querySelector('.share-btn').addEventListener('click', () => this.shareImage());

            // Keyboard navigation
            lightboxElement.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') this.navigate('prev');
                if (e.key === 'ArrowRight') this.navigate('next');
                if (e.key === 'Escape') this.lightbox.hide();
            });
        }

        // Touch support
        this.setupTouchSupport();
    }

    setupTouchSupport() {
        const lightboxContent = document.querySelector('.lightbox-content');
        if (!lightboxContent) return;

        let touchStartX = 0;
        let touchEndX = 0;

        lightboxContent.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        lightboxContent.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe();
        });

        const handleSwipe = () => {
            const swipeThreshold = 50;
            const swipeLength = touchEndX - touchStartX;

            if (Math.abs(swipeLength) > swipeThreshold) {
                if (swipeLength > 0) {
                    this.navigate('prev');
                } else {
                    this.navigate('next');
                }
            }
        };
    }

    openLightbox(data) {
        const modal = document.getElementById('galleryLightbox');
        const image = modal.querySelector('img');
        const title = modal.querySelector('.modal-title');
        const metadata = modal.querySelector('.image-metadata');

        // Set image and title
        image.src = data.imageSrc;
        title.textContent = data.title;

        // Set metadata
        if (data.metadata) {
            const meta = JSON.parse(data.metadata);
            metadata.innerHTML = this.formatMetadata(meta);
        }

        // Store current image data
        this.currentImageId = data.id;
        this.currentGalleryId = data.galleryId;

        // Show lightbox
        this.lightbox.show();

        // Track view
        this.trackView(data.id);
    }

    formatMetadata(metadata) {
        return Object.entries(metadata)
            .map(([key, value]) => `
                <div class="metadata-item">
                    <span class="metadata-label">${key.replace('_', ' ')}:</span>
                    <span class="metadata-value">${value}</span>
                </div>
            `).join('');
    }

    async navigate(direction) {
        try {
            const response = await fetch(`/api/channels/gallery/navigate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    currentId: this.currentImageId,
                    galleryId: this.currentGalleryId,
                    direction: direction
                })
            });

            if (!response.ok) throw new Error('Navigation failed');

            const data = await response.json();
            if (data.image) {
                this.openLightbox(data.image);
            }
        } catch (error) {
            console.error('Navigation error:', error);
            // Show error notification
            window.channelsManager.showNotification('Failed to navigate images', 'error');
        }
    }

    async downloadImage() {
        try {
            const response = await fetch(`/api/channels/gallery/download/${this.currentImageId}`);
            if (!response.ok) throw new Error('Download failed');

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `image-${this.currentImageId}.jpg`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            // Track download
            this.trackDownload(this.currentImageId);
        } catch (error) {
            console.error('Download error:', error);
            window.channelsManager.showNotification('Failed to download image', 'error');
        }
    }

    shareImage() {
        const modal = document.getElementById('galleryLightbox');
        const shareUrl = `${window.location.origin}/gallery/image/${this.currentImageId}`;
        const title = modal.querySelector('.modal-title').textContent;

        if (navigator.share) {
            navigator.share({
                title: title,
                url: shareUrl
            }).catch(console.error);
        } else {
            // Fallback to copy to clipboard
            navigator.clipboard.writeText(shareUrl)
                .then(() => window.channelsManager.showNotification('Link copied to clipboard', 'success'))
                .catch(() => window.channelsManager.showNotification('Failed to copy link', 'error'));
        }
    }

    async trackView(imageId) {
        try {
            await fetch(`/api/channels/gallery/view/${imageId}`, { method: 'POST' });
        } catch (error) {
            console.error('Failed to track view:', error);
        }
    }

    async trackDownload(imageId) {
        try {
            await fetch(`/api/channels/gallery/download/${imageId}/track`, { method: 'POST' });
        } catch (error) {
            console.error('Failed to track download:', error);
        }
    }
}

// Initialize gallery handler
document.addEventListener('DOMContentLoaded', () => {
    window.galleryHandler = new GalleryHandler();
});
