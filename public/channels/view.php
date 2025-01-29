<?php
$pageTitle = 'Channel View - Pustak';
require_once '../../config/init.php';
include ROOT_PATH . '/includes/components/header.php';


// Get channel ID
$channelId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$channelId) {
    header('Location: index.php');
    exit;
}

// Connect to database
$conn = connectDB();

// Get channel details with stats
$channelQuery = "SELECT c.*, 
                 (SELECT COUNT(*) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id) as content_count,
                 (SELECT SUM(views) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id) as total_views,
                 (SELECT SUM(downloads) FROM `repo-channel_content` cc WHERE cc.channel_id = c.id) as total_downloads
                 FROM `repo-channels` c 
                 WHERE c.id = ? AND c.status = 'active'";

$stmt = $conn->prepare($channelQuery);
$stmt->bind_param('i', $channelId);
$stmt->execute();
$channel = $stmt->get_result()->fetch_assoc();

if (!$channel) {
    header('Location: index.php');
    exit;
}

// Get channel content with pagination and filters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 12;
$offset = ($page - 1) * $itemsPerPage;

$contentQuery = "SELECT * FROM `repo-channel_content` 
                WHERE channel_id = ? AND status = 'published'
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?";

$stmt = $conn->prepare($contentQuery);
$stmt->bind_param('iii', $channelId, $itemsPerPage, $offset);
$stmt->execute();
$content = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get total content count for pagination
$totalQuery = "SELECT COUNT(*) as total FROM `repo-channel_content` 
              WHERE channel_id = ? AND status = 'published'";
$stmt = $conn->prepare($totalQuery);
$stmt->bind_param('i', $channelId);
$stmt->execute();
$totalItems = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);
?>

<div class="channel-view">
    <!-- Channel Header -->
    <?php include ROOT_PATH . '/includes/pages/channels/components/channel-header.php'; ?>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar with Channel Info -->
            <div class="col-lg-3">
                <?php include ROOT_PATH . '/includes/pages/channels/components/channel-sidebar.php'; ?>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                <!-- Content Filters -->
                <?php include ROOT_PATH . '/includes/pages/channels/components/content-filters.php'; ?>

                <!-- Content Grid -->
                <div class="content-grid">
                    <?php 
                    $templateFile = ROOT_PATH . '/includes/pages/channels/templates/' . 
                                  $channel['template_type'] . '-content.php';
                    
                    if (file_exists($templateFile)) {
                        include $templateFile;
                    } else {
                        include ROOT_PATH . '/includes/pages/channels/templates/default-content.php';
                    }
                    ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <?php include ROOT_PATH . '/includes/pages/channels/components/pagination.php'; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
$conn->close();
include ROOT_PATH . '/includes/components/footer.php'; 
?>