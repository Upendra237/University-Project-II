<?php
require_once __DIR__ . '/../../config/init.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pustak - <?php echo $pageTitle ?? 'Welcome'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Other Functionalities -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/assets/css/main.css" rel="stylesheet">
    <link href="/assets/css/pages/thesis/thesis.css" rel="stylesheet">
    <link href="/assets/css/pages/channels/channels.css" rel="stylesheet">
    <link href="/assets/css/pages/channels/sections/featured.css" rel="stylesheet">
    <link href="/assets/css/pages/channels/sections/for-students.css" rel="stylesheet">
    <link href="/assets/css/pages/channels/sections/trending.css" rel="stylesheet">
    <link href="/assets/css/pages/channels/sections/department.css" rel="stylesheet">
    <link href="/assets/css/pages/channels/filter-modal.css" rel="stylesheet">
    <link href="/assets/css/pages/channels/search.css" rel="stylesheet">
    <link href="/assets/css/pages/channels/sidebar.css" rel="stylesheet">

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark header-gradient">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="/">
            <i class="bi bi-book me-2"></i> PUSTAK
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item mx-1">
                    <a class="nav-link px-3 <?php echo ($pageTitle == 'Home') ? 'active' : ''; ?>" href="/">
                        <i class="bi bi-house me-1"></i> Home
                    </a>
                </li>

                <li class="nav-item mx-1">
                    <a class="nav-link px-3 <?php echo ($pageTitle == 'Student Projects - Pustak') ? 'active' : ''; ?>" href="/projects.php">
                        <i class="bi bi-lightbulb me-1"></i> Projects
                    </a>
                </li>

                <li class="nav-item mx-1">
                    <a class="nav-link px-3 <?php echo ($pageTitle == 'Thesis Papers - Pustak') ? 'active' : ''; ?>" href="/public/thesis/index.php">
                        <i class="bi bi-journal-text me-1"></i> Thesis
                    </a>
                </li>

                <li class="nav-item mx-1">
                    <a class="nav-link px-3 <?php echo ($pageTitle == 'Resources') ? 'active' : ''; ?>" href="/resources.php">
                        <i class="bi bi-archive me-1"></i> Resources
                    </a>
                </li>

                <li class="nav-item mx-1">
                    <a class="nav-link px-3 <?php echo ($pageTitle == 'Collections') ? 'active' : ''; ?>" href="public/channels/index.php">
                        <i class="bi bi-collection me-1"></i> Collections
                    </a>
                </li>

                <li class="nav-item mx-1">
                    <a class="nav-link px-3 <?php echo ($pageTitle == 'Login') ? 'active' : ''; ?>" href="/auth/login.php">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content Container -->
<main class="container py-4">