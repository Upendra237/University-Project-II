<section class="trending-collections mb-5">
    <div class="section-header mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="header-content">
                <div class="d-flex align-items-center mb-2">
                    <span class="feature-icon me-2">
                        <i class="bi bi-graph-up-arrow"></i>
                    </span>
                    <h2 class="section-title mb-0">Trending Collections</h2>
                </div>
                <p class="section-subtitle text-muted">Most popular resources this week</p>
            </div>
            <a href="/channels/trending" class="view-all-btn">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="trending-slider">
        <div class="slider-controls">
            <button class="control-btn prev" aria-label="Previous">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="control-btn next" aria-label="Next">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="trending-container">
            <?php
            $trendingQuery = "SELECT c.*, 
                COALESCE((SELECT COUNT(*) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id), 0) as content_count,
                COALESCE((SELECT SUM(views) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id), 0) as total_views,
                COALESCE((SELECT SUM(downloads) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id), 0) as total_downloads
                FROM `repo-channels` c 
                WHERE c.status = 'active'
                ORDER BY total_views DESC, total_downloads DESC 
                LIMIT 10";
            $trendingChannels = $conn->query($trendingQuery)->fetch_all(MYSQLI_ASSOC);
            
            foreach ($trendingChannels as $index => $channel): ?>
                <div class="trending-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                    <div class="card">
                        <div class="card-media">
                            <?php if ($channel['preview_image']): ?>
                                <img src="<?php echo ROOT_URL; ?>assets/images/channels/<?php echo htmlspecialchars($channel['preview_image']); ?>"
                                     alt="<?php echo htmlspecialchars($channel['name']); ?>"
                                     class="card-img">
                            <?php endif; ?>
                            <div class="card-overlay">
                                <span class="trend-badge">
                                    <i class="bi bi-arrow-up-right"></i>
                                    Trending
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="card-type">
                                <span class="type-badge bg-<?php echo strtolower($channel['template_type']); ?>">
                                    <i class="bi bi-<?php echo getTypeIcon($channel['template_type']); ?>"></i>
                                    <?php echo ucfirst($channel['template_type']); ?>
                                </span>
                            </div>

                            <h3 class="card-title">
                                <?php echo htmlspecialchars($channel['name']); ?>
                            </h3>
                            
                            <p class="card-text">
                                <?php echo htmlspecialchars(substr($channel['description'], 0, 80)) . '...'; ?>
                            </p>

                            <?php if ($channel['department']): ?>
                                <div class="card-department">
                                    <i class="bi bi-building"></i>
                                    <span><?php echo htmlspecialchars($channel['department']); ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="card-stats">
                                <div class="stat-item">
                                    <i class="bi bi-collection"></i>
                                    <span><?php echo isset($channel['content_count']) ? number_format($channel['content_count']) : '0'; ?> items</span>
                                </div>
                                <div class="stat-item">
                                    <i class="bi bi-eye"></i>
                                    <span><?php echo isset($channel['total_views']) ? number_format($channel['total_views']) : '0'; ?> views</span>
                                </div>
                            </div>

                            <a href="view.php?id=<?php echo $channel['id']; ?>" 
                               class="explore-btn">
                                Explore Collection
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>