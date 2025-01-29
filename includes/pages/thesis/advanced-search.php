<div class="card">
    <div class="card-body">
        <h4 class="card-title">Advanced Search</h4>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="advancedSearchForm">
            <!-- Search Box -->
            <div class="mb-3">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search" 
                       value="<?php echo htmlspecialchars($searchQuery); ?>" 
                       placeholder="Search titles, authors, keywords...">
            </div>

            <!-- Department Filter -->
            <div class="mb-3">
                <label class="form-label">Department</label>
                <select class="form-select" name="department">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo htmlspecialchars($dept); ?>" 
                                <?php echo $selectedDepartment === $dept ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dept); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Year Filter -->
            <div class="mb-3">
                <label class="form-label">Year</label>
                <select class="form-select" name="year">
                    <option value="">All Years</option>
                    <?php foreach ($years as $year): ?>
                        <option value="<?php echo $year; ?>" 
                                <?php echo $selectedYear == $year ? 'selected' : ''; ?>>
                            <?php echo $year; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Author Filter with Search Icon -->
            <div class="mb-3">
                <label class="form-label">Author</label>
                <div class="input-group">
                    <select class="form-select" name="author" id="authorDropdown">
                        <option value="">All Authors</option>
                        <?php foreach ($authors as $author): ?>
                            <option value="<?php echo htmlspecialchars($author); ?>" 
                                    <?php echo $selectedAuthor === $author ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($author); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-outline-secondary" id="authorSearchBtn">
                        <i class="bi bi-search"></i> <!-- Bootstrap icon for search -->
                    </button>
                </div>
                <!-- Hidden Search Input Field for Author Autocomplete -->
                <div id="authorSearchContainer" class="mt-2" style="display: none;">
                    <input type="text" id="authorSearchInput" class="form-control" autocomplete="off"
                           placeholder="Type to search authors...">
                    <div id="authorSuggestions" class="list-group" style="max-height: 150px; overflow-y: auto;">
                        <!-- Autocomplete suggestions will appear here -->
                    </div>
                </div>
            </div>

            <!-- Rating Range -->
            <div class="mb-3">
                <label class="form-label">Rating Range</label>
                <div class="row g-2">
                    <div class="col">
                        <input type="number" class="form-control" name="min_rating" 
                               min="0" max="5" step="0.1" value="<?php echo $minRating; ?>" 
                               placeholder="Min">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" name="max_rating" 
                               min="0" max="5" step="0.1" value="<?php echo $maxRating; ?>" 
                               placeholder="Max">
                    </div>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="mb-3">
                <label class="form-label">Sort By</label>
                <select class="form-select" name="sort">
                    <option value="default" <?php echo $sortBy == 'default' ? 'selected' : ''; ?>>Default</option>
                    <option value="title-asc" <?php echo $sortBy == 'title-asc' ? 'selected' : ''; ?>>Title (A-Z)</option>
                    <option value="title-desc" <?php echo $sortBy == 'title-desc' ? 'selected' : ''; ?>>Title (Z-A)</option>
                    <option value="year-desc" <?php echo $sortBy == 'year-desc' ? 'selected' : ''; ?>>Year (Newest)</option>
                    <option value="year-asc" <?php echo $sortBy == 'year-asc' ? 'selected' : ''; ?>>Year (Oldest)</option>
                    <option value="downloads-desc" <?php echo $sortBy == 'downloads-desc' ? 'selected' : ''; ?>>Most Downloaded</option>
                    <option value="rating-desc" <?php echo $sortBy == 'rating-desc' ? 'selected' : ''; ?>>Highest Rated</option>
                </select>
            </div>

            <!-- Display Type -->
            <input type="hidden" name="display" value="<?php echo $displayType; ?>">

            <!-- Submit and Reset Buttons -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='thesis.php'">Reset Filters</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Author Search Autocomplete -->
<script>
document.getElementById('authorSearchBtn').addEventListener('click', function() {
    const container = document.getElementById('authorSearchContainer');
    container.style.display = container.style.display === 'none' ? 'block' : 'none';
    document.getElementById('authorSearchInput').focus();
});

document.getElementById('authorSearchInput').addEventListener('input', function() {
    const query = this.value.trim().toLowerCase();
    const suggestionsContainer = document.getElementById('authorSuggestions');
    suggestionsContainer.innerHTML = '';

    if (query.length > 0) {
        const authors = <?php echo json_encode($authors); ?>;
        const filteredAuthors = authors.filter(author => 
            author.toLowerCase().includes(query)
        );

        filteredAuthors.forEach(author => {
            const suggestionItem = document.createElement('button');
            suggestionItem.classList.add('list-group-item', 'list-group-item-action');
            suggestionItem.textContent = author;
            suggestionItem.onclick = function() {
                document.getElementById('authorDropdown').value = author;
                document.getElementById('authorSearchInput').value = '';
                document.getElementById('authorSearchContainer').style.display = 'none'; // Collapse the search
                suggestionsContainer.innerHTML = '';
            };
            suggestionsContainer.appendChild(suggestionItem);
        });
    }
});
</script>

<!-- Custom CSS for Design -->
<style>
.list-group-item-action {
    cursor: pointer;
}

.list-group-item-action:hover {
    background-color: #f1f1f1;
}
</style>