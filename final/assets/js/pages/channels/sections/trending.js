document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.trending-container');
    const cards = document.querySelectorAll('.trending-card');
    const prevBtn = document.querySelector('.control-btn.prev');
    const nextBtn = document.querySelector('.control-btn.next');

    // Initialize
    updateControls();

    // Event Listeners
    if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            container.scrollBy({ left: -320, behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', () => {
            container.scrollBy({ left: 320, behavior: 'smooth' });
        });
    }

    // Update control buttons visibility
    container.addEventListener('scroll', () => {
        updateControls();
    });

    function updateControls() {
        if (prevBtn && nextBtn) {
            const scrollLeft = container.scrollLeft;
            const maxScroll = container.scrollWidth - container.clientWidth;

            prevBtn.style.opacity = scrollLeft > 0 ? '1' : '0';
            nextBtn.style.opacity = scrollLeft < maxScroll ? '1' : '0';
        }
    }

    // Touch scroll for mobile
    let isDown = false;
    let startX;
    let scrollLeft;

    container.addEventListener('mousedown', (e) => {
        isDown = true;
        container.classList.add('active');
        startX = e.pageX - container.offsetLeft;
        scrollLeft = container.scrollLeft;
    });

    container.addEventListener('mouseleave', () => {
        isDown = false;
        container.classList.remove('active');
    });

    container.addEventListener('mouseup', () => {
        isDown = false;
        container.classList.remove('active');
    });

    container.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - container.offsetLeft;
        const walk = (x - startX) * 2;
        container.scrollLeft = scrollLeft - walk;
    });
});