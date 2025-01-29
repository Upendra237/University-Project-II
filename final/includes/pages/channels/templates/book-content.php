// File: includes/pages/channels/templates/book-content.php

<div class="book-content" data-view="grid">
    <div class="row g-4">
        <?php foreach ($content as $item): 
            $metadata = json_decode($item['metadata'], true);
        ?>
            <div class="col-sm-6 col-lg-3">
                <div class="book-item">
                    <!-- Book Cover -->
                    <div class="book-cover">
                        <?php if ($item['preview_image']): ?>
                            <img src="<?php echo ROOT_URL; ?>uploads/channels/<?php echo htmlspecialchars($item['preview_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                                 class="cover-img">
                        <?php else: ?>
                            <div class="cover-placeholder">
                                <i class="bi bi-book"></i>
                                <div class="placeholder-title">
                                    <?php echo htmlspecialchars($item['title']); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Quick Actions Overlay -->
                        <div class="book-overlay">
                            <div class="book-actions">
                                <button class="btn btn-light btn-sm" 
                                        onclick="previewBook(<?php echo htmlspecialchars(json_encode($item)); ?>)">
                                    <i class="bi bi-eye"></i> Preview
                                </button>
                                <?php if ($item['access_type'] === 'open'): ?>
                                    <a href="download.php?id=<?php echo $item['id']; ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Book Info -->
                    <div class="book-info">
                        <h5 class="book-title">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </h5>

                        <!-- Book Metadata -->
                        <div class="book-metadata">
                            <?php if (isset($metadata['author'])): ?>
                                <div class="author">
                                    <i class="bi bi-person"></i> 
                                    <?php echo htmlspecialchars($metadata['author']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($metadata['publisher'])): ?>
                                <div class="publisher">
                                    <i class="bi bi-building"></i>
                                    <?php echo htmlspecialchars($metadata['publisher']); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($metadata['year'])): ?>
                                <div class="year">
                                    <i class="bi bi-calendar"></i>
                                    <?php echo htmlspecialchars($metadata['year']); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($metadata['isbn'])): ?>
                                <div class="isbn">
                                    <i class="bi bi-upc"></i>
                                    ISBN: <?php echo htmlspecialchars($metadata['isbn']); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($item['description']): ?>
                            <p class="book-description">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>
                        <?php endif; ?>

                        <!-- Book Stats -->
                        <div class="book-stats">
                            <span title="Views">
                                <i class="bi bi-eye"></i> 
                                <?php echo number_format($item['views']); ?>
                            </span>
                            <span title="Downloads">
                                <i class="bi bi-download"></i> 
                                <?php echo number_format($item['downloads']); ?>
                            </span>
                            <?php if (isset($metadata['pages'])): ?>
                                <span title="Pages">
                                    <i class="bi bi-file-text"></i>
                                    <?php echo number_format($metadata['pages']); ?> pages
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Book Preview Modal -->
<div class="modal fade" id="bookPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Book Cover -->
                    <div class="col-md-4">
                        <div class="preview-cover"></div>
                        <div class="preview-actions mt-3"></div>
                    </div>
                    
                    <!-- Book Details -->
                    <div class="col-md-8">
                        <div class="preview-details"></div>
                        
                        <!-- Table of Contents -->
                        <div class="preview-toc mt-4">
                            <h6>Table of Contents</h6>
                            <div class="toc-content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
