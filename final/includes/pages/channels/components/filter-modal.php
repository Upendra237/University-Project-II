// File: includes/pages/channels/components/filter-modal.php

<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">Advanced Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <form id="advancedFilterForm">
                    <!-- Content Type -->
                    <div class="filter-section mb-4">
                        <h6 class="filter-section-title">Content Type</h6>
                        <div class="row g-2">
                            <?php foreach ($templateTypes as $type): ?>
                            <div class="col-6">
                                <div class="form-check card">
                                    <div class="card-body p-2">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               name="types[]" 
                                               value="<?php echo $type; ?>"
                                               id="type_<?php echo $type; ?>"
                                               <?php echo in_array($type, $selectedTypes ?? []) ? 'checked' : ''; ?>>
                                        <label class="form-check-label w-100" for="type_<?php echo $type; ?>">
                                            <i class="bi bi-<?php echo getTypeIcon($type); ?>"></i>
                                            <?php echo ucfirst($type); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Sort By -->
                    <div class="filter-section mb-4">
                        <h6 class="filter-section-title">Sort By</h6>
                        <select class="form-select" name="sort">
                            <option value="recent">Recently Added</option>
                            <option value="popular">Most Popular</option>
                            <option value="name">Name (A-Z)</option>
                            <option value="rating">Highest Rated</option>
                        </select>
                    </div>

                    <!-- Rating Range -->
                    <div class="filter-section mb-4">
                        <h6 class="filter-section-title">Rating</h6>
                        <div class="range-slider">
                            <input type="range" class="form-range" min="0" max="5" step="0.5" id="ratingRange">
                            <div class="range-value"></div>
                        </div>
                    </div>

                    <!-- Access Type -->
                    <div class="filter-section">
                        <h6 class="filter-section-title">Access Type</h6>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="access" value="all" id="access_all" checked>
                            <label class="btn btn-outline-primary" for="access_all">All</label>

                            <input type="radio" class="btn-check" name="access" value="open" id="access_open">
                            <label class="btn btn-outline-primary" for="access_open">Open Access</label>

                            <input type="radio" class="btn-check" name="access" value="restricted" id="access_restricted">
                            <label class="btn btn-outline-primary" for="access_restricted">Restricted</label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" onclick="resetFilters()">Reset</button>
                <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
            </div>
        </div>
    </div>
</div>