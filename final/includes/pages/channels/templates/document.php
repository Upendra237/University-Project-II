<div class="document-list">
    <?php foreach ($content as $item): ?>
    <div class="card mb-3 document-card">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Document Icon -->
                <div class="col-auto">
                    <div class="document-icon">
                        <?php
                        $fileExtension = pathinfo($item['content_path'], PATHINFO_EXTENSION);
                        $iconClass = 'bi-file-text';
                        switch(strtolower($fileExtension)) {
                            case 'pdf':
                                $iconClass = 'bi-file-pdf';
                                break;
                            case 'doc':
                            case 'docx':
                                $iconClass = 'bi-file-word';
                                break;
                            case 'xls':
                            case 'xlsx':
                                $iconClass = 'bi-file-excel';
                                break;
                            case 'ppt':
                            case 'pptx':
                                $iconClass = 'bi-file-ppt';
                                break;
                        }
                        ?>
                        <i class="bi <?php echo $iconClass; ?> display-4"></i>
                    </div>
                </div>

                <!-- Document Info -->
                <div class="col">
                    <h5 class="card-title mb-1">
                        <?php echo htmlspecialchars($item['title']); ?>
                    </h5>
                    
                    <?php if ($item['description']): ?>
                        <p class="card-text text-muted mb-2"><?php echo htmlspecialchars($item['description']); ?></p>
                    <?php endif; ?>

                    <!-- Metadata -->
                    <div class="document-meta">
                        <div class="row g-3">
                            <?php if ($item['author']): ?>
                                <div class="col-auto">
                                    <span class="meta-item">
                                        <i class="bi bi-person"></i>
                                        <?php echo htmlspecialchars($item['author']); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if ($item['year']): ?>
                                <div class="col-auto">
                                    <span class="meta-item">
                                        <i class="bi bi-calendar"></i>
                                        <?php echo htmlspecialchars($item['year']); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if ($item['department']): ?>
                                <div class="col-auto">
                                    <span class="meta-item">
                                        <i class="bi bi-building"></i>
                                        <?php echo htmlspecialchars($item['department']); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <!-- Custom Metadata -->
                            <?php if (!empty($item['metadata'])): ?>
                                <?php foreach ($item['metadata'] as $key => $value): ?>
                                    <div class="col-auto">
                                        <span class="meta-item">
                                            <i class="bi bi-tag"></i>
                                            <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $key))); ?>:
                                            <?php echo htmlspecialchars($value); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-auto ms-auto">
                    <div class="document-stats text-end mb-2">
                        <span class="badge bg-light text-dark me-2">
                            <i class="bi bi-eye"></i> <?php echo number_format($item['views']); ?>
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-download"></i> <?php echo number_format($item['downloads']); ?>
                        </span>
                    </div>
                    <div class="btn-group">
                        <?php if ($item['content_path']): ?>
                            <a href="read.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> Read
                            </a>
                            <a href="download.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-download"></i> Download
                            </a>
                        <?php else: ?>
                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="bi bi-lock"></i> Not Available
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (empty($content)): ?>
    <div class="text-center py-5">
        <i class="bi bi-file-earmark-text display-1 text-muted"></i>
        <h3 class="mt-3">No Documents Found</h3>
        <p class="text-muted">Try adjusting your search criteria or check back later.</p>
    </div>
    <?php endif; ?>
</div>

<style>
.document-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid rgba(0,0,0,0.1);
}

.document-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.document-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0d6efd;
}

.document-meta {
    font-size: 0.875rem;
}

.meta-item {
    color: #6c757d;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.document-stats .badge {
    font-weight: normal;
}

.btn-group .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

/* Empty state styling */
.text-center.py-5 {
    background: #f8f9fa;
    border-radius: 8px;
}

.text-center.py-5 .display-1 {
    opacity: 0.5;
}
</style>