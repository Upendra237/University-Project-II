<?php
$itemsPerPage = 9;
$totalItems = count($filteredThesis);
$totalPages = ceil($totalItems / $itemsPerPage);

// Get the current page from the query string, defaulting to 1 if not set or invalid
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // Ensure current page is within valid range

// Calculate the offset and get only the items for the current page
$offset = ($currentPage - 1) * $itemsPerPage;
$currentItems = array_slice($filteredThesis, $offset, $itemsPerPage);
?>
<div class="row g-4">
    <?php if (empty($filteredThesis)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                No thesis papers found matching your criteria. Try adjusting your filters.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($currentItems as $thesis): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 thesis-card">
                    <div class="card-preview position-relative">                    
                        <img src="<?php echo ROOT_URL; ?>assets/images/thesis/<?php echo htmlspecialchars($thesis['landscape-preview']); ?>"
                             class="card-img-top" alt="<?php echo htmlspecialchars($thesis['title']); ?>">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-primary">
                                <i class="bi bi-star-fill"></i> <?php echo number_format($thesis['rating'], 1); ?>
                            </span>
                        </div>
                        <div class="preview-overlay position-absolute top-50 start-50 translate-middle d-flex flex-column justify-content-center align-items-center">
                            <div class="d-flex justify-content-center mb-3">
                                <?php if ($thesis['is_peer_reviewed']): ?>
                                    <span class="badge bg-success me-2" data-bs-toggle="tooltip" title="Peer Reviewed">
                                        <i class="bi bi-check-circle"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger me-2" data-bs-toggle="tooltip" title="Not Peer Reviewed">
                                        <i class="bi bi-x-circle"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if ($thesis['file_available']): ?>
                                    <span class="badge bg-success me-2" data-bs-toggle="tooltip" title="File Available">
                                        <i class="bi bi-file-earmark"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger me-2" data-bs-toggle="tooltip" title="File Not Available">
                                        <i class="bi bi-file-x"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if ($thesis['access_type'] === 'open'): ?>
                                    <span class="badge bg-success" data-bs-toggle="tooltip" title="Open Access">
                                        <i class="bi bi-globe"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger" data-bs-toggle="tooltip" title="Restricted Access">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($thesis['title']); ?></h5>
                        </div>
                        <div>
                            <p class="thesis-description"><?php echo htmlspecialchars($thesis['description']); ?></p>
                        </div>
                        <div class="meta-info">
                            <div><i class="text-primary bi bi-person"></i> <?php echo htmlspecialchars($thesis['author']); ?></div>
                            <div><i class="bi bi-calendar"></i> <?php echo $thesis['year']; ?></div>
                        </div>
                        <div class="meta-info">
                            <div><i class="bi bi-eye "></i> <?php echo number_format($thesis['views']); ?> views</div>
                            <div><i class="text-success bi bi-download"></i> <?php echo number_format($thesis['downloads']); ?> downloads</div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-grid">
                            <a href="reader/?id=<?php echo $thesis['id']; ?>&type=thesis" 
                               class="btn btn-primary">Read Now</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination Controls -->
<nav>
    <ul class="pagination justify-content-center mt-3">
        <?php if ($currentPage > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>">Previous</a>
            </li>
        <?php endif; ?>

        <?php for ($page = 1; $page <= $totalPages; $page++): ?>
            <li class="page-item <?php echo ($page == $currentPage) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
