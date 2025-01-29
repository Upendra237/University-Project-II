<?php
/**
 * Channel Helper Functions
 * Contains commonly used functions for channel-related operations
 */

/**
 * Get icon for template type
 */
function getTemplateIcon($type) {
    return match($type) {
        'gallery' => 'images',
        'document' => 'file-text',
        'video' => 'play-circle',
        'book' => 'book',
        'dataset' => 'database',
        default => 'collection'
    };
}

/**
 * Format file size to human readable format
 */
function formatFileSize($bytes) {
    if ($bytes === 0) return '0 B';
    
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    return round($bytes / (1024 ** $pow), 2) . ' ' . $units[$pow];
}

/**
 * Check if user has access to channel
 */
function hasChannelAccess($channelId, $userId = null) {
    global $conn;
    
    if (!$userId && isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    }

    // Public channels are accessible to all
    $query = "SELECT visibility, department FROM `repo-channels` WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $channelId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result['visibility'] === 'public') {
        return true;
    }

    // Check user-specific access
    if ($userId) {
        $userQuery = "SELECT department FROM users WHERE id = ?";
        $userStmt = $conn->prepare($userQuery);
        $userStmt->bind_param('i', $userId);
        $userStmt->execute();
        $userResult = $userStmt->get_result()->fetch_assoc();

        // Department-specific access
        if ($result['department'] === $userResult['department']) {
            return true;
        }
    }

    return false;
}

/**
 * Get channel content with pagination
 */
function getChannelContent($channelId, $page = 1, $limit = 12, $filters = []) {
    global $conn;
    
    $offset = ($page - 1) * $limit;
    $whereConditions = ['channel_id = ?', 'status = "published"'];
    $params = [$channelId];
    $types = 'i';

    // Apply filters
    if (!empty($filters)) {
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                switch ($key) {
                    case 'search':
                        $whereConditions[] = '(title LIKE ? OR description LIKE ?)';
                        $searchTerm = "%$value%";
                        $params[] = $searchTerm;
                        $params[] = $searchTerm;
                        $types .= 'ss';
                        break;
                    case 'date_from':
                        $whereConditions[] = 'created_at >= ?';
                        $params[] = $value;
                        $types .= 's';
                        break;
                    case 'date_to':
                        $whereConditions[] = 'created_at <= ?';
                        $params[] = $value;
                        $types .= 's';
                        break;
                    // Add more filter conditions as needed
                }
            }
        }
    }

    $whereClause = implode(' AND ', $whereConditions);
    $query = "SELECT * FROM `repo-channel_content` 
              WHERE $whereClause 
              ORDER BY created_at DESC 
              LIMIT ? OFFSET ?";
    
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $row['metadata'] = json_decode($row['metadata'], true);
        $items[] = $row;
    }

    // Get total count for pagination
    $countQuery = "SELECT COUNT(*) as total FROM `repo-channel_content` WHERE $whereClause";
    $stmt = $conn->prepare($countQuery);
    $stmt->bind_param(substr($types, 0, -2), ...array_slice($params, 0, -2));
    $stmt->execute();
    $totalCount = $stmt->get_result()->fetch_assoc()['total'];

    return [
        'items' => $items,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => ceil($totalCount / $limit),
            'total_items' => $totalCount,
            'items_per_page' => $limit
        ]
    ];
}

/**
 * Track channel or content view
 */
