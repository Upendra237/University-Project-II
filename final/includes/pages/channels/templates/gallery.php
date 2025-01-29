<div class="row g-4">
    <?php foreach ($content as $item): ?>
    <div class="col-sm-6 col-lg-4">
        <div class="card h-100 gallery-item">
            <div class="gallery-image">
                <?php if ($item['preview_image']): ?>
                    <img src="<?php echo ROOT_URL; ?>uploads/channels/gallery/<?php echo htmlspecialchars($item['preview_image']); ?>"
                         class="card-img-top" alt="<?php echo htmlspecialchars($item['title']); ?>">
                <?php else: ?>
                    <div class="placeholder-image">
                        <i class="bi bi-image text-muted"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                <?php if ($item['description']): ?>
                    <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($item['metadata'])): ?>
                <div class="image-meta text-muted small">
                    <?php if (isset($item['metadata']['event_name'])): ?>
                        <div><i class="bi bi-calendar-event"></i> <?php echo htmlspecialchars($item['metadata']['event_name']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($item['metadata']['event_date'])): ?>
                        <div><i class="bi bi-calendar"></i> <?php echo date('F j, Y', strtotime($item['metadata']['event_date'])); ?></div>
                    <?php endif; ?>
                    <?php if (isset($item['metadata']['photographer'])): ?>
                        <div><i class="bi bi-camera"></i> <?php echo htmlspecialchars($item['metadata']['photographer']); ?></div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-transparent">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary view-image" 
                                data-bs-toggle="modal" data-bs-target="#imageModal"
                                data-image="<?php echo ROOT_URL; ?>uploads/channels/gallery/<?php echo htmlspecialchars($item['content_path']); ?>"
                                data-title="<?php echo htmlspecialchars($item['title']); ?>">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <?php if ($item['content_path']): ?>
                        <a href="download.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-download"></i> Download
                        </a>
                        <?php endif; ?>
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-eye"></i> <?php echo number_format($item['views']); ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle image modal
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const image = button.dataset.image;
            const title = button.dataset.title;
            
            const modalTitle = this.querySelector('.modal-title');
            const modalImage = this.querySelector('.modal-body img');
            
            modalTitle.textContent = title;
            modalImage.src = image;
        });
    }
});
</script>