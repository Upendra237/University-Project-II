<?php
if(isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $department = htmlspecialchars($_POST['department']);
    $stakeholder = htmlspecialchars($_POST['stakeholder']);
    $message = htmlspecialchars($_POST['message']);
    
    // Email configuration (replace with actual email handling)
    $to = "info@khec.edu.np";
    $subject = "New Contact Form Submission";
    $headers = "From: " . $email;
    
    // Send email (implement proper email handling)
    // mail($to, $subject, $message, $headers);
    
    $success = "Your message has been sent successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Khwopa Engineering College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #003366;
            --secondary-color: #666;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px 0;
            margin-bottom: 40px;
        }

        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .contact-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .contact-info i {
            color: var(--primary-color);
            font-size: 24px;
            margin-right: 10px;
        }

        .map-container {
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
        }

        .form-control {
            border-radius: 5px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }

        .college-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .social-links {
            margin-top: 20px;
        }

        .social-links a {
            color: var(--primary-color);
            margin-right: 15px;
            font-size: 20px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="contact-container">
            <h1 class="text-center mb-0">Contact Us</h1>
        </div>
    </div>

    <div class="contact-container">
        <?php if(isset($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="contact-card">
                    <h3 class="mb-4">College Information</h3>
                    
                    <div class="college-info">
                        <h4>Khwopa Engineering College</h4>
                        <p>An Undertaking of Bhaktapur Municipality<br>
                        Affiliated to Purbanchal University, Estd. 2001</p>
                        <p class="text-muted fst-italic">Dedicated To Country & People</p>
                    </div>

                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> P.O. Box: 84, Libali, Bhaktapur - 8<br>
                        Bagmati, Nepal</p>
                        
                        <p><i class="fas fa-phone"></i> +977-1-5122098, +977-1-5122094</p>
                        
                        <p><i class="fas fa-envelope"></i> info@khec.edu.np<br>
                        khec.pu@gmail.com</p>
                    </div>

                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="contact-card">
                    <h3 class="mb-4">Send us a Message</h3>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Department/Section</label>
                            <select class="form-select" id="department" name="department">
                                <option value="general">General</option>
                                <option value="civil">Civil Engineering</option>
                                <option value="computer">Computer Engineering</option>
                                <option value="electronics">Electronics & Communication</option>
                                <option value="architecture">Architecture</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="stakeholder" class="form-label">Stakeholder Type *</label>
                            <select class="form-select" id="stakeholder" name="stakeholder" required>
                                <option value="">Select Stakeholder Type</option>
                                <option value="student">Student</option>
                                <option value="parent">Parent</option>
                                <option value="faculty">Faculty</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="contact-card map-container mb-4">
            <!-- Replace src with actual Google Maps embed code -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3533.0237458894543!2d85.4262!3d27.6725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb1acea47f4c2f%3A0x8f27c7a29f43b2e5!2sKhwopa%20Engineering%20College!5e0!3m2!1sen!2snp!4v1638347689087!5m2!1sen!2snp" 
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="contact-container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light">Home</a></li>
                        <li><a href="#" class="text-light">About Us</a></li>
                        <li><a href="#" class="text-light">Courses</a></li>
                        <li><a href="#" class="text-light">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Khwopa Circle</h5>
                    <ul class="list-unstyled">
                        <li>KHWOPA COLLEGE OF ENGINEERING</li>
                        <li>KHWOPA COLLEGE</li>
                        <li>KHWOPA SECONDARY SCHOOL</li>
                        <li>KHWOPA POLYTECHNIC INSTITUTE</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2024 Khwopa Engineering College. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>