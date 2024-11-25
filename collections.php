<?php
$pageTitle = 'Channels - Pustak';
include 'includes/header.php';

// Load the collections data from the JSON file
$collections = json_decode(file_get_contents('assets/data/collections.json'), true);

// Check if a specific collection was requested
$requestedCollection = isset($_GET['channel']) ? $_GET['channel'] : null;
$currentCollection = null;

foreach ($collections as $collection) {
    if ($collection['slug'] == $requestedCollection) {
        $currentCollection = $collection;
        break;
    }
}

// Redirect to the dedicated page if the collection has a dedicated page
if ($currentCollection && $currentCollection['slug'] === 'thesis-papers') {
    header('Location: thesis.php');
    exit;
} elseif ($currentCollection && $currentCollection['hasDedicatedPage']) {
    header('Location: ' . ($currentCollection['slug'] . '.php'));
    exit;
}
?>

<div class="container py-5">
    <?php if ($currentCollection) { ?>
        <?php include 'layouts/collection.php'; ?>
    <?php } else { ?>
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1>Explore Our Channels</h1>
            <div class="d-flex gap-3">
                <button class="btn btn-outline-primary">
                    <i class="bi bi-grid-fill"></i> Grid View
                </button>
                <button class="btn btn-outline-primary">
                    <i class="bi bi-list"></i> List View
                </button>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($collections as $collection) { ?>
            <div class="col-md-4">
                <a href="?channel=<?php echo $collection['slug']; ?>" class="channel-card">
                    <div class="channel-header">
                        <img src="<?php echo $collection['image']; ?>" alt="<?php echo $collection['title']; ?>">
                    </div>
                    <div class="channel-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="channel-title"><?php echo $collection['title']; ?></h3>
                            <span class="badge bg-primary rounded-pill"><?php echo count($collection['resources']); ?></span>
                        </div>
                        <p class="channel-description"><?php echo $collection['description']; ?></p>
                        <div class="channel-stats">
                            <span><i class="bi bi-calendar"></i> Updated <?php echo date('F j, Y', filemtime('assets/data/collections.json')); ?></span>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<?php include 'includes/footer.php'; ?>