function trackView($type, $id) {
    global $conn;
    
    $table = $type === 'channel' ? 'repo-channels' : 'repo-channel_content';
    $query = "UPDATE `$table` SET views = views + 1 WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

/**
 * Get channel recommendations
 */
function getChannelRecommendations($userId, $limit = 5) {
    global $conn;
    
    // Get user's department and interests
    $userQuery = "SELECT department FROM users WHERE id = ?";
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $userDept = $stmt->get_result()->fetch_assoc()['department'];

    // Get popular channels from user's department
    $query = "SELECT c.*, 
             (SELECT COUNT(*) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id) as content_count
             FROM `repo-channels` c 
             WHERE c.department = ? 
             AND c.status = 'active'
             ORDER BY c.views DESC 
             LIMIT ?";
             
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $userDept, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $recommendations = [];
    while ($row = $result->fetch_assoc()) {
        $recommendations[] = $row;
    }

    return $recommendations;
}

/**
 * Format channel metadata for display
 */
function formatChannelMetadata($metadata) {
    if (!is_array($metadata)) {
        $metadata = json_decode($metadata, true);
    }

    $formatted = [];
    foreach ($metadata as $key => $value) {
        $label = ucwords(str_replace('_', ' ', $key));
        $formatted[] = [
            'label' => $label,
            'value' => is_array($value) ? implode(', ', $value) : $value
        ];
    }

    return $formatted;
}

/**
 * Check if content is downloadable
 */
function isContentDownloadable($contentId, $userId = null) {
    global $conn;
    
    $query = "SELECT c.access_type 
              FROM `repo-channel_content` cc 
              JOIN `repo-channels` c ON cc.channel_id = c.id 
              WHERE cc.id = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $contentId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result['access_type'] === 'open') {
        return true;
    }

    return hasChannelAccess($contentId, $userId);
}

/**
 * Generate secure download URL
 */
function generateDownloadUrl($contentId) {
    $token = bin2hex(random_bytes(32));
    $_SESSION['download_tokens'][$contentId] = [
        'token' => $token,
        'expires' => time() + 3600 // 1 hour expiry
    ];

    return "/download.php?id=$contentId&token=$token";
}

// Add this function at the bottom of the file or in your helper functions
function hasActiveFilters() {
    $filterParams = [
        'type',
        'department',
        'date_from',
        'date_to',
        'min_rating',
        'max_rating',
        'access_type'
    ];

    foreach ($filterParams as $param) {
        if (!empty($_GET[$param])) {
            return true;
        }
    }

    return false;
}

// Add this helper function as well since it's likely needed
function countActiveFilters() {
    $count = 0;
    $filterParams = [
        'type',
        'department',
        'date_from',
        'date_to',
        'min_rating',
        'max_rating',
        'access_type'
    ];

    foreach ($filterParams as $param) {
        if (!empty($_GET[$param])) {
            $count++;
        }
    }

    return $count;
}


/**
 * Get icon class for channel type
 */
function getTypeIcon($type) {
    return match(strtolower($type)) {
        'gallery' => 'bi-images',
        'document' => 'bi-file-text',
        'video' => 'bi-play-circle',
        'book' => 'bi-book',
        'dataset' => 'bi-database',
        'course' => 'bi-journal-text',
        'paper' => 'bi-file-earmark-text',
        'resource' => 'bi-archive',
        default => 'bi-collection'
    };
}

// File: includes/helpers/channel-helpers.php

function renderChannelCard($channel) {
    $bgImage = $channel['preview_image'] 
        ? ROOT_URL . 'assets/images/channels/' . htmlspecialchars($channel['preview_image'])
        : ROOT_URL . 'assets/images/channels/default-' . $channel['template_type'] . '.jpg';
    ?>
    <div class="channel-card <?php echo $channel['template_type']; ?>">
        <div class="channel-header" style="background-image: url('<?php echo $bgImage; ?>')">
            <div class="channel-content">
                <div class="channel-badge">
                    <i class="bi bi-<?php echo getTypeIcon($channel['template_type']); ?>"></i>
                    <?php echo ucfirst($channel['template_type']); ?>
                </div>
                
                <h3 class="channel-title"><?php echo htmlspecialchars($channel['name']); ?></h3>
                <p class="channel-description"><?php echo htmlspecialchars($channel['description']); ?></p>

                <div class="channel-stats">
                    <div class="stat-item">
                        <i class="bi bi-collection"></i>
                        <span><?php echo number_format($channel['content_count'] ?? 0); ?> items</span>
                    </div>
                    <div class="stat-item">
                        <i class="bi bi-eye"></i>
                        <span><?php echo number_format($channel['total_views'] ?? 0); ?> views</span>
                    </div>
                </div>

                <a href="view.php?id=<?php echo $channel['id']; ?>" class="explore-btn">
                    Explore Collection
                </a>
            </div>
        </div>
    </div>
    <?php
}