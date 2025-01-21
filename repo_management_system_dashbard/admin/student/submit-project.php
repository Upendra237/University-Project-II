<?php
// admin/student/submit-project.php
session_start();
require_once '../../auth/session.php';
require_once '../../config/database.php';
checkLogin();
checkUserType('student');

// Initialize variables
$errors = [];
$success = '';
$userData = [
    'department' => '',
    'semester' => ''
];

try {
    $conn = connectDB();
    
    // Get user data
    $stmt = $conn->prepare("SELECT department, semester FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
        }
        $stmt->close();
    }
} catch (Exception $e) {
    $errors[] = "Database connection error. Please try again later.";
    error_log("Error: " . $e->getMessage());
}

// List of departments
$departments = [
    'Computer Engineering',
    'Civil Engineering',
    'Electronics Engineering'
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = ['title', 'description', 'project_type', 'department', 'team_members', 'supervisor'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required";
            }
        }

        if (empty($errors)) {
            $conn = connectDB();
            
            // Set default values
            $year = date('Y');
            $status = isset($_POST['save_draft']) ? 'draft' : 'pending';

            // Prepare SQL
            $sql = "INSERT INTO repo_projects (
                        title, description, project_type, semester, department,
                        team_members, supervisor, year, github_url, demo_url,
                        status, submitted_by
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $semester = $_POST['semester'] ?? $userData['semester'] ?? '';
                $github_url = $_POST['github_url'] ?? null;
                $demo_url = $_POST['demo_url'] ?? null;

                $stmt->bind_param(
                    "sssssssisssi",
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['project_type'],
                    $semester,
                    $_POST['department'],
                    $_POST['team_members'],
                    $_POST['supervisor'],
                    $year,
                    $github_url,
                    $demo_url,
                    $status,
                    $_SESSION['user_id']
                );

                if ($stmt->execute()) {
                    $project_id = $conn->insert_id;
                    $_SESSION['project_id'] = $project_id; // Store for advanced form
                    $_SESSION['success_msg'] = "Project " . ($status === 'draft' ? 'saved as draft' : 'submitted') . " successfully!";
                    
                    if (isset($_POST['continue_to_advanced'])) {
                        header("Location: submit-project-advanced.php");
                    } else {
                        header("Location: my-projects.php");
                    }
                    exit();
                } else {
                    throw new Exception($stmt->error);
                }
            } else {
                throw new Exception($conn->error);
            }
        }
    } catch (Exception $e) {
        $errors[] = "Error saving project: " . $e->getMessage();
        error_log("Error in submit-project.php: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Project - Repository System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .main-content {
            padding-top: 80px;
        }
        .form-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .progress-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            position: relative;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
        }
        .step.active .step-circle {
            background: #0d6efd;
            color: white;
        }
        .step-line {
            position: absolute;
            top: 20px;
            height: 2px;
            background: #e9ecef;
            width: 100%;
            left: 0;
            z-index: 1;
        }
        .step-title {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
        .step.active .step-title {
            color: #0d6efd;
            font-weight: 500;
        }
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <?php 
    include '../../includes/components/sidebar.php';
    include '../../includes/components/admin-menu.php'; 
    ?>

    <div class="main-content">
        <div class="container">
            <!-- Progress Steps -->
            <div class="progress-indicator mb-4">
                <div class="row w-100 position-relative">
                    <div class="step active col-6">
                        <div class="step-circle">1</div>
                        <div class="step-title">Basic Details</div>
                    </div>
                    <div class="step col-6">
                        <div class="step-circle">2</div>
                        <div class="step-title">Additional Details</div>
                    </div>
                    <div class="step-line"></div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="form-card p-4">
                <h2 class="text-center mb-4">Submit Your Project</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" id="projectForm">
                    <!-- Basic Details -->
                    <div class="row">
                        <!-- Project Title -->
                        <div class="col-12 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="title" name="title" 
                                       placeholder="Enter project title" required
                                       value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                                <label for="title">Project Title</label>
                            </div>
                        </div>

                        <!-- Project Description -->
                        <div class="col-12 mb-3">
                            <div class="form-floating">
                                <textarea class="form-control" id="description" name="description" 
                                          style="height: 100px" placeholder="Enter project description" 
                                          required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                                <label for="description">Project Description</label>
                            </div>
                            <div class="form-text">Provide a brief overview of your project (100-200 words)</div>
                        </div>

                        <!-- Project Type & Department -->
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="project_type" name="project_type" required>
                                    <option value="">Select Project Type</option>
                                    <option value="Project I" <?php echo (isset($_POST['project_type']) && $_POST['project_type'] == 'Project I') ? 'selected' : ''; ?>>Project I (3rd Sem)</option>
                                    <option value="Project II" <?php echo (isset($_POST['project_type']) && $_POST['project_type'] == 'Project II') ? 'selected' : ''; ?>>Project II (5th Sem)</option>
                                    <option value="Project III" <?php echo (isset($_POST['project_type']) && $_POST['project_type'] == 'Project III') ? 'selected' : ''; ?>>Project III (7th Sem)</option>
                                    <option value="Project IV" <?php echo (isset($_POST['project_type']) && $_POST['project_type'] == 'Project IV') ? 'selected' : ''; ?>>Project IV (8th Sem)</option>
                                </select>
                                <label for="project_type">Project Type</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="department" name="department" required>
                                    <option value="">Select Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?php echo htmlspecialchars($dept); ?>" 
                                                <?php echo ($userData['department'] === $dept) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($dept); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="department">Department</label>
                            </div>
                        </div>

                        <!-- Team Members -->
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <textarea class="form-control" id="team_members" name="team_members" 
                                          style="height: 100px" placeholder="Enter team members" required><?php echo isset($_POST['team_members']) ? htmlspecialchars($_POST['team_members']) : ''; ?></textarea>
                                <label for="team_members">Team Members</label>
                            </div>
                            <div class="form-text">Enter team member names separated by commas</div>
                        </div>

<!-- Supervisor -->
<div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="supervisor" name="supervisor" 
                                       placeholder="Enter supervisor name" required
                                       value="<?php echo isset($_POST['supervisor']) ? htmlspecialchars($_POST['supervisor']) : ''; ?>">
                                <label for="supervisor">Project Supervisor</label>
                            </div>
                        </div>

                        <!-- GitHub & Demo URLs -->
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="url" class="form-control" id="github_url" name="github_url"
                                       placeholder="Enter GitHub URL"
                                       value="<?php echo isset($_POST['github_url']) ? htmlspecialchars($_POST['github_url']) : ''; ?>">
                                <label for="github_url">GitHub URL (Optional)</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="url" class="form-control" id="demo_url" name="demo_url"
                                       placeholder="Enter Demo URL"
                                       value="<?php echo isset($_POST['demo_url']) ? htmlspecialchars($_POST['demo_url']) : ''; ?>">
                                <label for="demo_url">Demo URL (Optional)</label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" name="save_draft" class="btn btn-outline-primary">
                            <i class="bi bi-save"></i> Save as Draft
                        </button>
                        <div class="d-flex gap-2">
                            <button type="submit" name="continue_to_advanced" class="btn btn-info">
                                Next: Additional Details <i class="bi bi-arrow-right"></i>
                            </button>
                            <button type="submit" name="submit_project" class="btn btn-primary">
                                <i class="bi bi-check2-circle"></i> Submit Project
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.getElementById('projectForm');
        const urlInputs = document.querySelectorAll('input[type="url"]');

        // Custom URL validation
        urlInputs.forEach(input => {
            input.addEventListener('change', function() {
                const url = this.value.trim();
                if (url !== '' && !url.match(/^(http|https):\/\/[^ "]+$/)) {
                    this.setCustomValidity('Please enter a valid URL starting with http:// or https://');
                } else {
                    this.setCustomValidity('');
                }
            });
        });

        // Handle form submission
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    </script>
</body>
</html>