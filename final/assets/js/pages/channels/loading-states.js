// File: assets/js/pages/channels/loading-states.js

class LoadingStateManager {
    constructor() {
        this.skeletonTemplates = {
            gallery: this.getGallerySkeleton(),
            document: this.getDocumentSkeleton(),
            book: this.getBookSkeleton(),
            video: this.getVideoSkeleton()
        };
    }

    showLoading(container, type = 'gallery', count = 6) {
        const skeletonHTML = Array(count)
            .fill(this.skeletonTemplates[type])
            .join('');
        
        container.innerHTML = skeletonHTML;
        container.classList.add('loading');
    }

    hideLoading(container) {
        container.classList.remove('loading');
    }

    getGallerySkeleton() {
        return `
            <div class="skeleton-card">
                <div class="skeleton-image pulse"></div>
                <div class="skeleton-content">
                    <div class="skeleton-title pulse"></div>
                    <div class="skeleton-text pulse"></div>
                    <div class="skeleton-meta">
                        <div class="skeleton-badge pulse"></div>
                        <div class="skeleton-badge pulse"></div>
                    </div>
                </div>
            </div>
        `;
    }

    getDocumentSkeleton() {
        return `
            <div class="skeleton-document">
                <div class="skeleton-icon pulse"></div>
                <div class="skeleton-content">
                    <div class="skeleton-title pulse"></div>
                    <div class="skeleton-text pulse"></div>
                    <div class="skeleton-meta">
                        <div class="skeleton-badge pulse"></div>
                        <div class="skeleton-stats pulse"></div>
                    </div>
                </div>
            </div>
        `;
    }

    getBookSkeleton() {
        return `
            <div class="skeleton-book">
                <div class="skeleton-cover pulse"></div>
                <div class="skeleton-content">
                    <div class="skeleton-title pulse"></div>
                    <div class="skeleton-author pulse"></div>
                    <div class="skeleton-text pulse"></div>
                </div>
            </div>
        `;
    }

    getVideoSkeleton() {
        return `
            <div class="skeleton-video">
                <div class="skeleton-thumbnail pulse"></div>
                <div class="skeleton-content">
                    <div class="skeleton-title pulse"></div>
                    <div class="skeleton-meta">
                        <div class="skeleton-duration pulse"></div>
                        <div class="skeleton-views pulse"></div>
                    </div>
                </div>
            </div>
        `;
    }
}

// Initialize loading state manager
window.loadingManager = new LoadingStateManager();
