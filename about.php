<!-- about.php -->
<?php
$pageTitle = 'About';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4">About Pustak</h1>
                <p class="lead">Empowering academic research through digital accessibility</p>
            </div>
            <div class="col-md-6">
                <img src="assets/images/about-hero.jpg" alt="Library" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h2 class="mb-4">Our Mission</h2>
                <p class="lead">To create an accessible digital repository of academic resources for ABC Engineering College, fostering research and knowledge sharing within our academic community.</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Grid -->
<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">What Makes Us Different</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-book display-4 text-primary mb-3"></i>
                        <h4>Digital Collection</h4>
                        <p>Access to thousands of academic resources including thesis papers, research papers, and books.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-laptop display-4 text-primary mb-3"></i>
                        <h4>Enhanced Reading</h4>
                        <p>Interactive flipbook interface with note-taking and highlighting capabilities.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people display-4 text-primary mb-3"></i>
                        <h4>Community Driven</h4>
                        <p>Built by students, for students, focusing on academic excellence.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Our Team</h2>
        <div class="row g-4">
            <!-- Team Member -->
            <div class="col-md-3">
                <div class="card team-card">
                    <img src="assets/images/placeholder-profile.jpg" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-1">John Doe</h5>
                        <p class="text-muted">Lead Developer</p>
                        <div class="social-links">
                            <a href="#" class="text-dark me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-dark me-2"><i class="bi bi-github"></i></a>
                            <a href="#" class="text-dark"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add more team members -->
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-5">Get in Touch</h2>
                <div class="card">
                    <div class="card-body">
                        <form id="contactForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="col-12">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" required>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" rows="5" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Location Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>Visit Us</h2>
                <p class="lead">ABC Engineering College</p>
                <address class="mb-4">
                    123 College Road<br>
                    Kathmandu, Nepal<br>
                    <br>
                    <strong>Phone:</strong> (977) 1-234567<br>
                    <strong>Email:</strong> info@pustak.edu.np
                </address>
            </div>
            <div class="col-md-6">
                <!-- Embed a map or add a custom image -->
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d56516.31397712412!2d85.3261629!3d27.7172453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb198a307baabf%3A0xb5137c1bf18db1ea!2sKathmandu%2044600%2C%20Nepal!5e0!3m2!1sen!2s!4v1699728190400!5m2!1sen!2s" 
                            style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
