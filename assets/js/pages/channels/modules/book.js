// File: assets/js/pages/channels/modules/book.js

class BookHandler {
    constructor() {
        this.reader = null;
        this.currentBook = null;
        this.bookmarks = new Set();
        this.lastReadPosition = {};
        this.initializeReader();
        this.setupEventListeners();
    }

    initializeReader() {
        if (!document.getElementById('bookReaderModal')) {
            const readerTemplate = `
                <div class="modal fade book-reader-modal" id="bookReaderModal" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-fullscreen-lg-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="reader-controls d-flex align-items-center w-100">
                                    <button class="btn btn-light btn-sm me-2" id="toggleSidebar">
                                        <i class="bi bi-list"></i>
                                    </button>
                                    <h5 class="modal-title flex-grow-1 mb-0"></h5>
                                    <div class="reader-actions">
                                        <button class="btn btn-light btn-sm" id="toggleBookmark">
                                            <i class="bi bi-bookmark"></i>
                                        </button>
                                        <div class="btn-group mx-2">
                                            <button class="btn btn-light btn-sm" id="decreaseFont">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <button class="btn btn-light btn-sm" id="increaseFont">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <button class="btn btn-light btn-sm" id="toggleTheme">
                                            <i class="bi bi-moon"></i>
                                        </button>
                                        <button type="button" class="btn-close ms-2" data-bs-dismiss="modal"></button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body p-0">
                                <div class="reader-container">
                                    <!-- Sidebar -->
                                    <div class="reader-sidebar">
                                        <div class="sidebar-tabs">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" data-bs-toggle="tab" 
                                                            data-bs-target="#contents">Contents</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" data-bs-toggle="tab" 
                                                            data-bs-target="#bookmarks">Bookmarks</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="contents">
                                                    <div class="toc-container"></div>
                                                </div>
                                                <div class="tab-pane fade" id="bookmarks">
                                                    <div class="bookmarks-container"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Reader View -->
                                    <div class="reader-view">
                                        <div id="bookContent"></div>
                                        <div class="reader-navigation">
                                            <button class="nav-prev" title="Previous Page">
                                                <i class="bi bi-chevron-left"></i>
                                            </button>
                                            <button class="nav-next" title="Next Page">
                                                <i class="bi bi-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="reading-progress">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar"></div>
                                    </div>
                                    <span class="progress-text">0% read</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', readerTemplate);
        }
    }

    setupEventListeners() {
        // Book click handler
        document.addEventListener('click', (e) => {
            const bookItem = e.target.closest('.book-item');
            if (bookItem) {
                const readButton = e.target.closest('.read-book');
                if (readButton) {
                    e.preventDefault();
                    this.openBook(bookItem.dataset);
                }
            }
        });

        // Reader controls
        const modal = document.getElementById('bookReaderModal');
        if (modal) {
            // Navigation
            modal.querySelector('.nav-prev').addEventListener('click', () => this.navigate('prev'));
            modal.querySelector('.nav-next').addEventListener('click', () => this.navigate('next'));

            // Font size
            modal.querySelector('#decreaseFont').addEventListener('click', () => this.adjustFontSize('decrease'));
            modal.querySelector('#increaseFont').addEventListener('click', () => this.adjustFontSize('increase'));

            // Theme toggle
            modal.querySelector('#toggleTheme').addEventListener('click', () => this.toggleTheme());

            // Bookmark toggle
            modal.querySelector('#toggleBookmark').addEventListener('click', () => this.toggleBookmark());

            // Sidebar toggle
            modal.querySelector('#toggleSidebar').addEventListener('click', () => this.toggleSidebar());

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (!modal.classList.contains('show')) return;
                
                switch(e.key) {
                    case 'ArrowLeft':
                        this.navigate('prev');
                        break;
                    case 'ArrowRight':
                        this.navigate('next');
                        break;
                    case 'b':
                        if (e.ctrlKey) this.toggleBookmark();
                        break;
                }
            });

            // Save progress on close
            modal.addEventListener('hide.bs.modal', () => {
                if (this.currentBook) {
                    this.saveReadingProgress();
                }
            });
        }
    }

    // Continue with more methods...
// File: assets/js/pages/channels/modules/book.js (continued)

    async openBook(bookData) {
        this.currentBook = bookData;
        const modal = document.getElementById('bookReaderModal');
        const contentContainer = modal.querySelector('#bookContent');

        try {
            // Show loading state
            contentContainer.innerHTML = '<div class="book-loading"><div class="spinner-border"></div></div>';

            // Load book data
            const book = await this.loadBook(bookData.id);
            
            // Update UI
            modal.querySelector('.modal-title').textContent = book.title;
            this.renderTableOfContents(book.toc);
            this.loadBookmarks();
            
            // Restore last reading position
            this.lastReadPosition = await this.getReadingProgress(bookData.id);
            
            // Initialize reader based on book type
            switch(book.format) {
                case 'epub':
                    await this.initializeEpubReader(book.url);
                    break;
                case 'pdf':
                    await this.initializePdfReader(book.url);
                    break;
                default:
                    throw new Error('Unsupported book format');
            }

            // Show modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();

            // Track view
            this.trackView(bookData.id);

        } catch (error) {
            console.error('Book loading error:', error);
            window.channelsManager.showNotification('Failed to load book', 'error');
        }
    }

    async loadBook(bookId) {
        const response = await fetch(`/api/channels/book/${bookId}`);
        if (!response.ok) throw new Error('Failed to load book data');
        return await response.json();
    }

    async initializeEpubReader(url) {
        // Initialize epub.js reader
        this.reader = ePub(url);
        const rendition = this.reader.renderTo('bookContent', {
            width: '100%',
            height: '100%',
            spread: 'none'
        });

        // Set up rendition event handlers
        rendition.on('rendered', () => {
            this.updateReadingProgress();
        });

        rendition.on('relocated', (location) => {
            this.updateProgress(location);
            this.saveLocation(location);
        });

        // Load saved position if exists
        if (this.lastReadPosition.location) {
            rendition.display(this.lastReadPosition.location);
        } else {
            rendition.display();
        }

        // Apply saved settings
        this.applyUserSettings();
    }

    async initializePdfReader(url) {
        // Initialize PDF.js viewer
        const loadingTask = pdfjsLib.getDocument(url);
        this.pdfDoc = await loadingTask.promise;
        
        // Load saved position or start from beginning
        this.currentPage = this.lastReadPosition.page || 1;
        await this.renderPdfPage();
    }

    async renderPdfPage() {
        const page = await this.pdfDoc.getPage(this.currentPage);
        const viewport = page.getViewport({ scale: this.currentScale });
        
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderContext = {
            canvasContext: context,
            viewport: viewport
        };

        document.getElementById('bookContent').innerHTML = '';
        document.getElementById('bookContent').appendChild(canvas);

        await page.render(renderContext).promise;
        this.updateReadingProgress();
    }

    renderTableOfContents(toc) {
        const container = document.querySelector('.toc-container');
        container.innerHTML = this.generateTocHtml(toc);

        // Add click handlers
        container.querySelectorAll('.toc-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const href = item.dataset.href;
                this.navigateToSection(href);
            });
        });
    }

    generateTocHtml(items, level = 0) {
        if (!items?.length) return '';
        
        return `
            <ul class="toc-list level-${level}">
                ${items.map(item => `
                    <li class="toc-item" data-href="${item.href}">
                        <span class="toc-link">${item.label}</span>
                        ${this.generateTocHtml(item.subitems, level + 1)}
                    </li>
                `).join('')}
            </ul>
        `;
    }

    navigate(direction) {
        if (this.reader) {
            if (direction === 'prev') {
                this.reader.prev();
            } else {
                this.reader.next();
            }
        } else if (this.pdfDoc) {
            const newPage = direction === 'prev' ? 
                Math.max(this.currentPage - 1, 1) : 
                Math.min(this.currentPage + 1, this.pdfDoc.numPages);
            
            if (newPage !== this.currentPage) {
                this.currentPage = newPage;
                this.renderPdfPage();
            }
        }
    }

    navigateToSection(href) {
        if (this.reader) {
            this.reader.display(href);
        }
    }

    async toggleBookmark() {
        const currentLocation = await this.getCurrentLocation();
        const bookmarkKey = `${this.currentBook.id}-${currentLocation}`;

        if (this.bookmarks.has(bookmarkKey)) {
            this.bookmarks.delete(bookmarkKey);
            await this.removeBookmark(bookmarkKey);
        } else {
            this.bookmarks.add(bookmarkKey);
            await this.saveBookmark(currentLocation);
        }

        this.updateBookmarkButton();
        this.renderBookmarks();
    }

    async saveBookmark(location) {
        try {
            await fetch(`/api/channels/book/bookmark/${this.currentBook.id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    location,
                    title: await this.getCurrentSectionTitle()
                })
            });
        } catch (error) {
            console.error('Failed to save bookmark:', error);
        }
    }

    async loadBookmarks() {
        try {
            const response = await fetch(`/api/channels/book/bookmarks/${this.currentBook.id}`);
            const bookmarks = await response.json();
            this.bookmarks = new Set(bookmarks.map(b => `${this.currentBook.id}-${b.location}`));
            this.renderBookmarks();
        } catch (error) {
            console.error('Failed to load bookmarks:', error);
        }
    }

    async saveReadingProgress() {
        try {
            const location = await this.getCurrentLocation();
            const progress = await this.getProgress();

            await fetch(`/api/channels/book/progress/${this.currentBook.id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ location, progress })
            });
        } catch (error) {
            console.error('Failed to save reading progress:', error);
        }
    }

    adjustFontSize(action) {
        const size = parseInt(localStorage.getItem('bookFontSize')) || 100;
        const newSize = action === 'increase' ? size + 10 : size - 10;
        localStorage.setItem('bookFontSize', newSize);
        this.applyFontSize(newSize);
    }

    applyFontSize(size) {
        const content = document.getElementById('bookContent');
        content.style.fontSize = `${size}%`;
    }

    toggleTheme() {
        const currentTheme = localStorage.getItem('bookTheme') || 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        localStorage.setItem('bookTheme', newTheme);
        this.applyTheme(newTheme);
    }

    applyTheme(theme) {
        const content = document.getElementById('bookContent');
        content.dataset.theme = theme;
        
        const themeButton = document.getElementById('toggleTheme');
        themeButton.innerHTML = `<i class="bi bi-${theme === 'light' ? 'moon' : 'sun'}"></i>`;
    }

    // API tracking methods
    async trackView(bookId) {
        try {
            await fetch(`/api/channels/book/view/${bookId}`, { method: 'POST' });
        } catch (error) {
            console.error('Failed to track view:', error);
        }
    }
}

// Initialize book handler
document.addEventListener('DOMContentLoaded', () => {
    window.bookHandler = new BookHandler();
});
