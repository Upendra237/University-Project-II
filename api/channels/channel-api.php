// File: api/channels/channel-api.php

<?php
require_once '../../config/init.php';

header('Content-Type: application/json');

// Get request method and action
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    $conn = connectDB();
    
    switch ($method) {
        case 'GET':
            handleGetRequests($action, $conn);
            break;
            
        case 'POST':
            handlePostRequests($action, $conn);
            break;
            
        default:
            throw new Exception('Method not allowed', 405);
    }
    
} catch (Exception $e) {
    sendErrorResponse($e->getMessage(), $e->getCode() ?: 500);
}

function handleGetRequests($action, $conn) {
    switch ($action) {
        case 'content':
            getChannelContent($conn);
            break;
            
        case 'filter':
            filterContent($conn);
            break;
            
        case 'search':
            searchChannels($conn);
            break;
            
        default:
            throw new Exception('Invalid action', 400);
    }
}

function handlePostRequests($action, $conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($action) {
        case 'view':
            incrementViews($conn, $data);
            break;
            
        case 'download':
            incrementDownloads($conn, $data);
            break;
            
        default:
            throw new Exception('Invalid action', 400);
    }
}

function getChannelContent($conn) {
    $channelId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
    $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT) ?: 12;
    $sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING) ?: 'newest';
    
    if (!$channelId) {
        throw new Exception('Channel ID required', 400);
    }
    
    $offset = ($page - 1) * $limit;
    
    // Build query based on sort option
    $orderBy = match($sort) {
        'oldest' => 'created_at ASC',
        'popular' => 'views DESC',
        'downloads' => 'downloads DESC',
        default => 'created_at DESC'
    };
    
    $query = "SELECT * FROM `repo-channel_content` 
              WHERE channel_id = ? AND status = 'published' 
              ORDER BY $orderBy 
              LIMIT ? OFFSET ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iii', $channelId, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Get total count for pagination
    $countQuery = "SELECT COUNT(*) as total FROM `repo-channel_content` 
                  WHERE channel_id = ? AND status = 'published'";
    $countStmt = $conn->prepare($countQuery);
    $countStmt->bind_param('i', $channelId);
    $countStmt->execute();
    $totalCount = $countStmt->get_result()->fetch_assoc()['total'];
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $row['metadata'] = json_decode($row['metadata'], true);
        $items[] = $row;
    }
    
    sendResponse([
        'items' => $items,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => ceil($totalCount / $limit),
            'total_items' => $totalCount,
            'items_per_page' => $limit
        ]
    ]);
}

function filterContent($conn) {
    $channelId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $filters = $_GET['filters'] ?? [];
    
    if (!$channelId) {
        throw new Exception('Channel ID required', 400);
    }
    
    $whereConditions = ['channel_id = ?', "status = 'published'"];
    $params = [$channelId];
    $types = 'i';
    
    // Build where conditions based on filters
    if (!empty($filters)) {
        foreach ($filters as $key => $value) {
            switch ($key) {
                case 'date_from':
                    if ($value) {
                        $whereConditions[] = 'created_at >= ?';
                        $params[] = $value;
                        $types .= 's';
                    }
                    break;
                    
                case 'date_to':
                    if ($value) {
                        $whereConditions[] = 'created_at <= ?';
                        $params[] = $value;
                        $types .= 's';
                    }
                    break;
                    
                case 'type':
                    if ($value) {
                        $whereConditions[] = 'JSON_EXTRACT(metadata, "$.type") = ?';
                        $params[] = $value;
                        $types .= 's';
                    }
                    break;
                    
                // Add more filter conditions as needed
            }
        }
    }
    
    $whereClause = implode(' AND ', $whereConditions);
    $query = "SELECT * FROM `repo-channel_content` WHERE $whereClause";
    
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $row['metadata'] = json_decode($row['metadata'], true);
        $items[] = $row;
    }
    
    sendResponse(['items' => $items]);
}

function searchChannels($conn) {
    $query = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
    $type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
    $department = filter_input(INPUT_GET, 'department', FILTER_SANITIZE_STRING);
    
    $whereConditions = ["status = 'active'"];
    $params = [];
    $types = '';
    
    if ($query) {
        $whereConditions[] = '(name LIKE ? OR description LIKE ?)';
        $searchTerm = "%$query%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'ss';
    }
    
    if ($type) {
        $whereConditions[] = 'template_type = ?';
        $params[] = $type;
        $types .= 's';
    }
    
    if ($department) {
        $whereConditions[] = 'department = ?';
        $params[] = $department;
        $types .= 's';
    }
    
    $whereClause = implode(' AND ', $whereConditions);
    $query = "SELECT * FROM `repo-channels` WHERE $whereClause ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $channels = [];
    while ($row = $result->fetch_assoc()) {
        $channels[] = $row;
    }
    
    sendResponse(['channels' => $channels]);
}

function incrementViews($conn, $data) {
    validateContentAccess($conn, $data['content_id']);
    
    $query = "UPDATE `repo-channel_content` SET views = views + 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $data['content_id']);
    $stmt->execute();
    
    sendResponse(['success' => true]);
}

function incrementDownloads($conn, $data) {
    validateContentAccess($conn, $data['content_id']);
    
    $query = "UPDATE `repo-channel_content` SET downloads = downloads + 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $data['content_id']);
    $stmt->execute();
    
    sendResponse(['success' => true]);
}

function validateContentAccess($conn, $contentId) {
    $query = "SELECT c.access_type 
              FROM `repo-channel_content` cc 
              JOIN `repo-channels` c ON cc.channel_id = c.id 
              WHERE cc.id = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $contentId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if (!$result) {
        throw new Exception('Content not found', 404);
    }
    
    if ($result['access_type'] === 'restricted' && !isUserAuthorized()) {
        throw new Exception('Access denied', 403);
    }
}

function sendResponse($data) {
    echo json_encode($data);
    exit;
}

function sendErrorResponse($message, $code) {
    http_response_code($code);
    echo json_encode(['error' => $message]);
    exit;
}

function isUserAuthorized() {
    // Implement your authorization logic here
    return true; // Temporary return for demonstration
}
?>
