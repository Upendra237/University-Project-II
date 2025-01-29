// Channels Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializeChannels();
    initializeFilters();
    initializeSearch();
    initializeAnimations();
    initializeLazyLoading();
});

// Channel Cards Management
const initializeChannels = () => {
    // Channel hover effects
    const channelCards = document.querySelectorAll('.channel-card');
    channelCards.forEach(card => {
        card.addEventListener('mouseenter', (e) => {
            const stats = card.querySelector('.channel-stats');
            if (stats) {
                stats.style.height = `${stats.scrollHeight}px`;
            }
        });

        card.addEventListener('mouseleave', (e) => {
            const stats = card.querySelector('.channel-stats');
            if (stats) {
                stats.style.height = '0';
            }
        });
    });

    // Initialize masonry layout for gallery type
    const galleries = document.querySelectorAll('.gallery-grid');
    galleries.forEach(gallery => {
        new Masonry(gallery, {
            itemSelector: '.gallery-item',
            columnWidth: '.gallery-item',
            percentPosition: true
        });
    });
};

// Filter Management
const initializeFilters = () => {
    const typeFilter = document.getElementById('typeFilter');
    const departmentFilter = document.getElementById('departmentFilter');
    const sortFilter = document.getElementById('sortFilter');
    
    const filters = [typeFilter, departmentFilter, sortFilter];
    
    filters.forEach(filter => {
        if (filter) {
            // Add animation class on focus
            filter.addEventListener('focus', () => {
                filter.classList.add('filter-active');
            });

            // Remove animation class on blur
            filter.addEventListener('blur', () => {
                filter.classList.remove('filter-active');
            });

            // Handle filter changes
            filter.addEventListener('change', () => {
                updateChannels();
                updateURL();
            });
        }
    });
};

// Search Functionality
const initializeSearch = () => {
    const searchInput = document.querySelector('.search-input');
    const searchButton = document.querySelector('.search-button');
    let searchTimeout;

    if (searchInput) {
        // Real-time search with debouncing
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = e.target.value.trim();
                filterChannels(searchTerm);
            }, 300);
        });

        // Clear search
        const clearButton = document.querySelector('.clear-search');
        if (clearButton) {
            clearButton.addEventListener('click', () => {
                searchInput.value = '';
                filterChannels('');
            });
        }
    }

    if (searchButton) {
        searchButton.addEventListener('click', (e) => {
            e.preventDefault();
            const searchTerm = searchInput.value.trim();
            filterChannels(searchTerm);
        });
    }
};

// Channel Filtering Logic
const filterChannels = (searchTerm) => {
    const channels = document.querySelectorAll('.channel-card');
    const noResults = document.querySelector('.no-results');
    let hasResults = false;

    channels.forEach(channel => {
        const title = channel.querySelector('.channel-title').textContent.toLowerCase();
        const description = channel.querySelector('.channel-description').textContent.toLowerCase();
        const type = channel.dataset.type.toLowerCase();
        const department = channel.dataset.department.toLowerCase();

        const matches = title.includes(searchTerm.toLowerCase()) || 
                       description.includes(searchTerm.toLowerCase()) ||
                       type.includes(searchTerm.toLowerCase()) ||
                       department.includes(searchTerm.toLowerCase());

        if (matches) {
            channel.style.display = 'block';
            hasResults = true;
        } else {
            channel.style.display = 'none';
        }
    });

    // Toggle no results message
    if (noResults) {
        noResults.style.display = hasResults ? 'none' : 'block';
    }
};

