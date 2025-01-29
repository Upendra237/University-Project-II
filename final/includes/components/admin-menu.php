<?php
// includes/components/admin-menu.php

// Dummy notifications - Replace with actual database queries later
function getNotifications($user_id, $limit = 5) {
    return [
        [
            'id' => 1,
            'title' => 'New Document Uploaded',
            'message' => 'Research paper uploaded by John Doe',
            'created_at' => '2 mins ago',
            'read' => false,
            'icon' => 'bi-file-earmark-text',
            'color' => 'primary'
        ],
        [
            'id' => 2,
            'title' => 'System Update',
            'message' => 'System maintenance scheduled for tonight',
            'created_at' => '1 hour ago',
            'read' => true,
            'icon' => 'bi-gear',
            'color' => 'warning'
        ],
        [
            'id' => 3,
            'title' => 'New User Registration',
            'message' => 'New faculty member registered',
            'created_at' => '3 hours ago',
            'read' => false,
            'icon' => 'bi-person-plus',
            'color' => 'success'
        ]
    ];
}

$user_id = $_SESSION['user_id'] ?? 0;
$user_type = $_SESSION['user_type'] ?? '';
$user_name = $_SESSION['full_name'] ?? 'User';
$notifications = getNotifications($user_id);
$unread_count = count(array_filter($notifications, fn($n) => !$n['read']));

// Quick actions based on user type
$quick_actions = [];
switch($user_type) {
    case 'admin':
        $quick_actions = [
            ['icon' => 'bi-person-plus', 'text' => 'Add User', 'link' => '/admin/admin/add_user.php', 'color' => 'primary'],
            ['icon' => 'bi-file-earmark-plus', 'text' => 'Add Document', 'link' => '/admin/admin/add_document.php', 'color' => 'success'],
            ['icon' => 'bi-gear', 'text' => 'Settings', 'link' => '/admin/admin/settings.php', 'color' => 'info'],
            ['icon' => 'bi-graph-up', 'text' => 'Reports', 'link' => '/admin/admin/reports.php', 'color' => 'warning']
        ];
        break;
    case 'department':
        $quick_actions = [
            ['icon' => 'bi-person-plus', 'text' => 'Add Faculty', 'link' => '/admin/department/add-faculty.php', 'color' => 'primary'],
            ['icon' => 'bi-file-earmark-plus', 'text' => 'Add Document', 'link' => '/admin/department/add-document.php', 'color' => 'success'],
            ['icon' => 'bi-calendar-event', 'text' => 'Schedule', 'link' => '/admin/department/schedule.php', 'color' => 'info'],
            ['icon' => 'bi-graph-up', 'text' => 'Statistics', 'link' => '/admin/department/stats.php', 'color' => 'warning']
        ];
        break;
    case 'student':
        $quick_actions = [
            ['icon' => 'bi-file-earmark-plus', 'text' => 'Upload', 'link' => '/admin/student/upload.php', 'color' => 'primary'],
            ['icon' => 'bi-bookmark-plus', 'text' => 'Bookmark', 'link' => '/admin/student/bookmarks.php', 'color' => 'success'],
            ['icon' => 'bi-person', 'text' => 'Profile', 'link' => '/admin/student/profile.php', 'color' => 'info'],
            ['icon' => 'bi-clock-history', 'text' => 'History', 'link' => '/admin/student/history.php', 'color' => 'warning']
        ];
        break;
}

// Get current page title
function getCurrentPageTitle() {
    $path = $_SERVER['REQUEST_URI'];
    if (strpos($path, 'dashboard') !== false) return 'Dashboard';
    if (strpos($path, 'users') !== false) return 'User Management';
    if (strpos($path, 'documents') !== false) return 'Documents';
    return 'Dashboard';
}
?>

<style>
.admin-menu {
    background: white;
    height: 70px;
    display: flex;
    align-items: center;
    padding: 0 1.5rem;
    position: fixed;
    top: 0;
    right: 0;
    left: var(--sidebar-min-width);
    z-index: 999;
    transition: left 0.1s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.admin-menu.collapsed {
    left: var(--sidebar-collapsed-width);
}

/* Breadcrumb Section */
.admin-menu-breadcrumb {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
    min-width: 0;
}

.page-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.breadcrumb {
    margin: 0;
    font-size: 0.875rem;
}

/* Search Section */
.admin-menu-search {
    max-width: 300px;
    margin: 0 2rem;
}

.search-wrapper {
    position: relative;
    background: #f1f5f9;
    border-radius: 10px;
    transition: all 0.2s ease;
}

.search-wrapper:focus-within {
    background: white;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
}

.search-wrapper input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: none;
    background: transparent;
    font-size: 0.875rem;
    color: #1a1a1a;
}

.search-wrapper input:focus {
    outline: none;
}

.search-wrapper i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 1rem;
}

/* Action Buttons */
.admin-menu-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.action-btn {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    border: 1px solid transparent;
    background: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.2s ease;
    cursor: pointer;
}

