// File: assets/js/pages/channels/mobile-nav.js

class MobileNavigation {
    constructor() {
        this.sidebarVisible = false;
        this.lastScrollTop = 0;
        this.initializeComponents();
        this.setupEventListeners();
    }

    initializeComponents() {
        // Create mobile navigation toggle button if it doesn't exist
        if (!document.querySelector('.mobile-nav-toggle')) {
            const toggleButton = `
                <button class="mobile-nav-toggle d-lg-none" aria-label="Toggle Navigation">
                    <i class="bi bi-list"></i>
                </button>
            `;
            document.querySelector('.channels-search').insertAdjacentHTML('afterbegin', toggleButton);
        }

        // Add mobile navigation overlay
        if (!document.querySelector('.mobile-nav-overlay')) {
            const overlay = document.createElement('div');
            overlay.className = 'mobile-nav-overlay';
            document.body.appendChild(overlay);
        }
    }

    setupEventListeners() {
        // Toggle button click
        document.querySelector('.mobile-nav-toggle').addEventListener('click', () => {
            this.toggleSidebar();
        });

        // Overlay click
        document.querySelector('.mobile-nav-overlay').addEventListener('click', () => {
            this.closeSidebar();
        });

        // Handle scroll for hiding/showing search bar
        this.setupScrollBehavior();

        // Handle swipe gestures
        this.setupSwipeHandler();

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992 && this.sidebarVisible) {
                this.closeSidebar();
            }
        });
    }

    toggleSidebar() {
        const sidebar = document.querySelector('.channels-sidebar');
        const overlay = document.querySelector('.mobile-nav-overlay');
        const toggleBtn = document.querySelector('.mobile-nav-toggle');

        if (this.sidebarVisible) {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            toggleBtn.classList.remove('active');
            document.body.classList.remove('sidebar-open');
        } else {
            sidebar.classList.add('show');
            overlay.classList.add('show');
            toggleBtn.classList.add('active');
            document.body.classList.add('sidebar-open');
        }

        this.sidebarVisible = !this.sidebarVisible;
    }

    closeSidebar() {
        if (this.sidebarVisible) {
            this.toggleSidebar();
        }
    }

    setupScrollBehavior() {
        const searchBar = document.querySelector('.channels-search');
        let lastScroll = 0;
        let scrollTimeout;

        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);

            scrollTimeout = setTimeout(() => {
                const currentScroll = window.pageYOffset;
                
                if (currentScroll > lastScroll && currentScroll > 100) {
                    // Scrolling down - hide search bar
                    searchBar.classList.add('search-hidden');
                } else {
                    // Scrolling up - show search bar
                    searchBar.classList.remove('search-hidden');
                }

                lastScroll = currentScroll;
            }, 100);
        });
    }

    setupSwipeHandler() {
        let touchStartX = 0;
        let touchEndX = 0;
        const minSwipeDistance = 50;

        document.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
        }, false);

        document.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].clientX;
            this.handleSwipe();
        }, false);

        this.handleSwipe = () => {
            const swipeDistance = touchEndX - touchStartX;

            if (Math.abs(swipeDistance) > minSwipeDistance) {
                if (swipeDistance > 0 && !this.sidebarVisible) {
                    // Swipe right - open sidebar
                    this.toggleSidebar();
                } else if (swipeDistance < 0 && this.sidebarVisible) {
                    // Swipe left - close sidebar
                    this.closeSidebar();
                }
            }
        };
    }
}

// Initialize mobile navigation
document.addEventListener('DOMContentLoaded', () => {
    window.mobileNav = new MobileNavigation();
});
