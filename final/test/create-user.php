<?php
// test/create-user.php
require_once '../config/database.php';

$message = '';
$userTypes = ['admin', 'department', 'faculty', 'student'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = $_POST['user_type'];
    
    if (in_array($user_type, $userTypes)) {
        // Generate random username
        $random_string = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5);
        $username = $user_type . '_' . $random_string;
        
        // Use a simple password for testing
        $password = "Test@123";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate email
        $email = $username . "@example.com";
        
        // Generate full name
        $full_name = ucfirst($user_type) . " " . ucfirst($random_string);
        
        $conn = connectDB();
        
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, full_name, user_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $hashed_password, $email, $full_name, $user_type);
        
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>
                User created successfully!<br>
                Username: {$username}<br>
                Password: Test@123<br>
                User Type: {$user_type}
            </div>";
        } else {
            $message = "<div class='alert alert-danger'>Error creating user: " . $conn->error . "</div>";
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
    <title>Create Test User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Test User</h4>
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="user_type" class="form-label">Select User Type</label>
                                <select class="form-select" name="user_type" id="user_type" required>
                                    <option value="">Select User Type</option>
                                    <?php foreach($userTypes as $type): ?>
                                        <option value="<?php echo $type; ?>"><?php echo ucfirst($type); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Random User</button>
                            <a href="list_users.php" class="btn btn-secondary">View All Users</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>