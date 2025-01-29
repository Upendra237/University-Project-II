<?php
// includes/components/sidebar.php
$user_type = $_SESSION['user_type'] ?? '';
$current_page = $_SERVER['PHP_SELF'];

// Define menu structure based on user type
$menu_items = [];

switch($user_type) {
    case 'admin':
        $menu_items = [
            [
                'title' => 'Dashboard',
                'icon' => 'bi bi-speedometer2',
                'link' => '/admin/admin/dashboard.php'
            ],
            [
                'title' => 'User Management',
                'icon' => 'bi bi-people',
                'submenu' => [
                    ['title' => 'All Users', 'link' => '/admin/admin/manage_staff.php'],
                    ['title' => 'Add User', 'link' => '/admin/admin/add_user.php'],
                    ['title' => 'User Roles', 'link' => '/admin/admin/manage_roles.php']
                ]
            ],
            [
                'title' => 'Repository',
                'icon' => 'bi bi-archive',
                'submenu' => [
                    ['title' => 'All Documents', 'link' => '/admin/admin/documents.php'],
                    ['title' => 'Pending Approval', 'link' => '/admin/admin/pending_docs.php'],
                    ['title' => 'Categories', 'link' => '/admin/admin/categories.php']
                ]
            ],
            [
                'title' => 'Reports',
                'icon' => 'bi bi-graph-up',
                'link' => '/admin/admin/reports.php'
            ],
            [
                'title' => 'Settings',
                'icon' => 'bi bi-gear',
                'link' => '/admin/admin/settings.php'
            ]
        ];
        break;
        
        case 'student':
            $menu_items = [
                [
                    'title' => 'Home',
                    'icon' => 'bi bi-house',
                    'link' => '/admin/student/dashboard.php'
                ],
                [
                    'title' => 'Projects',
                    'icon' => 'bi bi-kanban',
                    'submenu' => [
                        ['title' => 'All Projects', 'link' => '/admin/student/projects.php'],
                        ['title' => 'My Projects', 'link' => '/admin/student/my-projects.php'],
                        ['title' => 'Submit Project', 'link' => '/admin/student/submit-project.php'],
                        ['title' => 'Project Guidelines', 'link' => '/admin/student/project-guidelines.php']
                    ]
                ],
                [
                    'title' => 'Thesis',
                    'icon' => 'bi bi-journal-text',
                    'submenu' => [
                        ['title' => 'Browse Thesis', 'link' => '/admin/student/browse-thesis.php'],
                        ['title' => 'My Thesis', 'link' => '/admin/student/my-thesis.php'],
                        ['title' => 'Submit Thesis', 'link' => '/admin/student/submit-thesis.php'],
                        ['title' => 'Thesis Format', 'link' => '/admin/student/thesis-format.php']
                    ]
                ],
                [
                    'title' => 'Resources',
                    'icon' => 'bi bi-folder2-open',
                    'submenu' => [
                        ['title' => 'Research Papers', 'link' => '/admin/student/research-papers.php'],
                        ['title' => 'Reference Materials', 'link' => '/admin/student/references.php'],
                        ['title' => 'Templates', 'link' => '/admin/student/templates.php'],
                        ['title' => 'Useful Links', 'link' => '/admin/student/useful-links.php']
                    ]
                ],
                [
                    'title' => 'Collections',
                    'icon' => 'bi bi-bookmarks',
                    'submenu' => [
                        ['title' => 'Saved Items', 'link' => '/admin/student/saved-items.php'],
                        ['title' => 'Downloads', 'link' => '/admin/student/downloads.php'],
                        ['title' => 'Recent Views', 'link' => '/admin/student/recent-views.php'],
                        ['title' => 'My Notes', 'link' => '/admin/student/my-notes.php']
                    ]
                ],
                [
                    'title' => 'Profile',
                    'icon' => 'bi bi-person',
                    'link' => '/admin/student/profile.php'
                ],
                [
                    'title' => 'Settings',
                    'icon' => 'bi bi-gear',
                    'link' => '/admin/student/settings.php'
                ]
            ];
            break;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="/assets/css/components/sidebar.css" rel="stylesheet">

<div class="sidebar" id="sidebar">
    <div class="sidebar-resize-handle" id="sidebarResize"></div>
    
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-chevron-left"></i>
    </button>
    
    <div class="sidebar-header">
        <div class="college-logo">
            <img src="/assets/images/logo/khec-logo.png" alt="KHEC">
        </div>
        <div class="college-info">
            <h1 class="college-name">KHEC</h1>
            <div class="college-subtitle">Repository System</div>
        </div>
    </div>
    
    <div class="sidebar-menu">
        <?php foreach($menu_items as $item): ?>
            <div class="menu-item">
                <?php if(isset($item['submenu'])): ?>
                    <a href="#" 
                       class="menu-link" 
                       data-submenu="submenu-<?php echo str_replace(' ', '-', strtolower($item['title'])); ?>">
                        <span class="menu-icon">
                            <i class="<?php echo $item['icon']; ?>"></i>
                        </span>
                        <span class="menu-text"><?php echo $item['title']; ?></span>
                        <i class="bi bi-chevron-down menu-arrow"></i>
                    </a>
                    <div class="submenu" id="submenu-<?php echo str_replace(' ', '-', strtolower($item['title'])); ?>">
                        <?php foreach($item['submenu'] as $submenu): ?>
                            <a href="<?php echo $submenu['link']; ?>" 
                               class="menu-link <?php echo strpos($current_page, $submenu['link']) !== false ? 'active' : ''; ?>">
                                <span class="menu-text"><?php echo $submenu['title']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <a href="<?php echo $item['link']; ?>" 
                       class="menu-link <?php echo strpos($current_page, $item['link']) !== false ? 'active' : ''; ?>">
                        <span class="menu-icon">
                            <i class="<?php echo $item['icon']; ?>"></i>
                        </span>
                        <span class="menu-text"><?php echo $item['title']; ?></span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div class="menu-item mt-auto">
            <a href="/auth/logout.php" class="menu-link text-danger">
                <span class="menu-icon">
                    <i class="bi bi-box-arrow-right"></i>
                </span>
                <span class="menu-text">Logout</span>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mainContent = document.querySelector('.main-content');
    const resizeHandle = document.getElementById('sidebarResize');
    const toggleIcon = sidebarToggle.querySelector('i');
    let isResizing = false;

    // Toggle sidebar
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        toggleIcon.style.transform = sidebar.classList.contains('collapsed') ? 'rotate(180deg)' : '';
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    });

    // Submenu functions
    function closeSubmenu(submenu) {
        if (!submenu) return;
        const height = submenu.scrollHeight;
        submenu.style.height = height + 'px';
        submenu.offsetHeight; // Force reflow
        submenu.style.height = '0';
        
        const arrow = submenu.previousElementSibling.querySelector('.menu-arrow');
        arrow.style.transform = '';
        
        setTimeout(() => {
            submenu.classList.remove('show');
            submenu.style.height = '';
        }, 100);
    }

    function openSubmenu(submenu) {
        if (!submenu) return;
        submenu.classList.add('show');
        const height = submenu.scrollHeight;
        submenu.style.height = '0';
        submenu.offsetHeight; // Force reflow
        submenu.style.height = height + 'px';
        
        const arrow = submenu.previousElementSibling.querySelector('.menu-arrow');
        arrow.style.transform = 'rotate(180deg)';
        
        setTimeout(() => {
            submenu.style.height = '';
        }, 100);
    }

    // Handle submenu clicks
    const menuLinks = document.querySelectorAll('.menu-link[data-submenu]');
    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const submenuId = this.getAttribute('data-submenu');
            const submenu = document.getElementById(submenuId);
            const currentlyOpen = document.querySelector('.submenu.show');
            
            if (currentlyOpen && currentlyOpen !== submenu) {
                closeSubmenu(currentlyOpen);
            }
            
            if (submenu.classList.contains('show')) {
                closeSubmenu(submenu);
            } else {
                openSubmenu(submenu);
            }
        });
    });

    // Close submenus when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.menu-link[data-submenu]')) {
            const openSubmenu = document.querySelector('.submenu.show');
            if (openSubmenu) {
                closeSubmenu(openSubmenu);
            }
        }
    });

    // Resize functionality
    const startResize = (e) => {
        isResizing = true;
        sidebar.classList.add('resizing');
        document.body.style.cursor = 'ew-resize';
        e.preventDefault();
    };
    
    const doResize = (e) => {
        if (!isResizing || sidebar.classList.contains('collapsed')) return;
        
        const newWidth = Math.max(
            parseInt(getComputedStyle(document.documentElement).getPropertyValue('--sidebar-min-width')),
            Math.min(e.clientX, parseInt(getComputedStyle(document.documentElement).getPropertyValue('--sidebar-max-width')))
        );
        
        sidebar.style.width = `${newWidth}px`;
        mainContent.style.marginLeft = `${newWidth}px`;
    };
    
    const stopResize = () => {
        if (!isResizing) return;
        
        isResizing = false;
        sidebar.classList.remove('resizing');
        document.body.style.cursor = '';
        localStorage.setItem('sidebarWidth', sidebar.style.width);
    };

    // Resize event listeners
    resizeHandle.addEventListener('mousedown', startResize);
    document.addEventListener('mousemove', doResize);
    document.addEventListener('mouseup', stopResize);
    document.addEventListener('mouseleave', stopResize);

    // Prevent text selection during resize
    document.addEventListener('selectstart', (e) => {
        if (isResizing) e.preventDefault();
    });

    // Restore previous state
    const savedCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    const savedWidth = localStorage.getItem('sidebarWidth');
    
    if (savedCollapsed) {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
        toggleIcon.style.transform = 'rotate(180deg)';
    }
    
    if (savedWidth && !savedCollapsed) {
        sidebar.style.width = savedWidth;
        mainContent.style.marginLeft = savedWidth;
    }

    // Responsive handling
    const handleResize = () => {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('expanded');
            sidebar.style.width = '';
            mainContent.style.marginLeft = '';
            toggleIcon.style.transform = '';
        }
    };

    window.addEventListener('resize', handleResize);
    handleResize();
});
</script>