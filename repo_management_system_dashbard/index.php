<?php
// index.php
session_start();
require_once 'config/database.php';

// If user is already logged in, redirect to appropriate dashboard
if (isset($_SESSION['user_type'])) {
    $dashboard_path = match($_SESSION['user_type']) {
        'admin' => '/admin/admin/dashboard.php',
        'department' => '/admin/department/dashboard.php',
        'faculty' => '/admin/faculty/dashboard.php',
        'student' => '/admin/student/dashboard.php',
        default => '/index.php'
    };
    header("Location: $dashboard_path");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Repository Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                        url('assets/images/library-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }

        /* Feature Cards */
        .feature-card {
            border: none;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #6610f2;
        }

        /* Stats Counter */
        .stats-counter {
            background: #f8f9fa;
            padding: 60px 0;
        }

        .counter-item {
            text-align: center;
            padding: 20px;
        }

        .counter-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #6610f2;
        }

        /* Footer */
        .footer {
            background: #343a40;
            color: white;
            padding: 40px 0 20px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li a {
            color: #a1a1a1;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links li a:hover {
            color: white;
        }

        /* Navigation */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .nav-link {
            font-weight: 500;
        }

        /* Custom Buttons */
        .btn-repository {
            background-color: #6610f2;
            color: white;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-repository:hover {
            background-color: #520dc2;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-book"></i>
                College Repository
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="/auth/login.php" class="btn btn-repository">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 mb-4">College Repository Management System</h1>
            <p class="lead mb-4">A comprehensive digital library for research papers, theses, and academic resources.</p>
            <a href="/auth/login.php" class="btn btn-repository btn-lg">
                Get Started <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-search feature-icon"></i>
                            <h4>Easy Search</h4>
                            <p>Find research papers, theses, and academic resources with advanced search capabilities.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-cloud-upload feature-icon"></i>
                            <h4>Simple Upload</h4>
                            <p>Upload and manage your academic documents with just a few clicks.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-check feature-icon"></i>
                            <h4>Secure Access</h4>
                            <p>Role-based access control ensures your documents are safe and accessible.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Counter -->
    <section class="stats-counter">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="counter-item">
                        <div class="counter-number">5000+</div>
                        <div>Research Papers</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="counter-item">
                        <div class="counter-number">1000+</div>
                        <div>Theses</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="counter-item">
                        <div class="counter-number">500+</div>
                        <div>Faculty Members</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="counter-item">
                        <div class="counter-number">10000+</div>
                        <div>Students</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Contact Us</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-repository">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>About Us</h5>
                    <p>College Repository Management System is a comprehensive digital library solution for academic institutions.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="/auth/login.php">Login</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact Info</h5>
                    <ul class="footer-links">
                        <li><i class="bi bi-envelope me-2"></i> contact@college.edu</li>
                        <li><i class="bi bi-phone me-2"></i> (123) 456-7890</li>
                        <li><i class="bi bi-geadmino-alt me-2"></i> 123 College Street, City</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4 mb-3">
            <div class="text-center">
                <small>&copy; 2024 College Repository Management System. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>