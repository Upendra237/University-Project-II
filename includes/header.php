<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pustak - <?php echo $pageTitle ?? 'Welcome'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="/assets/css/custom.css" rel="stylesheet">
</head>
<body>
<!-- includes/header.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-book"></i> PUSTAK
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($pageTitle == 'Home') ? 'active' : ''; ?>" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($pageTitle == 'Resources') ? 'active' : ''; ?>" href="/resources.php">Resources</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($pageTitle == 'Collections') ? 'active' : ''; ?>" href="/collections.php">Collections</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($pageTitle == 'Reader') ? 'active' : ''; ?>" href="/reader.php">Reader</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($pageTitle == 'About') ? 'active' : ''; ?>" href="/about.php">About</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <!-- Main Content Container -->
    <main class="container py-4">
