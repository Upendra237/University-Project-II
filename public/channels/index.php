<?php
$pageTitle = 'Collections';
require_once '../../config/init.php';
include ROOT_PATH . '/includes/components/header.php';
include ROOT_PATH . '/config/database.php';

//tmp to check errors
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Connect to database
$conn = connectDB();

// Variables for features.php
$section = $_GET['section'] ?? 'home';

// Get filter parameters
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$selectedType = isset($_GET['type']) ? $_GET['type'] : '';
$selectedDepartment = isset($_GET['department']) ? $_GET['department'] : '';

// Get template types for filter
$templateTypes = ['gallery', 'document', 'book', 'video', 'dataset'];

// Get departments for filter
$deptQuery = "SELECT DISTINCT department FROM `repo-channels` WHERE status = 'active'";
$departments = $conn->query($deptQuery)->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid">
    <div class="row">
        <!-- Left Navigation -->
        <div class="col-lg-3 col-md-3 d-none d-md-block channels-sidebar">
            <?php include ROOT_PATH . '/includes/pages/channels/components/sidebar.php'; ?>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-9 channels-main">
            <!-- Search Bar -->
            <?php include ROOT_PATH . '/includes/pages/channels/components/search.php'; ?>

            <!-- Featured Channels -->
            <?php include ROOT_PATH . '/includes/pages/channels/sections/featured.php'; ?>

            <!-- For Students Section -->
            <?php include ROOT_PATH . '/includes/pages/channels/sections/for-students.php'; ?>

            <!-- Popular Channels -->
            <?php include ROOT_PATH . '/includes/pages/channels/sections/trending.php'; ?>

            <!-- Department Channels -->
            <?php include ROOT_PATH . '/includes/pages/channels/sections/department.php'; ?>
        </div>
    </div>
</div>

<?php 
$conn->close();
include ROOT_PATH . '/includes/components/footer.php'; 
?>