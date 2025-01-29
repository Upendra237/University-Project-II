// File: includes/pages/channels/components/content-filters.php

<div class="content-filters mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <!-- Search Input -->
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" 
                       class="form-control border-start-0" 
                       id="contentSearch"
                       placeholder="Search in this collection..."
                       value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="d-flex justify-content-md-end align-items-center gap-3 mt-3 mt-md-0">
                <!-- Sort Dropdown -->
                <select class="form-select w-auto" id="contentSort">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="popular">Most Popular</option>
                    <option value="downloads">Most Downloaded</option>
                </select>

                <!-- View Type Toggle -->
                <div class="btn-group" role="group">
                    <button type="button" 
                            class="btn btn-outline-secondary active" 
                            data-view="grid">
                        <i class="bi bi-grid"></i>
                    </button>
                    <button type="button" 
                            class="btn btn-outline-secondary" 
                            data-view="list">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

                <!-- Filter Button -->
                <button type="button" 
                        class="btn btn-primary" 
                        data-bs-toggle="modal" 
                        data-bs-target="#contentFilterModal">
                    <i class="bi bi-funnel"></i>
                    <span class="d-none d-sm-inline ms-1">Filter</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Active Filters -->
    <?php if (!empty($_GET['filters'])): ?>
    <div class="active-filters mt-3">
        <?php foreach ($_GET['filters'] as $filter => $value): ?>
        <span class="badge bg-primary">
            <?php echo ucfirst($filter); ?>: <?php echo htmlspecialchars($value); ?>
            <button type="button" class="btn-close btn-close-white" 
                    onclick="removeFilter('<?php echo $filter; ?>')"></button>
        </span>
        <?php endforeach; ?>
        <button type="button" class="btn btn-link btn-sm" onclick="clearAllFilters()">
            Clear All
        </button>
    </div>
    <?php endif; ?>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="contentFilterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="contentFilterForm">
                    <!-- Date Range -->
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="row g-2">
                            <div class="col">
                                <input type="date" class="form-control" name="date_from">
                            </div>
                            <div class="col">
                                <input type="date" class="form-control" name="date_to">
                            </div>
                        </div>
                    </div>

                    <!-- File Type (if applicable) -->
                    <?php if ($channel['template_type'] === 'document'): ?>
                    <div class="mb-3">
                        <label class="form-label">File Type</label>
                        <select class="form-select" name="file_type">
                            <option value="">All Types</option>
                            <option value="pdf">PDF</option>
                            <option value="doc">Word</option>
                            <option value="ppt">PowerPoint</option>
                        </select>
                    </div>
                    <?php endif; ?>

                    <!-- Other filters based on template type -->
                    <?php include getTemplateFilters($channel['template_type']); ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="applyContentFilters()">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>
</div>
