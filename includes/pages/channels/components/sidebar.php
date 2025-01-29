<div class="channels-nav">
    <!-- Pustak Collections -->
    <div class="nav-section mb-4">
        <h6 class="nav-section-title">Pustak Collections</h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo empty($section) ? 'active' : ''; ?>" href="/channels">
                    <i class="bi bi-house-door"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $section === 'explore' ? 'active' : ''; ?>" href="/channels/explore">
                    <i class="bi bi-compass"></i> Explore
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $section === 'trending' ? 'active' : ''; ?>" href="/channels/trending">
                    <i class="bi bi-graph-up-arrow"></i> Trending
                </a>
            </li>
        </ul>
    </div>

    <!-- For Students -->
    <div class="nav-section mb-4">
        <h6 class="nav-section-title">For Students</h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="/channels/course-materials">
                    <i class="bi bi-book"></i> Course Materials
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/channels/past-papers">
                    <i class="bi bi-file-text"></i> Past Papers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/channels/resources">
                    <i class="bi bi-journals"></i> Study Resources
                </a>
            </li>
        </ul>
    </div>

    <!-- Library -->
    <div class="nav-section mb-4">
        <h6 class="nav-section-title">Library</h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="/channels/references">
                    <i class="bi bi-bookmark"></i> References
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/channels/saved">
                    <i class="bi bi-heart"></i> Saved
                </a>
            </li>
        </ul>
    </div>

    <!-- Your Department -->
    <div class="nav-section">
        <h6 class="nav-section-title">Your Department</h6>
        <ul class="nav flex-column">
            <?php foreach ($departments as $dept): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo $selectedDepartment === $dept['department'] ? 'active' : ''; ?>" 
                   href="/channels?department=<?php echo urlencode($dept['department']); ?>">
                    <i class="bi bi-building"></i> <?php echo htmlspecialchars($dept['department']); ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>