// Animation Initialization
const initializeAnimations = () => {
    // Initialize AOS (Animate On Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Add smooth reveal animation to channel cards
    const cards = document.querySelectorAll('.channel-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });
};

// Lazy Loading Implementation
const initializeLazyLoading = () => {
    const images = document.querySelectorAll('.lazy-load');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy-load');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
};

// URL Management
const updateURL = () => {
    const type = document.getElementById('typeFilter')?.value || '';
    const department = document.getElementById('departmentFilter')?.value || '';
    const sort = document.getElementById('sortFilter')?.value || '';
    const search = document.querySelector('.search-input')?.value || '';

    const params = new URLSearchParams(window.location.search);
    
    if (type) params.set('type', type);
    if (department) params.set('department', department);
    if (sort) params.set('sort', sort);
    if (search) params.set('search', search);

    const newURL = `${window.location.pathname}?${params.toString()}`;
    history.pushState({}, '', newURL);
};

// Update Channels Display
const updateChannels = () => {
    const type = document.getElementById('typeFilter')?.value;
    const department = document.getElementById('departmentFilter')?.value;
    const sort = document.getElementById('sortFilter')?.value;

    const channels = document.querySelectorAll('.channel-card');
    let visibleChannels = Array.from(channels);

    // Filter by type
    if (type) {
        visibleChannels = visibleChannels.filter(channel => 
            channel.dataset.type === type
        );
    }

    // Filter by department
    if (department) {
        visibleChannels = visibleChannels.filter(channel => 
            channel.dataset.department === department
        );
    }

    // Apply sorting
    if (sort) {
        visibleChannels.sort((a, b) => {
            switch (sort) {
                case 'name-asc':
                    return a.querySelector('.channel-title').textContent
                           .localeCompare(b.querySelector('.channel-title').textContent);
                case 'name-desc':
                    return b.querySelector('.channel-title').textContent
                           .localeCompare(a.querySelector('.channel-title').textContent);
                case 'items-desc':
                    return parseInt(b.dataset.items) - parseInt(a.dataset.items);
                case 'views-desc':
                    return parseInt(b.dataset.views) - parseInt(a.dataset.views);
                default:
                    return 0;
            }
        });
    }

    // Update visibility
    channels.forEach(channel => {
        channel.style.display = 'none';
    });

    visibleChannels.forEach(channel => {
        channel.style.display = 'block';
    });

    // Reinitialize masonry if needed
    const galleries = document.querySelectorAll('.gallery-grid');
    galleries.forEach(gallery => {
        if (gallery.masonry) {
            gallery.masonry.layout();
        }
    });
};

// File: assets/js/pages/channels/channels.js

class ChannelsManager {
    constructor() {
        this.initializeScroll();
        this.initializeFilters();
        this.setupSearchDebounce();
    }

    // Initialize horizontal scroll functionality
    initializeScroll() {
        document.querySelectorAll('.channels-row').forEach(row => {
            const scroll = row.querySelector('.channels-scroll');
            const leftBtn = row.querySelector('.scroll-left');
            const rightBtn = row.querySelector('.scroll-right');

            if (leftBtn && rightBtn) {
                leftBtn.addEventListener('click', () => {
                    scroll.scrollBy({ left: -600, behavior: 'smooth' });
                });

                rightBtn.addEventListener('click', () => {
                    scroll.scrollBy({ left: 600, behavior: 'smooth' });
                });

                // Update button visibility based on scroll position
                scroll.addEventListener('scroll', () => {
                    const canScrollLeft = scroll.scrollLeft > 0;
                    const canScrollRight = scroll.scrollLeft < scroll.scrollWidth - scroll.clientWidth;

                    leftBtn.style.opacity = canScrollLeft ? '1' : '0';
                    rightBtn.style.opacity = canScrollRight ? '1' : '0';
                });
            }
        });
    }

    // Initialize filter functionality
    initializeFilters() {
        const filterForm = document.getElementById('advancedFilterForm');
        if (!filterForm) return;

        // Handle filter changes
        filterForm.addEventListener('change', (e) => {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            
            // Update URL with filter params
            const url = new URL(window.location);
            for (const [key, value] of params) {
                if (value) {
                    url.searchParams.set(key, value);
                } else {
                    url.searchParams.delete(key);
                }
            }
            
            window.history.pushState({}, '', url);
            this.updateContent(params);
        });
    }

    // Setup search with debouncing
    setupSearchDebounce() {
        const searchInput = document.getElementById('channelSearch');
        if (!searchInput) return;

        let timeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const params = new URLSearchParams(window.location.search);
                params.set('search', e.target.value);
                this.updateContent(params);
            }, 300);
        });
    }

    // Update content based on filters
    async updateContent(params) {
        try {
            const response = await fetch(`/api/channels/filter?${params.toString()}`);
            const data = await response.json();
            
            // Update sections
            document.querySelectorAll('[data-section]').forEach(section => {
                const sectionType = section.dataset.section;
                if (data[sectionType]) {
                    this.updateSection(section, data[sectionType]);
                }
            });
        } catch (error) {
            console.error('Error updating content:', error);
        }
    }

    // Update individual section content
    updateSection(section, data) {
        const container = section.querySelector('.channels-scroll');
        if (!container) return;

        container.innerHTML = data.map(channel => this.renderChannelCard(channel)).join('');
    }

    // Render individual channel card
    renderChannelCard(channel) {
        return `
            <div class="channel-card channel-card-${channel.template_type}">
                <!-- Card HTML structure -->
            </div>
        `;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    window.channelsManager = new ChannelsManager();
});
// File: assets/js/pages/channels/channels.js

class ChannelsManager {
    constructor() {
        this.initializeComponents();
        this.setupEventListeners();
    }

    initializeComponents() {
        this.searchBar = document.getElementById('contentSearch');
        this.filterForm = document.getElementById('contentFilterForm');
        this.viewToggle = document.querySelectorAll('[data-view]');
        this.contentContainer = document.querySelector('.content-grid');
        
        // Initialize tooltips and popovers
        this.initializeBootstrapComponents();
    }

    initializeBootstrapComponents() {
        // Initialize all tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

        // Initialize all popovers
        const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
        popovers.forEach(popover => new bootstrap.Popover(popover));
    }

