// File: includes/pages/channels/components/channel-sidebar.php

<div class="channel-sidebar sticky-top">
    <!-- Quick Stats Card -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Quick Stats</h5>
            <div class="quick-stats">
                <div class="stat-row">
                    <div class="stat-label">Total Items</div>
                    <div class="stat-value"><?php echo number_format($channel['content_count']); ?></div>
                </div>
                <div class="stat-row">
                    <div class="stat-label">Views</div>
                    <div class="stat-value"><?php echo number_format($channel['total_views']); ?></div>
                </div>
                <div class="stat-row">
                    <div class="stat-label">Downloads</div>
                    <div class="stat-value"><?php echo number_format($channel['total_downloads']); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Channel Details -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Channel Details</h5>
            <ul class="channel-details-list">
                <li>
                    <i class="bi bi-collection"></i>
                    <span class="detail-label">Type:</span>
                    <span class="detail-value"><?php echo ucfirst($channel['template_type']); ?></span>
                </li>
                <li>
                    <i class="bi bi-building"></i>
                    <span class="detail-label">Department:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($channel['department']); ?></span>
                </li>
                <li>
                    <i class="bi bi-calendar3"></i>
                    <span class="detail-label">Created:</span>
                    <span class="detail-value">
                        <?php echo date('M d, Y', strtotime($channel['created_at'])); ?>
                    </span>
                </li>
                <li>
                    <i class="bi bi-shield-check"></i>
                    <span class="detail-label">Access:</span>
                    <span class="detail-value">
                        <?php echo ucfirst($channel['access_type']); ?>
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Actions -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Actions</h5>
            <div class="d-grid gap-2">
                <?php if (isUserSubscribed($channel['id'])): ?>
                    <button class="btn btn-outline-primary" onclick="unsubscribeFromChannel(<?php echo $channel['id']; ?>)">
                        <i class="bi bi-bell-slash"></i> Unsubscribe
                    </button>
                <?php else: ?>
                    <button class="btn btn-primary" onclick="subscribeToChannel(<?php echo $channel['id']; ?>)">
                        <i class="bi bi-bell"></i> Subscribe
                    </button>
                <?php endif; ?>
                
                <button class="btn btn-outline-secondary" onclick="shareChannel(<?php echo $channel['id']; ?>)">
                    <i class="bi bi-share"></i> Share
                </button>
            </div>
        </div>
    </div>

    <!-- Related Channels -->
    <?php
    $relatedQuery = "SELECT c.*, 
                     (SELECT COUNT(*) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id) as content_count
                     FROM `repo-channels` c 
                     WHERE c.department = ? 
                     AND c.id != ? 
                     AND c.status = 'active' 
                     LIMIT 3";
    $stmt = $conn->prepare($relatedQuery);
    $stmt->bind_param('si', $channel['department'], $channel['id']);
    $stmt->execute();
    $relatedChannels = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    if (!empty($relatedChannels)):
    ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Related Channels</h5>
            <div class="related-channels">
                <?php foreach ($relatedChannels as $related): ?>
                    <a href="/channels/view.php?id=<?php echo $related['id']; ?>" 
                       class="related-channel-item">
                        <div class="related-channel-icon">
                            <i class="bi bi-<?php echo getTypeIcon($related['template_type']); ?>"></i>
                        </div>
                        <div class="related-channel-info">
                            <h6><?php echo htmlspecialchars($related['name']); ?></h6>
                            <span class="text-muted">
                                <?php echo $related['content_count']; ?> items
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
