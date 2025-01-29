document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // Add hover animations for explore links
    const exploreLinks = document.querySelectorAll('.explore-link');
    exploreLinks.forEach(link => {
        link.addEventListener('mouseenter', () => {
            link.querySelector('i').style.transform = 'translateX(8px)';
        });
        
        link.addEventListener('mouseleave', () => {
            link.querySelector('i').style.transform = 'translateX(0)';
        });
    });

    // Optional: Add load more functionality
    const viewMoreBtn = document.querySelector('.view-more-btn');
    if (viewMoreBtn) {
        viewMoreBtn.addEventListener('click', (e) => {
            // Animate the arrow on click
            const arrow = viewMoreBtn.querySelector('i');
            arrow.style.transform = 'translateX(4px)';
            setTimeout(() => {
                arrow.style.transform = 'translateX(0)';
            }, 200);
        });
    }

    // Add image lazy loading
    const images = document.querySelectorAll('.card-img');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;

                    // Load actual image from data-src
                    const actualSrc = img.dataset.src;
                    if (actualSrc) {
                        img.src = actualSrc;

                        // Ensure the onerror attribute is preserved if loading fails
                        img.onerror = function() {
                            this.onerror = null;
                            this.src = '<?php echo ROOT_URL; ?>assets/images/channels/default-cover.jpg';
                        };
                    }

                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }
});
