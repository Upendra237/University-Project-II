<?php
$pageTitle = 'Student Projects - Pustak';
include 'includes/header.php';

// Initialize variables with default values
$projectsData = [];
$filteredProjects = [];
$departments = [];
$years = [];
$supervisors = [];
$projectTypes = [];
$allKeywords = [];

// Load and validate JSON data
$jsonFile = __DIR__ . '/assets/data/projects.json';
if (file_exists($jsonFile)) {
    $jsonData = file_get_contents($jsonFile);
    if ($jsonData !== false) {
        $projectsData = json_decode($jsonData, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            // Initialize filteredProjects with all projects initially
            $filteredProjects = $projectsData;
            
            // Extract unique values for filters
            $departments = array_unique(array_column($projectsData, 'department'));
            $years = array_unique(array_column($projectsData, 'year'));
            $supervisors = array_unique(array_column($projectsData, 'supervisor'));
            $projectTypes = array_unique(array_column($projectsData, 'project_type'));
            
            foreach ($projectsData as $project) {
                if (isset($project['keywords'])) {
                    $allKeywords = array_merge($allKeywords, $project['keywords']);
                }
            }
            $allKeywords = array_unique($allKeywords);
            sort($allKeywords);
            rsort($years);
            sort($supervisors);
            sort($projectTypes);
        } else {
            error_log("JSON decode error in projects.json: " . json_last_error_msg());
        }
    } else {
        error_log("Could not read file: $jsonFile");
    }
} else {
    error_log("File not found: $jsonFile");
}

// Get filter parameters
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$selectedDepartment = isset($_GET['department']) ? $_GET['department'] : '';
$selectedYear = isset($_GET['year']) ? $_GET['year'] : '';
$selectedSupervisor = isset($_GET['supervisor']) ? $_GET['supervisor'] : '';
$selectedSemester = isset($_GET['semester']) ? $_GET['semester'] : '';
$selectedProjectType = isset($_GET['project_type']) ? $_GET['project_type'] : '';
$selectedKeywords = isset($_GET['keywords']) ? (array)$_GET['keywords'] : [];
$minRating = isset($_GET['min_rating']) ? floatval($_GET['min_rating']) : 0;
$maxRating = isset($_GET['max_rating']) ? floatval($_GET['max_rating']) : 5;

// Filter projects based on selected criteria
if (!empty($projectsData)) {
    $filteredProjects = array_filter($projectsData, function($project) use (
        $searchQuery, $selectedDepartment, $selectedYear, 
        $selectedSupervisor, $selectedSemester, $selectedProjectType,
        $selectedKeywords, $minRating, $maxRating
    ) {
        // Search query filter
        if (!empty($searchQuery)) {
            $searchText = strtolower($project['title'] . ' ' . 
                         $project['team_members'] . ' ' . 
                         $project['supervisor'] . ' ' . 
                         implode(' ', $project['technologies_used']) . ' ' . 
                         $project['description']);
            if (stripos($searchText, strtolower($searchQuery)) === false) {
                return false;
            }
        }

        // Department filter
        if (!empty($selectedDepartment) && $project['department'] !== $selectedDepartment) {
            return false;
        }

        // Year filter
        if (!empty($selectedYear) && $project['year'] != $selectedYear) {
            return false;
        }

        // Supervisor filter
        if (!empty($selectedSupervisor) && $project['supervisor'] !== $selectedSupervisor) {
            return false;
        }

        // Semester filter
        if (!empty($selectedSemester) && $project['semester'] !== $selectedSemester) {
            return false;
        }

        // Project Type filter
        if (!empty($selectedProjectType) && $project['project_type'] !== $selectedProjectType) {
            return false;
        }

        // Rating range filter
        if ($project['rating'] < $minRating || $project['rating'] > $maxRating) {
            return false;
        }

        return true;
    });

    // Sort filtered results
    if (!empty($sortBy)) {
        usort($filteredProjects, function($a, $b) use ($sortBy) {
            switch ($sortBy) {
                case 'title-asc':
                    return strcasecmp($a['title'], $b['title']);
                case 'title-desc':
                    return strcasecmp($b['title'], $a['title']);
                case 'newest':
                    return $b['year'] - $a['year'];
                case 'oldest':
                    return $a['year'] - $b['year'];
                case 'rating-high':
                    return $b['rating'] - $a['rating'];
                case 'downloads-high':
                    return $b['downloads'] - $a['downloads'];
                case 'views-high':
                    return $b['views'] - $a['views'];
                default:
                    return 0;
            }
        });
    }
}
?>

<div class="container-fluid py-1">
    <div class="row">
        <!-- Left Sidebar with Advanced Search -->
        <div class="col-lg-3">
            <?php include 'includes/pages/projects/advanced-search.php'; ?>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9">
            <?php include 'includes/pages/projects/projects-header.php'; ?>
            <?php include 'includes/pages/projects/project-list.php'; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>