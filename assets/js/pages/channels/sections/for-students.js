// assets/js/pages/channels/sections/for-students.js

document.addEventListener('DOMContentLoaded', function() {
    // Simple hover effects only - removing AOS temporarily
    const resourceLinks = document.querySelectorAll('.resource-link');
    if (resourceLinks.length > 0) {
        resourceLinks.forEach(link => {
            if (link) {
                const icon = link.querySelector('i');
                if (icon) {
                    link.addEventListener('mouseenter', () => {
                        icon.style.transform = 'translateX(8px)';
                    });
                    link.addEventListener('mouseleave', () => {
                        icon.style.transform = 'translateX(0)';
                    });
                }
            }
        });
    }
});

function animateCount(element, finalCount) {
    if (!element || !finalCount) return;

    try {
        const duration = 1500;
        const start = 0;
        const end = parseInt(finalCount);
        const startTime = performance.now();

        function updateCount(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            const current = Math.round(start + (end - start) * progress);
            element.textContent = `${current}+`;

            if (progress < 1) {
                requestAnimationFrame(updateCount);
            }
        }

        requestAnimationFrame(updateCount);
    } catch (error) {
        console.error('Error in animateCount:', error);
        // Fallback: just show the final number
        element.textContent = finalCount;
    }
}