<?php
$pageTitle = 'Thesis Papers - Pustak';
require_once '../../config/init.php';
include ROOT_PATH . '/includes/components/header.php';


// Load the thesis data from the JSON file
$thesisData = json_decode(file_get_contents(ROOT_PATH . '/assets/data/thesis.json'), true);

// Get filter parameters
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$selectedDepartment = isset($_GET['department']) ? $_GET['department'] : '';
$selectedYear = isset($_GET['year']) ? $_GET['year'] : '';
$selectedAuthor = isset($_GET['author']) ? $_GET['author'] : '';
$selectedKeywords = isset($_GET['keywords']) ? (array)$_GET['keywords'] : [];
$minRating = isset($_GET['min_rating']) ? floatval($_GET['min_rating']) : 0;
$maxRating = isset($_GET['max_rating']) ? floatval($_GET['max_rating']) : 5;
$displayType = isset($_GET['display']) ? $_GET['display'] : 'landscape';

// Prepare filter data
$departments = array_unique(array_column($thesisData, 'department'));
$years = array_unique(array_column($thesisData, 'year'));
rsort($years);
$authors = array_unique(array_column($thesisData, 'author'));
sort($authors);

$allKeywords = [];
foreach ($thesisData as $thesis) {
    $allKeywords = array_merge($allKeywords, $thesis['keywords']);
}
$allKeywords = array_unique($allKeywords);
sort($allKeywords);

// Filter thesis data
$filteredThesis = array_filter($thesisData, function($thesis) use (
    $searchQuery, $selectedDepartment, $selectedYear, 
    $selectedAuthor, $selectedKeywords, $minRating, $maxRating
) {
    // Search query filter
    if (!empty($searchQuery)) {
        $searchText = strtolower($thesis['title'] . ' ' . $thesis['author'] . ' ' . 
                     implode(' ', $thesis['keywords']) . ' ' . $thesis['description']);
        if (stripos($searchText, strtolower($searchQuery)) === false) {
            return false;
        }
    }

    // Department filter
    if (!empty($selectedDepartment) && $thesis['department'] !== $selectedDepartment) {
        return false;
    }

    // Year filter
    if (!empty($selectedYear) && $thesis['year'] != $selectedYear) {
        return false;
    }

    // Author filter
    if (!empty($selectedAuthor) && $thesis['author'] !== $selectedAuthor) {
        return false;
    }

    // Keywords filter
    if (!empty($selectedKeywords)) {
        $hasKeyword = false;
        foreach ($selectedKeywords as $keyword) {
            if (in_array($keyword, $thesis['keywords'])) {
                $hasKeyword = true;
                break;
            }
        }
        if (!$hasKeyword) {
            return false;
        }
    }

    // Rating range filter
    if ($thesis['rating'] < $minRating || $thesis['rating'] > $maxRating) {
        return false;
    }

    return true;
});

// Sort filtered results
if (!empty($sortBy)) {
    usort($filteredThesis, function($a, $b) use ($sortBy) {
        switch ($sortBy) {
            case 'title-asc':
                return strcasecmp($a['title'], $b['title']);
            case 'title-desc':
                return strcasecmp($b['title'], $a['title']);
            case 'author-asc':
                return strcasecmp($a['author'], $b['author']);
            case 'author-desc':
                return strcasecmp($b['author'], $a['author']);
            case 'year-desc':
                return $b['year'] - $a['year'];
            case 'year-asc':
                return $a['year'] - $b['year'];
            case 'downloads-desc':
                return $b['downloads'] - $a['downloads'];
            case 'rating-desc':
                return $b['rating'] - $a['rating'];
            default:
                return 0;
        }
    });
}
?>

<div class="container-fluid py-1">
    <div class="row">
        <!-- Left Sidebar with Advanced Search -->
        <div class="col-lg-3">
            <?php include ROOT_PATH . '/includes/pages/thesis/advanced-search.php'; ?>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9">
            <?php include ROOT_PATH . '/includes/pages/thesis/thesis-header.php'; ?>

            <?php if ($displayType == 'landscape'): ?>
                <?php include ROOT_PATH . '/includes/pages/thesis/landscape-preview.php'; ?>
            <?php elseif ($displayType == 'portrait'): ?>
                <?php include ROOT_PATH . '/includes/pages/thesis/portrait-preview.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include ROOT_PATH . '/includes/components/footer.php'; ?>
