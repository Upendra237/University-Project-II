<?php
// auth/login.php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../admin/index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;
    
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        $conn = connectDB();
        
        $stmt = $conn->prepare("SELECT id, username, password, user_type, full_name FROM users WHERE username = ? AND status = 'active'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            $error = "User not found";
        } else {
            $user = $result->fetch_assoc();
            
            if (!password_verify($password, $user['password'])) {
                $error = "Invalid password";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['full_name'] = $user['full_name'];
                
                if ($remember) {
                    setcookie('remember_token', base64_encode($user['id'] . ':' . $user['username']), time() + (86400 * 30), '/');
                }
                
                $dashboard_path = match($user['user_type']) {
                    'admin' => '../admin/admin/dashboard.php',
                    'department' => '../admin/department/dashboard.php',
                    'faculty' => '../admin/faculty/dashboard.php',
                    'student' => '../admin/student/dashboard.php',
                    default => '../index.php'
                };
                header("Location: $dashboard_path");
                exit();
            }
        }
        
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repository Management System - KHEC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/components/auth.css" rel="stylesheet">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <!-- College Logo and Title -->
            <div class="auth-header">
                <div class="college-logo">
                    <img src="../assets/images/logo/khec-logo.png" alt="KHEC Logo">
                </div>
                <h1 class="auth-title">Repository System</h1>
                <div class="college-name">Khwopa Engineering College</div>
                <div class="college-subtitle">Affiliated to Purbanchal University, Estd. 2001</div>
            </div>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
                <div class="auth-error">
                    <i class="bi bi-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="">
                <div class="form-floating">
                    <input type="text" 
                           class="form-control" 
                           id="username" 
                           name="username" 
                           placeholder="Username"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                           required>
                    <label for="username">Username</label>
                </div>

                <div class="form-floating position-relative">
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Password" 
                           required>
                    <label for="password">Password</label>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <button type="submit" class="auth-btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Log In
                </button>
            </form>

            <!-- Additional Links -->
            <div class="auth-links">
                <a href="forgot_password.php">Forgot Password?</a>
                <span class="mx-2">•</span>
                <a href="../index.php">Back to Homepage</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.classList.remove('bi-eye');
                toggleBtn.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleBtn.classList.remove('bi-eye-slash');
                toggleBtn.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>