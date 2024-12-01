<?php
$itemsPerPage = 9;
$totalItems = count($filteredProjects);
$totalPages = ceil($totalItems / $itemsPerPage);

$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages));

$offset = ($currentPage - 1) * $itemsPerPage;
$currentItems = array_slice($filteredProjects, $offset, $itemsPerPage);
?>

<div class="row g-4">
    <?php if (empty($filteredProjects)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                No projects found matching your criteria. Try adjusting your filters.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($currentItems as $project): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 khec-project-card">
                    <!-- Project Preview Image -->
                    <div class="position-relative">
                        <img src="assets/images/projects/<?php echo htmlspecialchars($project['preview_image']); ?>"
                             class="card-img-top khec-project-image" 
                             alt="<?php echo htmlspecialchars($project['title']); ?>">
                        
                        <!-- Project Type Badge -->
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-primary">
                                <?php echo htmlspecialchars($project['project_type']); ?>
                            </span>
                        </div>
                        
                        <!-- Rating Badge -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-warning">
                                <i class="bi bi-star-fill"></i> 
                                <?php echo number_format($project['rating'], 1); ?>
                            </span>
                        </div>

                        <!-- Overlay with Quick Actions -->
                        <div class="khec-project-hover-overlay">
                            <div class="d-flex gap-2">
                                <?php if ($project['demo_url']): ?>
                                    <a href="<?php echo htmlspecialchars($project['demo_url']); ?>" 
                                       class="btn btn-primary btn-sm" target="_blank">
                                        <i class="bi bi-play-circle"></i> Demo
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($project['report_url']): ?>
                                    <a href="<?php echo htmlspecialchars($project['report_url']); ?>" 
                                       class="btn btn-light btn-sm">
                                        <i class="bi bi-eye"></i> View Report
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Department Badge -->
                        <div class="mb-2">
                            <span class="badge bg-info">
                                <?php echo htmlspecialchars($project['department']); ?>
                            </span>
                        </div>

                        <!-- Project Title -->
                        <h5 class="card-title">
                            <?php echo htmlspecialchars($project['title']); ?>
                        </h5>

                        <!-- Project Description -->
                        <p class="khec-project-description">
                            <?php echo htmlspecialchars($project['description']); ?>
                        </p>

                        <!-- Team Members -->
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-people"></i> Team:
                                <?php echo htmlspecialchars($project['team_members']); ?>
                            </small>
                        </div>

                        <!-- Supervisor -->
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-person"></i> Supervisor:
                                <?php echo htmlspecialchars($project['supervisor']); ?>
                            </small>
                        </div>

                        <!-- Views and Downloads -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-eye"></i> <?php echo number_format($project['views']); ?> views
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-download"></i> <?php echo number_format($project['downloads']); ?> downloads
                            </small>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <?php echo $project['semester']; ?> Semester | <?php echo $project['year']; ?>
                            </small>
                            <div>
                                <?php if ($project['github_url']): ?>
                                    <a href="<?php echo htmlspecialchars($project['github_url']); ?>" 
                                    class="btn btn-outline-dark" 
                                    target="_blank">
                                        <i class="bi bi-github"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if ($project['report_url']): ?>
                                    <a href="<?php echo htmlspecialchars($project['report_url']); ?>" 
                                    class="btn btn-outline-primary">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="col-12">
                <nav aria-label="Project navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?><?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?><?php echo $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>">
                                    Next
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>