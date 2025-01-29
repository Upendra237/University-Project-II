// File: assets/js/pages/channels/search.js

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('channelSearch');
    let searchTimeout;

    // Debounced search
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchQuery = e.target.value.trim();
            updateSearchResults(searchQuery);
        }, 300);
    });

    // Update search results
    function updateSearchResults(query) {
        const currentUrl = new URL(window.location);
        if (query) {
            currentUrl.searchParams.set('search', query);
        } else {
            currentUrl.searchParams.delete('search');
        }
        window.history.pushState({}, '', currentUrl);
        refreshContent();
    }

    // Refresh content based on filters
    function refreshContent() {
        // Add loading state
        document.querySelector('.channels-main').classList.add('loading');
        
        // Fetch and update content
        const searchParams = new URLSearchParams(window.location.search);
        fetch(`/api/channels/search?${searchParams.toString()}`)
            .then(response => response.json())
            .then(data => {
                // Update content sections
                updateSections(data);
            })
            .finally(() => {
                document.querySelector('.channels-main').classList.remove('loading');
            });
    }
});