<?php
$pageTitle = $collection['title'] . ' - Digital Academic Repository';
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="mb-2"><?php echo $collection['title']; ?></h1>
            <p class="lead"><?php echo $collection['description']; ?></p>
        </div>
        <div class="d-flex gap-3">
            <a href="collections.php" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Channels
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Sort By
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Newest</a></li>
                    <li><a class="dropdown-item" href="#">Oldest</a></li>
                    <li><a class="dropdown-item" href="#">Title (A-Z)</a></li>
                    <li><a class="dropdown-item" href="#">Title (Z-A)</a></li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    View As
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Grid</a></li>
                    <li><a class="dropdown-item" href="#">List</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <?php foreach ($collection['resources'] as $resource) { ?>
        <div class="col-md-4">
            <div class="card h-100 resource-card">
                <div class="card-preview">
                    <img src="assets/images/previews/<?php echo $resource['preview']; ?>" class="card-img-top" alt="<?php echo $resource['title']; ?>">
                    <div class="preview-overlay">
                        <a href="reader/?id=<?php echo $resource['id']; ?>" class="btn btn-primary">Read Now</a>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $resource['title']; ?></h5>
                    <p class="card-text"><?php echo $resource['description']; ?></p>
                    <div class="meta-info">
                        <span><i class="bi bi-person"></i> <?php echo $resource['author']; ?></span>
                        <span><i class="bi bi-calendar"></i> <?php echo $resource['year']; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
