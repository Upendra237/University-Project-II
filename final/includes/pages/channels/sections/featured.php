<section class="featured-channels mb-4">
    <?php
    $featuredQuery = "SELECT c.*, 
                    (SELECT COUNT(*) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id) as content_count,
                    (SELECT COALESCE(SUM(views), 0) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id) as total_views
                    FROM `repo-channels` c 
                    WHERE c.status = 'active' 
                    ORDER BY total_views DESC 
                    LIMIT 5";
    $result = $conn->query($featuredQuery);
    $featuredChannels = [];
    if ($result) {
        $featuredChannels = $result->fetch_all(MYSQLI_ASSOC);
    }
    ?>

    <div class="container-fluid">
        <div class="featured-header mb-4">
            <div class="d-flex align-items-center mb-2">
                <span class="featured-icon me-2">✨</span>
                <h2 class="featured-title mb-0">Featured Collections</h2>
            </div>
            <p class="featured-subtitle text-muted">Discover our most popular academic resources</p>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredChannels as $channel): ?>
                <div class="col-md-4">
                    <div class="featured-card">
                        <div class="card h-100 border-0 shadow-sm">
                            <?php if ($channel['preview_image']): ?>
                                <div class="card-img-container">
                                    <img src="<?php echo ROOT_URL; ?>assets/images/channels/<?php echo htmlspecialchars($channel['preview_image']); ?>"
                                         class="card-img-top featured-image" 
                                         alt="<?php echo htmlspecialchars($channel['name']); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <div class="channel-type mb-2">
                                    <span class="badge bg-purple-soft">
                                        <i class="bi bi-collection me-1"></i>
                                        <?php echo ucfirst($channel['template_type']); ?>
                                    </span>
                                </div>
                                
                                <h5 class="card-title">
                                    <?php echo htmlspecialchars($channel['name']); ?>
                                </h5>
                                
                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars(substr($channel['description'], 0, 100)) . '...'; ?>
                                </p>

                                <?php if ($channel['department']): ?>
                                    <div class="department-tag">
                                        <i class="bi bi-building"></i>
                                        <span><?php echo htmlspecialchars($channel['department']); ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="meta-footer">
                                    <div class="stats-row">
                                        <div class="stat-item">
                                            <i class="bi bi-file-text"></i>
                                            <span><?php echo $channel['content_count']; ?> items</span>
                                        </div>
                                        <div class="stat-item">
                                            <i class="bi bi-eye"></i>
                                            <span><?php echo number_format($channel['total_views']); ?> views</span>
                                        </div>
                                    </div>

                                    <a href="view.php?id=<?php echo $channel['id']; ?>" 
                                       class="btn btn-purple w-100">
                                        Explore Collection
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>