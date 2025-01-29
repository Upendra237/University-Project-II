// File: includes/pages/channels/templates/gallery-content.php

<div class="gallery-content" data-view="grid">
    <div class="row g-4">
        <?php foreach ($content as $item): ?>
            <div class="col-sm-6 col-lg-4">
                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="<?php echo ROOT_URL; ?>uploads/channels/<?php echo htmlspecialchars($item['content_path']); ?>" 
                             alt="<?php echo htmlspecialchars($item['title']); ?>"
                             class="img-fluid"
                             loading="lazy">
                        
                        <div class="gallery-overlay">
                            <button class="btn btn-light btn-sm" onclick="viewImage(<?php echo htmlspecialchars(json_encode($item)); ?>)">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <?php if ($item['access_type'] === 'open'): ?>
                                <a href="download.php?id=<?php echo $item['id']; ?>" class="btn btn-light btn-sm">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="gallery-info">
                        <h5 class="gallery-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                        <?php if ($item['description']): ?>
                            <p class="gallery-description"><?php echo htmlspecialchars($item['description']); ?></p>
                        <?php endif; ?>
                        
                        <!-- Metadata -->
                        <?php if ($item['metadata']): 
                            $metadata = json_decode($item['metadata'], true);
                        ?>
                            <div class="gallery-metadata">
                                <?php foreach ($metadata as $key => $value): ?>
                                    <div class="metadata-item">
                                        <span class="metadata-label"><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</span>
                                        <span class="metadata-value"><?php echo htmlspecialchars($value); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="gallery-stats">
                            <span><i class="bi bi-eye"></i> <?php echo number_format($item['views']); ?></span>
                            <span><i class="bi bi-download"></i> <?php echo number_format($item['downloads']); ?></span>
                            <span><i class="bi bi-calendar"></i> <?php echo date('M d, Y', strtotime($item['created_at'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Image Viewer Modal -->
<div class="modal fade" id="imageViewerModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="" class="img-fluid" id="modalImage">
            </div>
            <div class="modal-footer border-0">
                <div class="image-metadata"></div>
                <a href="#" class="btn btn-primary download-btn" download>
                    <i class="bi bi-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>