    setupEventListeners() {
        // Search functionality
        if (this.searchBar) {
            this.setupSearch();
        }

        // View toggle
        this.viewToggle.forEach(button => {
            button.addEventListener('click', () => this.toggleView(button.dataset.view));
        });

        // Filter form
        if (this.filterForm) {
            this.setupFilters();
        }

        // Content type specific handlers
        this.setupContentTypeHandlers();
    }

    setupSearch() {
        let debounceTimeout;
        this.searchBar.addEventListener('input', (e) => {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                this.handleSearch(e.target.value);
            }, 300);
        });
    }

    async handleSearch(query) {
        try {
            const params = new URLSearchParams(window.location.search);
            params.set('search', query);
            
            // Update URL without reload
            window.history.pushState({}, '', `?${params.toString()}`);
            
            // Fetch and update content
            await this.updateContent(params);
        } catch (error) {
            console.error('Search error:', error);
            this.showNotification('Search failed. Please try again.', 'error');
        }
    }

    setupFilters() {
        this.filterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.applyFilters();
        });

        // Handle filter changes
        this.filterForm.addEventListener('change', (e) => {
            if (e.target.hasAttribute('data-instant-filter')) {
                this.applyFilters();
            }
        });
    }

    async applyFilters() {
        try {
            const formData = new FormData(this.filterForm);
            const params = new URLSearchParams(formData);
            
            // Update URL
            window.history.pushState({}, '', `?${params.toString()}`);
            
            // Update content
            await this.updateContent(params);
            
            // Close filter modal if open
            const filterModal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
            if (filterModal) {
                filterModal.hide();
            }
        } catch (error) {
            console.error('Filter error:', error);
            this.showNotification('Failed to apply filters. Please try again.', 'error');
        }
    }

    async updateContent(params) {
        this.showLoader();
        try {
            const response = await fetch(`/api/channels/content?${params.toString()}`);
            if (!response.ok) throw new Error('Failed to fetch content');
            
            const data = await response.json();
            this.renderContent(data);
        } catch (error) {
            console.error('Content update error:', error);
            this.showNotification('Failed to update content. Please try again.', 'error');
        } finally {
            this.hideLoader();
        }
    }

    renderContent(data) {
        if (!this.contentContainer) return;
        
        if (data.items.length === 0) {
            this.contentContainer.innerHTML = this.getEmptyStateHTML();
            return;
        }

        const templateType = this.contentContainer.dataset.templateType;
        const viewType = this.getCurrentView();
        
        this.contentContainer.innerHTML = data.items.map(item => 
            this.getContentItemHTML(item, templateType, viewType)
        ).join('');

        // Reinitialize components
        this.initializeBootstrapComponents();
        this.setupContentTypeHandlers();
    }

    setupContentTypeHandlers() {
        // Gallery handlers
        this.setupGalleryHandlers();
        
        // Video handlers
        this.setupVideoHandlers();
        
        // Document handlers
        this.setupDocumentHandlers();
        
        // Book handlers
        this.setupBookHandlers();
    }

    setupGalleryHandlers() {
        const galleryItems = document.querySelectorAll('.gallery-item');
        galleryItems.forEach(item => {
            item.querySelector('.view-image')?.addEventListener('click', (e) => {
                const data = e.currentTarget.dataset;
                this.showImageViewer(data);
            });
        });
    }

    setupVideoHandlers() {
        const videoItems = document.querySelectorAll('.video-item');
        videoItems.forEach(item => {
            item.querySelector('.play-video')?.addEventListener('click', (e) => {
                const data = e.currentTarget.dataset;
                this.playVideo(data);
            });
        });
    }

    setupDocumentHandlers() {
        const documentItems = document.querySelectorAll('.document-item');
        documentItems.forEach(item => {
            item.querySelector('.preview-document')?.addEventListener('click', (e) => {
                const data = e.currentTarget.dataset;
                this.previewDocument(data);
            });
        });
    }

    setupBookHandlers() {
        const bookItems = document.querySelectorAll('.book-item');
        bookItems.forEach(item => {
            item.querySelector('.preview-book')?.addEventListener('click', (e) => {
                const data = e.currentTarget.dataset;
                this.previewBook(data);
            });
        });
    }

    showLoader() {
        if (!this.loader) {
            this.loader = document.createElement('div');
            this.loader.className = 'content-loader';
            this.loader.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;
        }
        this.contentContainer.appendChild(this.loader);
    }

    hideLoader() {
        this.loader?.remove();
    }

    showNotification(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type}`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
    }

    getCurrentView() {
        return localStorage.getItem('channelView') || 'grid';
    }

    toggleView(viewType) {
        localStorage.setItem('channelView', viewType);
        this.contentContainer.dataset.view = viewType;
        
        this.viewToggle.forEach(button => {
            button.classList.toggle('active', button.dataset.view === viewType);
        });
    }
}

// Initialize the channels manager
document.addEventListener('DOMContentLoaded', () => {
    window.channelsManager = new ChannelsManager();
});
