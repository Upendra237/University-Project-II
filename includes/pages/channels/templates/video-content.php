// File: includes/pages/channels/templates/video-content.php

<div class="video-content" data-view="grid">
    <div class="row g-4">
        <?php foreach ($content as $item): ?>
            <div class="col-sm-6 col-lg-4">
                <div class="video-item">
                    <!-- Video Thumbnail -->
                    <div class="video-thumbnail">
                        <?php if ($item['preview_image']): ?>
                            <img src="<?php echo ROOT_URL; ?>uploads/channels/<?php echo htmlspecialchars($item['preview_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                                 class="thumbnail-img">
                        <?php else: ?>
                            <div class="thumbnail-placeholder">
                                <i class="bi bi-play-circle"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Duration Badge -->
                        <?php if ($item['metadata']): 
                            $metadata = json_decode($item['metadata'], true);
                            if (isset($metadata['duration'])):
                        ?>
                            <span class="duration-badge">
                                <?php echo formatDuration($metadata['duration']); ?>
                            </span>
                        <?php endif; endif; ?>

                        <!-- Play Button Overlay -->
                        <div class="video-overlay">
                            <button class="btn btn-play" onclick="playVideo(<?php echo htmlspecialchars(json_encode($item)); ?>)">
                                <i class="bi bi-play-circle-fill"></i>
                            </button>
                            <?php if ($item['access_type'] === 'open'): ?>
                                <div class="video-actions">
                                    <a href="download.php?id=<?php echo $item['id']; ?>" class="btn btn-light btn-sm">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                    <?php if (isset($metadata['transcript'])): ?>
                                        <button class="btn btn-light btn-sm" onclick="viewTranscript(<?php echo $item['id']; ?>)">
                                            <i class="bi bi-file-text"></i> Transcript
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Video Info -->
                    <div class="video-info">
                        <h5 class="video-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                        <?php if ($item['description']): ?>
                            <p class="video-description"><?php echo htmlspecialchars($item['description']); ?></p>
                        <?php endif; ?>

                        <!-- Video Metadata -->
                        <div class="video-metadata">
                            <?php if (isset($metadata)): ?>
                                <?php if (isset($metadata['presenter'])): ?>
                                    <div class="presenter">
                                        <i class="bi bi-person"></i> <?php echo htmlspecialchars($metadata['presenter']); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($metadata['topic'])): ?>
                                    <div class="topic">
                                        <i class="bi bi-tag"></i> <?php echo htmlspecialchars($metadata['topic']); ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="upload-date">
                                <i class="bi bi-calendar"></i> 
                                <?php echo date('M d, Y', strtotime($item['created_at'])); ?>
                            </div>
                        </div>

                        <!-- Video Stats -->
                        <div class="video-stats">
                            <span title="Views">
                                <i class="bi bi-eye"></i> <?php echo number_format($item['views']); ?>
                            </span>
                            <span title="Downloads">
                                <i class="bi bi-download"></i> <?php echo number_format($item['downloads']); ?>
                            </span>
                            <?php if (isset($metadata['quality'])): ?>
                                <span title="Quality">
                                    <i class="bi bi-badge-hd"></i> <?php echo htmlspecialchars($metadata['quality']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Video Player Modal -->
<div class="modal fade" id="videoPlayerModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="video-player-wrapper">
                    <video id="videoPlayer" class="video-js vjs-big-play-centered" controls>
                        <p class="vjs-no-js">
                            To view this video please enable JavaScript, and consider upgrading to a web browser that
                            <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                        </p>
                    </video>
                </div>
            </div>
            <div class="modal-footer border-0">
                <div class="video-info-modal"></div>
            </div>
        </div>
    </div>
</div>

<!-- Transcript Modal -->
<div class="modal fade" id="transcriptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Video Transcript</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="transcriptContent"></div>
            </div>
        </div>
    </div>
</div>

<?php
function formatDuration($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;

    if ($hours > 0) {
        return sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);
    }
    return sprintf("%d:%02d", $minutes, $seconds);
}
?>
