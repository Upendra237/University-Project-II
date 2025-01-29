// File: includes/pages/channels/components/channel-card.php

<?php
function renderChannelCard($channel) {
    $typeIcon = getTypeIcon($channel['template_type']);
    $cardClass = 'channel-card channel-card-' . $channel['template_type'];
?>
    <div class="<?php echo $cardClass; ?>">
        <div class="card h-100">
            <div class="card-preview">
                <?php if ($channel['preview_image']): ?>
                    <img src="<?php echo ROOT_URL; ?>assets/images/channels/<?php echo htmlspecialchars($channel['preview_image']); ?>"
                         class="card-img-top" alt="<?php echo htmlspecialchars($channel['name']); ?>">
                <?php else: ?>
                    <div class="placeholder-preview">
                        <i class="bi bi-<?php echo $typeIcon; ?>"></i>
                    </div>
                <?php endif; ?>
                
                <div class="card-badges">
                    <span class="type-badge">
                        <i class="bi bi-<?php echo $typeIcon; ?>"></i>
                        <?php echo ucfirst($channel['template_type']); ?>
                    </span>
                    <?php if ($channel['access_type'] === 'restricted'): ?>
                        <span class="access-badge">
                            <i class="bi bi-lock"></i>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <h5 class="card-title">
                    <?php echo htmlspecialchars($channel['name']); ?>
                </h5>
                <p class="card-text">
                    <?php echo htmlspecialchars($channel['description']); ?>
                </p>
                
                <div class="channel-stats">
                    <div class="stat-item">
                        <i class="bi bi-collection"></i>
                        <span><?php echo $channel['content_count']; ?> items</span>
                    </div>
                    <div class="stat-item">
                        <i class="bi bi-eye"></i>
                        <span><?php echo number_format($channel['total_views']); ?></span>
                    </div>
                    <div class="stat-item">
                        <i class="bi bi-download"></i>
                        <span><?php echo number_format($channel['total_downloads']); ?></span>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-outline-primary w-100" 
                        onclick="window.location.href='view.php?id=<?php echo $channel['id']; ?>'">
                    Browse Collection
                </button>
            </div>
        </div>
    </div>
<?php
}
?>