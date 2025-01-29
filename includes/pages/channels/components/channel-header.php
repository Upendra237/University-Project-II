// File: includes/pages/channels/components/channel-header.php

<div class="channel-header">
    <div class="channel-cover" 
         style="background-image: url('<?php echo $channel['preview_image'] ? 
               ROOT_URL . 'assets/images/channels/' . htmlspecialchars($channel['preview_image']) : 
               ROOT_URL . 'assets/images/channels/default-cover.jpg'; ?>')">
        <div class="channel-overlay">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/channels">Collections</a>
                        </li>
                        <?php if ($channel['department']): ?>
                            <li class="breadcrumb-item">
                                <a href="/channels?department=<?php echo urlencode($channel['department']); ?>">
                                    <?php echo htmlspecialchars($channel['department']); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php echo htmlspecialchars($channel['name']); ?>
                        </li>
                    </ol>
                </nav>

                <div class="channel-info">
                    <div class="channel-meta">
                        <span class="channel-type">
                            <i class="bi bi-<?php echo getTypeIcon($channel['template_type']); ?>"></i>
                            <?php echo ucfirst($channel['template_type']); ?>
                        </span>
                        <?php if ($channel['access_type'] === 'restricted'): ?>
                            <span class="channel-access">
                                <i class="bi bi-lock"></i> Restricted Access
                            </span>
                        <?php endif; ?>
                    </div>

                    <h1 class="channel-title">
                        <?php echo htmlspecialchars($channel['name']); ?>
                    </h1>

                    <p class="channel-description">
                        <?php echo htmlspecialchars($channel['description']); ?>
                    </p>

                    <div class="channel-stats">
                        <div class="stat-item">
                            <i class="bi bi-collection"></i>
                            <span><?php echo number_format($channel['content_count']); ?> Items</span>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-eye"></i>
                            <span><?php echo number_format($channel['total_views']); ?> Views</span>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-download"></i>
                            <span><?php echo number_format($channel['total_downloads']); ?> Downloads</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
