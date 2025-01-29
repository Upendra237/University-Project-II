<section class="department-section mb-5">
    <?php
    // For testing, hardcode department (later use session)
    $userDepartment = "Computer Engineering"; 
    
    if ($userDepartment):
        // Get department channels
        $deptQuery = "SELECT c.*, 
                     (SELECT COUNT(*) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id) as content_count,
                     (SELECT COALESCE(SUM(views), 0) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id) as total_views
                     FROM `repo-channels` c 
                     WHERE c.department = ? AND c.status = 'active'
                     ORDER BY total_views DESC 
                     LIMIT 6";
        $stmt = $conn->prepare($deptQuery);
        $stmt->bind_param('s', $userDepartment);
        $stmt->execute();
        $deptChannels = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if (!empty($deptChannels)):
    ?>
        <div class="section-header mb-4">
            <div class="d-flex justify-content-between align-items-start">
                <div class="header-content">
                    <div class="d-flex align-items-center mb-2">
                        <span class="department-icon">
                            <i class="bi bi-building"></i>
                        </span>
                        <h2 class="section-title mb-0">
                            <?php echo htmlspecialchars($userDepartment); ?>
                        </h2>
                    </div>
                    <p class="section-subtitle text-muted">Collections from your department</p>
                </div>
                <a href="/channels/department/<?php echo urlencode($userDepartment); ?>" 
                   class="view-more-btn">
                    View All
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="department-grid">
            <?php foreach ($deptChannels as $index => $channel): 
                $isLarge = $index < 2; // First two items are larger
            ?>
                <div class="department-card <?php echo $isLarge ? 'card-large' : ''; ?>" 
                     data-aos="fade-up" 
                     data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="card h-100">
                        <div class="card-img-wrapper">
                            <?php if ($channel['preview_image']): ?>
                                <img src="<?php echo ROOT_URL; ?>assets/images/channels/<?php echo htmlspecialchars($channel['preview_image']); ?>"
                                     class="card-img" 
                                     alt="<?php echo htmlspecialchars($channel['name']); ?>"
                                     onerror="this.onerror=null;this.src='<?php echo ROOT_URL; ?>assets/images/channels/default-cover.jpg';">
                            <?php else: ?>
                                <div class="default-image">
                                    <i class="bi bi-collection"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-overlay">
                                <span class="type-badge">
                                    <i class="bi bi-<?php echo getTypeIcon($channel['template_type']); ?>"></i>
                                    <?php echo ucfirst($channel['template_type']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <h3 class="card-title">
                                <?php echo htmlspecialchars($channel['name']); ?>
                            </h3>
                            
                            <p class="card-text">
                                <?php echo htmlspecialchars(substr($channel['description'], 0, $isLarge ? 150 : 100)) . '...'; ?>
                            </p>

                            <div class="card-meta">
                                <div class="meta-stats">
                                    <div class="stat-item">
                                        <i class="bi bi-collection"></i>
                                        <span><?php echo number_format($channel['content_count']); ?> items</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="bi bi-eye"></i>
                                        <span><?php echo number_format($channel['total_views']); ?> views</span>
                                    </div>
                                </div>

                                <a href="view.php?id=<?php echo $channel['id']; ?>" 
                                   class="explore-link">
                                    Explore Collection
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php 
        else:
            // No channels found message
            echo '<div class="alert alert-info">No collections available for ' . htmlspecialchars($userDepartment) . ' department.</div>';
        endif;
    endif; 
    ?>
</section>