<?php
include 'includes/header.php';
session_start();
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database configuration
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "digital_library";

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    if (isset($_POST['login'])) {
        $email = $conn->real_escape_string($_POST['login_email']);
        $password = $_POST['login_password'];
        
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $success = "Login successful! Redirecting...";
                header("refresh:2;url=dashboard.php");
            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "User not found!";
        }
    }
    
    if (isset($_POST['register'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $conn->real_escape_string($_POST['role']);
        $department = $conn->real_escape_string($_POST['department']);
        $roll_number = $conn->real_escape_string($_POST['roll_number']);
        
        // Check if email already exists
        $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            $sql = "INSERT INTO users (name, email, password, role, department, roll_number) 
                    VALUES ('$name', '$email', '$password', '$role', '$department', '$roll_number')";
            
            if ($conn->query($sql) === TRUE) {
                $success = "Registration successful! Please login.";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Library - Login & Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            background: white;
            max-width: 900px;
            width: 90%;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            display: flex;
            margin: 20px;
        }

        .left-section {
            flex: 1;
            padding-right: 40px;
            border-right: 1px solid #eee;
        }

        .right-section {
            flex: 1;
            padding-left: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-section img {
            width: 120px;
            margin-bottom: 15px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
            border-color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78,115,223,0.4);
        }

        .social-login {
            margin-top: 20px;
            text-align: center;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            color: white;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            transform: translateY(-3px);
        }

        .error, .success {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error {
            background-color: #ffe6e6;
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .success {
            background-color: #e6ffe6;
            color: #198754;
            border: 1px solid #198754;
        }

        @media (max-width: 768px) {
            .form-container {
                flex-direction: column;
                padding: 20px;
            }

            .left-section {
                padding-right: 0;
                border-right: none;
                border-bottom: 1px solid #eee;
                padding-bottom: 20px;
                margin-bottom: 20px;
            }

            .right-section {
                padding-left: 0;
            }
        }

        .welcome-text {
            text-align: center;
            color: var(--secondary-color);
            margin-bottom: 30px;
        }

        .form-floating {
            margin-bottom: 15px;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 15px;
            cursor: pointer;
            color: var(--secondary-color);
        }

        .animate-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <!-- Left Section - Login -->
        <div class="left-section">
            <div class="logo-section">
                <img src="https://via.placeholder.com/120" alt="Logo">
                <h2>Welcome Back!</h2>
                <p class="text-muted">Please login to your account</p>
            </div>

            <?php if($error): ?>
                <div class="error animate-in"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="success animate-in"><?php echo $success; ?></div>
            <?php endif; ?>

            <form id="loginForm" method="POST" class="needs-validation animate-in" novalidate>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="login_email" name="login_email" placeholder="name@example.com" required>
                    <label for="login_email">Email address</label>
                </div>

                <div class="form-floating mb-3 position-relative">
                    <input type="password" class="form-control" id="login_password" name="login_password" placeholder="Password" required>
                    <label for="login_password">Password</label>
                    <i class="fas fa-eye-slash password-toggle" onclick="togglePassword('login_password', this)"></i>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="#" class="text-primary text-decoration-none">Forgot Password?</a>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100 mb-3">Login</button>

                <div class="text-center text-muted">
                    Don't have an account? <a href="#" onclick="showRegisterForm()" class="text-primary text-decoration-none">Register</a>
                </div>
            </form>
        </div>

        <!-- Right Section - Register -->
        <div class="right-section" id="registerSection" style="display: none;">
            <h3 class="text-center mb-4">Create New Account</h3>
            
            <form id="registerForm" method="POST" class="needs-validation animate-in" novalidate>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" required>
                    <label for="name">Full Name</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    <label for="email">Email address</label>
                </div>

                <div class="form-floating mb-3 position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <i class="fas fa-eye-slash password-toggle" onclick="togglePassword('password', this)"></i>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="roll_number" name="roll_number" placeholder="Roll Number">
                    <label for="roll_number">Roll Number (for students)</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="staff">Staff</option>
                    </select>
                    <label for="role">Role</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="department" name="department" required>
                        <option value="">Select Department</option>
                        <option value="cs">Computer Science</option>
                        <option value="ee">Electrical Engineering</option>
                        <option value="me">Mechanical Engineering</option>
                        <option value="ce">Civil Engineering</option>
                    </select>
                    <label for="department">Department</label>
                </div>

                <button type="submit" name="register" class="btn btn-primary w-100 mb-3">Register</button>

                <div class="text-center text-muted">
                    Already have an account? <a href="#" onclick="showLoginForm()" class="text-primary text-decoration-none">Login</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form toggle functionality
        function showRegisterForm() {
            document.querySelector('.left-section').style.display = 'none';
            document.getElementById('registerSection').style.display = 'block';
        }

        function showLoginForm() {
            document.querySelector('.left-section').style.display = 'block';
            document.getElementById('registerSection').style.display = 'none';
        }

        // Password toggle functionality
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        // Password strength check
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strength = checkPasswordStrength(password);
            this.classList.remove('is-valid', 'is-invalid');
            if (password.length > 0) {
                this.classList.add(strength >= 3 ? 'is-valid' : 'is-invalid');
            }
        });

        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            return strength;
        }
    </script>
</body>
</html>