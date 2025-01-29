// File: includes/pages/channels/templates/document-content.php

<div class="document-content" data-view="grid">
    <div class="row g-4">
        <?php foreach ($content as $item): ?>
            <div class="col-sm-6 col-lg-4">
                <div class="document-item">
                    <div class="document-preview">
                        <?php 
                        $fileType = pathinfo($item['content_path'], PATHINFO_EXTENSION);
                        $previewIcon = getDocumentIcon($fileType);
                        ?>
                        <div class="document-icon">
                            <i class="bi bi-<?php echo $previewIcon; ?>"></i>
                            <span class="file-type"><?php echo strtoupper($fileType); ?></span>
                        </div>
                        
                        <div class="document-overlay">
                            <?php if ($item['access_type'] === 'open'): ?>
                                <a href="view.php?id=<?php echo $item['id']; ?>" class="btn btn-light btn-sm">
                                    <i class="bi bi-eye"></i> Preview
                                </a>
                                <a href="download.php?id=<?php echo $item['id']; ?>" class="btn btn-light btn-sm">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            <?php else: ?>
                                <button class="btn btn-light btn-sm" onclick="requestAccess(<?php echo $item['id']; ?>)">
                                    <i class="bi bi-lock"></i> Request Access
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="document-info">
                        <h5 class="document-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                        <?php if ($item['description']): ?>
                            <p class="document-description"><?php echo htmlspecialchars($item['description']); ?></p>
                        <?php endif; ?>

                        <!-- Document Metadata -->
                        <?php if ($item['metadata']): 
                            $metadata = json_decode($item['metadata'], true);
                        ?>
                            <div class="document-metadata">
                                <?php foreach ($metadata as $key => $value): ?>
                                    <div class="metadata-item">
                                        <span class="metadata-label"><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</span>
                                        <span class="metadata-value"><?php echo htmlspecialchars($value); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="document-stats">
                            <span title="Views">
                                <i class="bi bi-eye"></i> <?php echo number_format($item['views']); ?>
                            </span>
                            <span title="Downloads">
                                <i class="bi bi-download"></i> <?php echo number_format($item['downloads']); ?>
                            </span>
                            <span title="Upload Date">
                                <i class="bi bi-calendar"></i> 
                                <?php echo date('M d, Y', strtotime($item['created_at'])); ?>
                            </span>
                            <span title="File Size">
                                <i class="bi bi-file-earmark"></i> 
                                <?php echo formatFileSize($item['file_size']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
function getDocumentIcon($fileType) {
    $icons = [
        'pdf' => 'file-earmark-pdf',
        'doc' => 'file-earmark-word',
        'docx' => 'file-earmark-word',
        'xls' => 'file-earmark-excel',
        'xlsx' => 'file-earmark-excel',
        'ppt' => 'file-earmark-ppt',
        'pptx' => 'file-earmark-ppt',
        'txt' => 'file-earmark-text',
        'default' => 'file-earmark'
    ];
    return $icons[strtolower($fileType)] ?? $icons['default'];
}

function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, 1) . ' ' . $units[$pow];
}
?>
