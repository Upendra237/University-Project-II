<?php
$itemsPerPage = 6;
$totalItems = count($filteredThesis);
$totalPages = ceil($totalItems / $itemsPerPage);

$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // Ensure current page is within valid range

$displayType = isset($_GET['display']) ? $_GET['display'] : 'portrait'; // Set default display type to 'portrait'

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
            <div class="col-md-6">
                <div class="card thesis-card h-100">
                    <div class="row g-0">
                        <!-- Left Column: Image, Rating, and Icons -->
                        <div class="col-4 d-flex flex-column align-items-center justify-content-center p-3">
                            <div class="card-preview-portrait position-relative mb-2">
                                <img src="assets/images/thesis/<?php echo htmlspecialchars($thesis['portrait-preview']); ?>" 
                                     class="img-fluid w-100 object-fit-contain" alt="<?php echo htmlspecialchars($thesis['title']); ?>">
                            </div>
                            
                            <div class="rating-stars mb-2">
                                <?php 
                                    $rating = $thesis['rating'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-muted"></i>';
                                    }
                                ?>
                            </div>

                            <div class="d-flex justify-content-center mb-3">
                                <?php if ($thesis['is_peer_reviewed']): ?>
                                    <span class="badge bg-success me-1" data-bs-toggle="tooltip" title="Peer Reviewed">
                                        <i class="bi bi-check-circle"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger me-1" data-bs-toggle="tooltip" title="Not Peer Reviewed">
                                        <i class="bi bi-x-circle"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if ($thesis['file_available']): ?>
                                    <span class="badge bg-success me-1" data-bs-toggle="tooltip" title="File Available">
                                        <i class="bi bi-file-earmark"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger me-1" data-bs-toggle="tooltip" title="File Not Available">
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

                        <!-- Right Column: Title, Description, and Other Details -->
                        <div class="col-8">
                            <div class="card-body d-flex flex-column h-100">
                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($thesis['title']); ?></h5>
                                <p class="card-text thesis-description flex-grow-1"><?php echo htmlspecialchars($thesis['description']); ?></p>
                                <div class="small text-muted d-flex justify-content-between mb-2">
                                    <div><i class="bi bi-person"></i> <?php echo htmlspecialchars($thesis['author']); ?></div>
                                    <div><i class="bi bi-calendar"></i> <?php echo $thesis['year']; ?></div>
                                </div>
                                <div class="small text-muted d-flex justify-content-between">
                                    <div><i class="bi bi-eye"></i> <?php echo number_format($thesis['views']); ?> views</div>
                                    <div><i class="bi bi-download"></i> <?php echo number_format($thesis['downloads']); ?> downloads</div>
                                </div>
                                <div class="card-footer bg-transparent mt-2 p-0">
                                    <div class="d-grid">
                                        <a href="reader/?id=<?php echo $thesis['id']; ?>&type=thesis" 
                                           class="btn btn-primary">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination Controls -->
<nav>
    <ul class="pagination justify-content-center pt-4">
        <?php if ($currentPage > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?display=<?php echo $displayType; ?>&page=<?php echo $currentPage - 1; ?>">Previous</a>
            </li>
        <?php endif; ?>

        <?php for ($page = 1; $page <= $totalPages; $page++): ?>
            <li class="page-item <?php echo ($page == $currentPage) ? 'active' : ''; ?>">
                <a class="page-link" href="?display=<?php echo $displayType; ?>&page=<?php echo $page; ?>"><?php echo $page; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?display=<?php echo $displayType; ?>&page=<?php echo $currentPage + 1; ?>">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
