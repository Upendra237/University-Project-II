<div class="channels-search sticky-top">
    <div class="search-container bg-white shadow-sm py-2">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-2">
                <!-- Search Input -->
                <div class="flex-grow-1">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0 ps-0" 
                               id="channelSearch"
                               placeholder="Search collections..." 
                               value="<?php echo htmlspecialchars($searchQuery); ?>">
                    </div>
                </div>

                <!-- Type Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-collection"></i>
                        <span class="d-none d-sm-inline">Type</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php echo empty($selectedType) ? 'active' : ''; ?>" 
                               href="?type=">All Types</a></li>
                        <?php foreach ($templateTypes as $type): ?>
                            <li>
                                <a class="dropdown-item <?php echo $selectedType === $type ? 'active' : ''; ?>" 
                                   href="?type=<?php echo urlencode($type); ?>">
                                    <?php echo ucfirst($type); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Department Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-building"></i>
                        <span class="d-none d-sm-inline">Department</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php echo empty($selectedDepartment) ? 'active' : ''; ?>" 
                               href="?department=">All Departments</a></li>
                        <?php foreach ($departments as $dept): ?>
                            <li>
                                <a class="dropdown-item <?php echo $selectedDepartment === $dept['department'] ? 'active' : ''; ?>" 
                                   href="?department=<?php echo urlencode($dept['department']); ?>">
                                    <?php echo htmlspecialchars($dept['department']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Advanced Filter Button -->
                <button type="button" 
                        class="btn btn-primary" 
                        data-bs-toggle="modal" 
                        data-bs-target="#filterModal">
                    <i class="bi bi-sliders"></i>
                    <?php if (hasActiveFilters()): ?>
                        <span class="badge bg-light text-primary ms-1"><?php echo countActiveFilters(); ?></span>
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </div>
</div>