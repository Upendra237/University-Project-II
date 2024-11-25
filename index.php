<!-- index.php -->
<?php
$pageTitle = 'Home - Digital Academic Repository';
include 'includes/header.php';
?>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" aria-label="Hero Carousel">
    <!-- Carousel Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    
    <!-- Carousel Inner Content -->
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="assets/images/hero-img-1.jpg" class="d-block w-100" alt="Digital Library">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="display-4 fw-bold text-white">Welcome to Pustak</h1>
                <p class="lead text-white">Your Digital Academic Repository</p>
                <a href="#explore" class="btn btn-primary btn-lg shadow-lg">Explore Resources</a>
            </div>
        </div>

        <!-- Example of additional slides -->
        <div class="carousel-item">
            <img src="assets/images/hero-img-2.jpg" class="d-block w-100" alt="Study Material">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="display-4 fw-bold text-white">Study with Ease</h1>
                <p class="lead text-white">Access a Wide Range of Study Materials</p>
                <a href="#explore" class="btn btn-primary btn-lg shadow-lg">Browse More</a>
            </div>
        </div>

        <div class="carousel-item">
            <img src="assets/images/hero-img-3.jpg" class="d-block w-100" alt="Research Tools">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="display-4 fw-bold text-white">Research Tools & Resources</h1>
                <p class="lead text-white">Equip Yourself with the Best Resources for Research</p>
                <a href="#explore" class="btn btn-primary btn-lg shadow-lg">Get Started</a>
            </div>
        </div>
    </div>

    <!-- Carousel Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Previous Slide">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Next Slide">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>


<!-- Quick Stats -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="stat-item">
                    <i class="bi bi-book display-4"></i>
                    <h2 class="counter">500+</h2>
                    <p>Research Papers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <i class="bi bi-journal-text display-4"></i>
                    <h2 class="counter">200+</h2>
                    <p>Thesis Papers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <i class="bi bi-people display-4"></i>
                    <h2 class="counter">1000+</h2>
                    <p>Active Users</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <i class="bi bi-building display-4"></i>
                    <h2>ABC</h2>
                    <p>Engineering College</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Resources -->
<section id="explore" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Featured Resources</h2>
        <div class="row g-4">
            <!-- Resource Card -->
            <div class="col-md-4">
                <div class="card h-100 resource-card">
                    <div class="card-preview">
                        <img src="assets/images/sample-thesis.jpg" class="card-img-top" alt="Thesis Preview">
                        <div class="preview-overlay">
                            <a href="reader/?id=1" class="btn btn-primary">Read Now</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Machine Learning in IoT Applications</h5>
                        <p class="card-text">A comprehensive study on implementing ML algorithms in IoT devices.</p>
                        <div class="meta-info">
                            <span><i class="bi bi-person"></i> Dr. John Doe</span>
                            <span><i class="bi bi-calendar"></i> 2023</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add more resource cards -->
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Enhanced Reading Experience</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <i class="bi bi-book display-4 mb-3"></i>
                    <h4>FlipBook View</h4>
                    <p>Immersive reading experience with realistic page turns</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <i class="bi bi-pencil display-4 mb-3"></i>
                    <h4>Note Taking</h4>
                    <p>Add and organize your notes while reading</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <i class="bi bi-brightness-high display-4 mb-3"></i>
                    <h4>Dark/Light Mode</h4>
                    <p>Customize your reading environment</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <i class="bi bi-highlight display-4 mb-3"></i>
                    <h4>Highlighting</h4>
                    <p>Mark and save important passages</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Browse by Category</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="category-card">
                    <i class="bi bi-journal-richtext"></i>
                    <h4>Thesis Papers</h4>
                    <p>Browse student and faculty thesis papers</p>
                    <a href="#" class="stretched-link"></a>
                </div>
            </div>
            <!-- Add more categories -->
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h2>Start Exploring Today</h2>
        <p class="lead">Join our growing community of researchers and students</p>
        <button class="btn btn-light btn-lg">Get Started</button>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
