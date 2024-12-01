<!-- Desktop View - Full Advanced Search -->
<div class="d-none d-md-block">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Search & Filters</h4>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="projectSearchForm">
                <!-- Search Box -->
                <div class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               value="<?php echo htmlspecialchars($searchQuery); ?>" 
                               placeholder="Search projects, team members...">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Sort Dropdown -->
                <div class="mb-4">
                    <label class="form-label">Sort By</label>
                    <select class="form-select khec-project-sort" name="sort" onchange="this.form.submit()">
                        <option value="newest" <?php echo $sortBy == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="oldest" <?php echo $sortBy == 'oldest' ? 'selected' : ''; ?>>Oldest First</option>
                        <option value="downloads-high" <?php echo $sortBy == 'downloads-high' ? 'selected' : ''; ?>>Most Downloaded</option>
                        <option value="views-high" <?php echo $sortBy == 'views-high' ? 'selected' : ''; ?>>Most Viewed</option>
                        <option value="rating-high" <?php echo $sortBy == 'rating-high' ? 'selected' : ''; ?>>Highest Rated</option>
                    </select>
                </div>

                <div class="border-top pt-3">
                    <!-- Project Type Filter -->
                    <div class="mb-3">
                        <label class="form-label">Project Type</label>
                        <select class="form-select" name="project_type">
                            <option value="">All Project Types</option>
                            <option value="Project I" <?php echo $selectedProjectType === 'Project I' ? 'selected' : ''; ?>>Project I (3rd Sem)</option>
                            <option value="Project II" <?php echo $selectedProjectType === 'Project II' ? 'selected' : ''; ?>>Project II (5th Sem)</option>
                            <option value="Project III" <?php echo $selectedProjectType === 'Project III' ? 'selected' : ''; ?>>Project III (7th Sem)</option>
                            <option value="Project IV" <?php echo $selectedProjectType === 'Project IV' ? 'selected' : ''; ?>>Project IV (8th Sem)</option>
                        </select>
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

                    <!-- Supervisor Filter -->
                    <div class="mb-3">
                        <label class="form-label">Project Supervisor</label>
                        <select class="form-select" name="supervisor">
                            <option value="">All Supervisors</option>
                            <?php foreach ($supervisors as $supervisor): ?>
                                <option value="<?php echo htmlspecialchars($supervisor); ?>" 
                                        <?php echo $selectedSupervisor === $supervisor ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($supervisor); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Rating Range -->
                    <div class="mb-4">
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
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='projects.php'">Reset All</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mobile View -->
<div class="d-md-none">
    <div class="card mb-3">
        <div class="card-body p-3">
            <!-- Simple Search Form -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="mobileSearchForm">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               value="<?php echo htmlspecialchars($searchQuery); ?>" 
                               placeholder="Search projects...">
                        <button type="submit" class="btn btn-primary px-3">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Sort Dropdown -->
                <select class="form-select khec-project-sort" name="sort" onchange="this.form.submit()">
                    <option value="newest" <?php echo $sortBy == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                    <option value="downloads-high" <?php echo $sortBy == 'downloads-high' ? 'selected' : ''; ?>>Most Downloaded</option>
                    <option value="views-high" <?php echo $sortBy == 'views-high' ? 'selected' : ''; ?>>Most Viewed</option>
                    <option value="rating-high" <?php echo $sortBy == 'rating-high' ? 'selected' : ''; ?>>Highest Rated</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Advanced Filters Button -->
    <button class="btn btn-outline-primary w-100 mb-3" type="button" 
            data-bs-toggle="modal" data-bs-target="#advancedFiltersModal">
        <i class="bi bi-sliders2"></i> Advanced Filters
        <?php if (hasActiveFilters()): ?>
            <span class="badge bg-primary ms-2"><?php echo countActiveFilters(); ?></span>
        <?php endif; ?>
    </button>
</div>

<!-- Mobile Advanced Filters Modal -->
<div class="modal fade khec-project-modal" id="advancedFiltersModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title">Advanced Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="mobileAdvancedForm">
                    <!-- Hidden search field to preserve search term -->
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
                    
                    <!-- Copy of all filter options from desktop view -->
                    <!-- Project Type Filter -->
                    <div class="mb-3">
                        <label class="form-label">Project Type</label>
                        <select class="form-select" name="project_type">
                            <option value="">All Project Types</option>
                            <option value="Project I" <?php echo $selectedProjectType === 'Project I' ? 'selected' : ''; ?>>Project I (3rd Sem)</option>
                            <option value="Project II" <?php echo $selectedProjectType === 'Project II' ? 'selected' : ''; ?>>Project II (5th Sem)</option>
                            <option value="Project III" <?php echo $selectedProjectType === 'Project III' ? 'selected' : ''; ?>>Project III (7th Sem)</option>
                            <option value="Project IV" <?php echo $selectedProjectType === 'Project IV' ? 'selected' : ''; ?>>Project IV (8th Sem)</option>
                        </select>
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

                    <!-- Supervisor Filter -->
                    <div class="mb-3">
                        <label class="form-label">Project Supervisor</label>
                        <select class="form-select" name="supervisor">
                            <option value="">All Supervisors</option>
                            <?php foreach ($supervisors as $supervisor): ?>
                                <option value="<?php echo htmlspecialchars($supervisor); ?>" 
                                        <?php echo $selectedSupervisor === $supervisor ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($supervisor); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Rating Range -->
                    <div class="mb-4">
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
                </form>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='projects.php'">Reset All</button>
                <button type="submit" form="mobileAdvancedForm" class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
    </div>
</div>

<?php
// Helper functions for filter status
function hasActiveFilters() {
    return isset($_GET['department']) || isset($_GET['project_type']) || 
           isset($_GET['year']) || isset($_GET['supervisor']) || 
           isset($_GET['min_rating']) || isset($_GET['max_rating']);
}

function countActiveFilters() {
    $count = 0;
    $filters = ['department', 'project_type', 'year', 'supervisor'];
    foreach ($filters as $filter) {
        if (!empty($_GET[$filter])) $count++;
    }
    if (!empty($_GET['min_rating']) || !empty($_GET['max_rating'])) $count++;
    return $count;
}
?>