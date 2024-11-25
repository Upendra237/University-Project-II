<!-- includes/collection-card.php -->
<div class="col-md-4">
    <a href="?collection=<?php echo $collection['slug']; ?>" class="collection-card">
        <div class="card h-100">
            <img src="assets/images/features/<?php echo $collection['image']; ?>" class="card-img-top" alt="<?php echo $collection['title']; ?>">
            <div class="card-body">
                <h4 class="card-title"><?php echo $collection['title']; ?></h4>
                <p class="card-text"><?php echo $collection['description']; ?></p>
            </div>
            <div class="card-footer">
                <small class="text-muted">
                    <?php echo count($collection['resources']); ?> Resources
                </small>
            </div>
        </div>
    </a>
</div>