.action-btn:hover {
    background: white;
    border-color: #e2e8f0;
    color: #1a1a1a;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    border-radius: 10px;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    border: 2px solid white;
    min-width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Dropdowns */
.dropdown-menu {
    margin-top: 10px;
    padding: 0.5rem;
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    animation: dropdownFade 0.2s ease;
}

@keyframes dropdownFade {
    from { 
        opacity: 0; 
        transform: translateY(10px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

/* Quick Actions Dropdown */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
    padding: 0.5rem;
    width: 320px;
}

.quick-action-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 10px;
    color: #64748b;
    text-decoration: none;
    transition: all 0.2s ease;
    background: #f8fafc;
}

.quick-action-btn:hover {
    background: #f1f5f9;
    color: #1a1a1a;
    transform: translateY(-1px);
}

.quick-action-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

/* Notifications Dropdown */
.notification-menu {
    width: 380px;
    padding: 0;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.notification-header h6 {
    font-weight: 600;
    margin: 0;
    color: #1a1a1a;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.notification-item:hover {
    background: #f8fafc;
}

.notification-item.unread {
    background: #f0f7ff;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-weight: 500;
    color: #1a1a1a;
    margin-bottom: 0.25rem;
}

.notification-message {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 0.25rem;
}

.notification-time {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Profile Dropdown */
.profile-menu {
    width: 300px;
    padding: 0;
}

.profile-header {
    padding: 1.5rem;
    text-align: center;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.profile-image {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin: 0 auto 1rem;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #64748b;
}

.profile-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.profile-role {
    display: inline-block;
    padding: 0.25rem 1rem;
    background: #e2e8f0;
    color: #64748b;
    border-radius: 20px;
    font-size: 0.875rem;
}

.profile-menu .dropdown-item {
    padding: 0.75rem 1rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border-radius: 8px;
    margin: 0.25rem;
}

.profile-menu .dropdown-item:hover {
    background: #f1f5f9;
    color: #1a1a1a;
}

.profile-menu .dropdown-item i {
    font-size: 1.1rem;
}

.profile-menu .text-danger:hover {
    background: #fef2f2;
    color: #ef4444;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .admin-menu {
        left: 0;
    }
    
    .admin-menu-search,
    .breadcrumb {
        display: none;
    }

    .page-title {
        font-size: 1.1rem;
    }

    .quick-actions {
        grid-template-columns: 1fr;
        width: 280px;
    }

    .notification-menu {
        width: 300px;
    }
}
</style>

<div class="admin-menu">
    <!-- Page Title & Breadcrumb -->
    <div class="admin-menu-breadcrumb">
        <h1 class="page-title"><?php echo getCurrentPageTitle(); ?></h1>
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?php echo getCurrentPageTitle(); ?></li>
            </ol>
        </nav>
    </div>

    <!-- Search -->
    <div class="admin-menu-search">
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search...">
        </div>
    </div>

<!-- Actions -->
<div class="admin-menu-actions">
                        <!-- Quick Actions -->
                        <div class="dropdown">
                            <button class="action-btn" data-bs-toggle="dropdown" title="Quick Actions">
                                <i class="bi bi-grid"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end p-2">
                                <div class="notification-header">
                                    <h6 class="mb-0">Quick Actions</h6>
                                </div>
                                <div class="quick-actions">
                                    <?php foreach($quick_actions as $action): ?>
                                        <a href="<?php echo $action['link']; ?>" class="quick-action-btn">
                                            <span class="quick-action-icon bg-soft-<?php echo $action['color']; ?>">
                                                <i class="bi <?php echo $action['icon']; ?>"></i>
                                            </span>
                                            <span class="quick-action-text"><?php echo $action['text']; ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="action-btn" data-bs-toggle="dropdown" title="Notifications">
                                <i class="bi bi-bell"></i>
                                <?php if($unread_count > 0): ?>
                                    <span class="notification-badge"><?php echo $unread_count; ?></span>
                                <?php endif; ?>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-menu">
                                <div class="notification-header">
                                    <h6 class="mb-0">Notifications</h6>
                                    <?php if($unread_count > 0): ?>
                                        <a href="#" class="text-primary text-decoration-none">Mark all read</a>
                                    <?php endif; ?>
                                </div>
                                <?php if(empty($notifications)): ?>
                                    <div class="p-4 text-center text-muted">
                                        <i class="bi bi-bell mb-2" style="font-size: 1.5rem;"></i>
                                        <p class="mb-0">No notifications</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach($notifications as $notification): ?>
                                        <div class="notification-item <?php echo !$notification['read'] ? 'unread' : ''; ?>">
                                            <div class="notification-icon bg-soft-<?php echo $notification['color']; ?>">
                                                <i class="bi <?php echo $notification['icon']; ?>"></i>
                                            </div>
                                            <div class="notification-content">
                                                <div class="notification-title"><?php echo $notification['title']; ?></div>
                                                <div class="notification-message"><?php echo $notification['message']; ?></div>
                                                <div class="notification-time"><?php echo $notification['created_at']; ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="p-2 text-center border-top">
                                        <a href="#" class="text-decoration-none">View all notifications</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Profile Menu -->
                        <div class="dropdown">
                            <button class="action-btn" data-bs-toggle="dropdown" title="Profile">
                                <i class="bi bi-person-circle"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end profile-menu">
                                <div class="profile-header">
                                    <div class="profile-image">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div class="profile-name"><?php echo htmlspecialchars($user_name); ?></div>
                                    <div class="profile-role"><?php echo ucfirst(htmlspecialchars($user_type)); ?></div>
                                </div>
                                <div class="p-2">
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-person"></i> My Profile
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-gear"></i> Settings
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-question-circle"></i> Help Center
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="/auth/logout.php">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const adminMenu = document.querySelector('.admin-menu');

    function updateMenuPosition() {
        requestAnimationFrame(() => {
            if (sidebar.classList.contains('collapsed')) {
                adminMenu.classList.add('collapsed');
            } else {
                adminMenu.classList.remove('collapsed');
            }
        });
    }

    // Initial position
    updateMenuPosition();

    // Watch for sidebar changes
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class' || mutation.attributeName === 'style') {
                updateMenuPosition();
            }
        });
    });

    observer.observe(sidebar, {
        attributes: true,
        attributeFilter: ['class', 'style']
    });

    // Update on window resize
    window.addEventListener('resize', updateMenuPosition);

    // Handle dropdowns
    const dropdowns = document.querySelectorAll('.dropdown-menu');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown')) {
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });
});
</script>