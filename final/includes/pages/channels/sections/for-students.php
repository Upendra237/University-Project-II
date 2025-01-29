<section class="student-resources mb-5">
    <div class="section-header mb-4">
        <div class="d-flex align-items-center mb-2">
            <span class="featured-icon me-2">🎓</span>
            <h2 class="section-title mb-0">For Students</h2>
        </div>
        <p class="section-subtitle text-muted">Essential resources for your academic journey</p>
    </div>

    <!-- Resource Categories -->
    <div class="resource-grid">
        <?php
        $resources = [
            [
                'icon' => 'book',
                'title' => 'Course Materials',
                'description' => 'Access lecture notes, presentations, and study materials',
                'count' => '2.5K+',
                'color' => 'purple',
                'link' => '/channels/course-materials'
            ],
            [
                'icon' => 'file-earmark-text',
                'title' => 'Past Papers',
                'description' => 'Practice with previous exam papers and solutions',
                'count' => '500+',
                'color' => 'blue',
                'link' => '/channels/past-papers'
            ],
            [
                'icon' => 'journal-text',
                'title' => 'Study Resources',
                'description' => 'Additional learning materials and reference guides',
                'count' => '1.2K+',
                'color' => 'green',
                'link' => '/channels/study-resources'
            ],
            [
                'icon' => 'play-circle',
                'title' => 'Video Tutorials',
                'description' => 'Watch recorded lectures and practical demonstrations',
                'count' => '300+',
                'color' => 'red',
                'link' => '/channels/tutorials'
            ]
        ];

        foreach ($resources as $resource): ?>
            <div class="resource-card" data-color="<?php echo $resource['color']; ?>">
                <div class="card-content">
                    <div class="icon-wrapper bg-<?php echo $resource['color']; ?>-soft">
                        <i class="bi bi-<?php echo $resource['icon']; ?>"></i>
                    </div>
                    <h3 class="resource-title"><?php echo $resource['title']; ?></h3>
                    <p class="resource-description"><?php echo $resource['description']; ?></p>
                    <div class="resource-count">
                        <span class="count-number"><?php echo $resource['count']; ?></span>
                        <span class="count-label">resources</span>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo $resource['link']; ?>" class="resource-link">
                        <span>Browse Collection</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Recent Additions -->
    <?php 
    $recentQuery = "SELECT c.*, 
                    (SELECT COUNT(*) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id) as content_count
                    FROM `repo-channels` c 
                    WHERE c.status = 'active' 
                    AND c.template_type IN ('document', 'video', 'book')
                    ORDER BY c.created_at DESC 
                    LIMIT 3";
    $recentResources = $conn->query($recentQuery)->fetch_all(MYSQLI_ASSOC);
    
    if (!empty($recentResources)): ?>
        <div class="recent-resources mt-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="recent-title">Recent Additions</h3>
                    <p class="text-muted mb-0">Latest resources added to our collections</p>
                </div>
                <a href="/channels/recent" class="view-all-link">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="recent-grid">
                <?php foreach ($recentResources as $resource): ?>
                    <div class="recent-card">
                        <div class="recent-card-content">
                            <div class="recent-type">
                                <i class="bi bi-<?php echo getTypeIcon($resource['template_type']); ?>"></i>
                                <span><?php echo ucfirst($resource['template_type']); ?></span>
                            </div>
                            <h4 class="recent-title"><?php echo htmlspecialchars($resource['name']); ?></h4>
                            <p class="recent-description">
                                <?php echo htmlspecialchars(substr($resource['description'], 0, 100)) . '...'; ?>
                            </p>
                            <div class="recent-meta">
                                <span class="recent-count">
                                    <i class="bi bi-collection"></i>
                                    <?php echo $resource['content_count']; ?> items
                                </span>
                                <span class="recent-date">
                                    <i class="bi bi-calendar"></i>
                                    <?php echo date('M d, Y', strtotime($resource['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>