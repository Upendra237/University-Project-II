<?php
// admin/student/dashboard.php
session_start();
require_once '../../auth/session.php';
require_once '../../config/database.php';
checkLogin();
checkUserType('student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Repository Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --header-height: 70px;
        }

        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .main-content {
            margin-left: var(--sidebar-min-width);
            padding-top: var(--header-height);
            min-height: 100vh;
            width: calc(100% - var(--sidebar-min-width));
            transition: all 0.2s ease;
            position: relative;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        .welcome-text {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: 600;
            text-align: center;
            margin: 2rem 1rem;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .main-content.expanded {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php 
    include '../../includes/components/sidebar.php';
    include '../../includes/components/admin-menu.php'; 
    ?>

    <div class="main-content">
        <div class="container-fluid">
            <div class="welcome-text">
                Welcome to Student Dashboard
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');

        // Function to update main content width
        function updateMainContentWidth() {
            if (sidebar.classList.contains('collapsed')) {
                mainContent.classList.add('expanded');
            } else {
                mainContent.classList.remove('expanded');
            }
        }

        // Initial width
        updateMainContentWidth();

        // Watch for sidebar changes
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    updateMainContentWidth();
                }
            });
        });

        observer.observe(sidebar, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Handle window resize
        window.addEventListener('resize', updateMainContentWidth);
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>