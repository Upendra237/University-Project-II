<?php
$pageTitle = 'Academic Resources';
include 'includes/header.php';

// Initialize variables
$resources = [];
$categories = [];
$types = [];

// Load resources data with error handling
$jsonFile = __DIR__ . '/assets/data/resources.json';
if (file_exists($jsonFile)) {
    $jsonData = file_get_contents($jsonFile);
    if ($jsonData !== false) {
        $data = json_decode($jsonData, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $resources = $data['resources'] ?? [];
            $categories = $data['categories'] ?? [];
            $types = $data['types'] ?? [];
        } else {
            error_log("JSON decode error: " . json_last_error_msg());
        }
    } else {
        error_log("Could not read file: $jsonFile");
    }
} else {
    error_log("File not found: $jsonFile");
}

// Get filter parameters
$category = $_GET['category'] ?? '';
$type = $_GET['type'] ?? '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

// Filter resources based on parameters
if ($category) {
    $resources = array_filter($resources, fn($r) => $r['category'] === $category);
}
if ($type) {
    $resources = array_filter($resources, fn($r) => $r['type'] === $type);
}
if ($search) {
    $resources = array_filter($resources, function($r) use ($search) {
        return stripos($r['title'], $search) !== false || 
               stripos($r['description'], $search) !== false ||
               stripos($r['author'], $search) !== false;
    });
}

// Sort resources only if there are resources to sort
if (!empty($resources)) {
    switch ($sort) {
        case 'newest':
            usort($resources, fn($a, $b) => $b['year'] - $a['year']);
            break;
        case 'popular':
            usort($resources, fn($a, $b) => $b['views'] - $a['views']);
            break;
        case 'downloads':
            usort($resources, fn($a, $b) => $b['downloads'] - $a['downloads']);
            break;
        case 'rating':
            usort($resources, fn($a, $b) => $b['rating'] - $a['rating']);
            break;
    }
}
?>

<!-- Hero Section -->
<div class="resources-hero bg-primary text-white py-5">
    <div class="container">
        <h1 class="display-4">Academic Resources</h1>
        <p class="lead">Explore our collection of research papers, thesis works, and academic publications</p>
        
        <!-- Search Bar -->
        <form action="" method="GET" class="mt-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control form-control-lg" 
                           placeholder="Search resources..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select form-select-lg">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" 
                                    <?php echo $category === $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select form-select-lg">
                        <option value="">All Types</option>
                        <?php foreach ($types as $t): ?>
                            <option value="<?php echo htmlspecialchars($t); ?>" 
                                    <?php echo $type === $t ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($t); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-light btn-lg w-100">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Results Section -->
<div class="container py-5">
    <!-- Sort Options -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Found <?php echo count($resources); ?> resources</h5>
        </div>
        <div class="d-flex align-items-center">
            <label class="me-2">Sort by:</label>
            <select class="form-select" id="sortSelect" onchange="window.location.href=this.value">
                <?php
                $baseUrl = '?' . http_build_query(array_merge($_GET, ['sort' => null]));
                $sortOptions = [
                    'newest' => 'Newest First',
                    'popular' => 'Most Popular',
                    'downloads' => 'Most Downloaded',
                    'rating' => 'Highest Rated'
                ];
                foreach ($sortOptions as $value => $label) {
                    $selected = $sort === $value ? 'selected' : '';
                    echo "<option value='{$baseUrl}&sort={$value}' {$selected}>{$label}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <!-- Resources Grid -->
    <div class="row g-4">
        <?php if (!empty($resources)): ?>
            <?php foreach ($resources as $resource): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 resource-card">
                        <div class="card-preview">
                            <img src="<?php echo htmlspecialchars($resource['thumbnail']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($resource['title']); ?>">
                            <div class="preview-overlay">
                                <a href="reader/?id=<?php echo $resource['id']; ?>" class="btn btn-primary me-2">
                                    Read Now
                                </a>
                                <a href="<?php echo htmlspecialchars($resource['file_path']); ?>" 
                                   class="btn btn-outline-light">
                                    Download PDF
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary">
                                    <?php echo htmlspecialchars($resource['type']); ?>
                                </span>
                                <div class="rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star-fill <?php echo $i <= $resource['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <h5 class="card-title">
                                <?php echo htmlspecialchars($resource['title']); ?>
                            </h5>
                            <p class="card-text">
                                <?php echo htmlspecialchars($resource['description']); ?>
                            </p>
                            <div class="meta-info mt-3">
                                <span>
                                    <i class="bi bi-person"></i> 
                                    <?php echo htmlspecialchars($resource['author']); ?>
                                </span>
                                <span>
                                    <i class="bi bi-calendar"></i> 
                                    <?php echo $resource['year']; ?>
                                </span>
                            </div>
                            <div class="stats mt-2 text-muted">
                                <small>
                                    <i class="bi bi-eye"></i> 
                                    <?php echo number_format($resource['views']); ?> views
                                    <i class="bi bi-download ms-2"></i> 
                                    <?php echo number_format($resource['downloads']); ?> downloads
                                </small>
                            </div>
                            <div class="tags mt-3">
                                <?php foreach ($resource['tags'] as $tag): ?>
                                    <span class="badge bg-light text-dark">
                                        <?php echo htmlspecialchars($tag); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="bi bi-search display-1 text-muted"></i>
                <h3 class="mt-3">No resources found</h3>
                <p class="text-muted">Try adjusting your search or filter criteria</p>
                <a href="resources.php" class="btn btn-primary">Clear all filters</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
