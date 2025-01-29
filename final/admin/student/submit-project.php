<?php
session_start();
require_once '../../auth/session.php';
require_once '../../config/database.php';
checkLogin();
checkUserType('student');

// Initialize variables
$errors = [];
$success = '';
$formData = [
    'title' => '',
    'description' => '',
    'project_type' => '',
    'department' => '',
    'team_members' => '',
    'supervisor' => '',
    'github_url' => '',
    'demo_url' => '',
    'technologies_used' => '',
    'full_description' => '',
    'key_features' => '',
    'project_outcomes' => '',
    'year' => date('Y')
];

// Get active tab from session or default to basic
$activeTab = $_SESSION['active_tab'] ?? 'basic';

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
            $formData['department'] = $userData['department'];
        }
        $stmt->close();
    }
} catch (Exception $e) {
    $errors[] = "Database connection error.";
    error_log("Error: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update formData with submitted values
    foreach ($formData as $key => $value) {
        if (isset($_POST[$key])) {
            $formData[$key] = $_POST[$key];
        }
    }

    $isDraft = isset($_POST['save_draft']);
    
    // Validate only required fields if not saving as draft
    if (!$isDraft) {
        $requiredFields = [
            'title' => 'Project Title',
            'description' => 'Project Description',
            'project_type' => 'Project Type',
            'department' => 'Department',
            'team_members' => 'Team Members',
            'supervisor' => 'Project Supervisor'
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($_POST[$field])) {
                $errors[] = "$label is required";
            }
        }

        // Additional validation
        if (strlen($_POST['description']) < 50) {
            $errors[] = "Project Description must be at least 50 characters long";
        }

        if (strlen($_POST['title']) < 5) {
            $errors[] = "Project Title must be at least 5 characters long";
        }
    }

    // Process form if no errors or saving as draft
    if (empty($errors) || $isDraft) {
        try {
            $conn = connectDB();
            
            $status = $isDraft ? 'draft' : 'pending';
            $year = date('Y');

            // Prepare JSON data
            $technologies = !empty($_POST['technologies_used']) ? 
                json_encode(array_values($_POST['technologies_used'])) : null;
            
            // Handle key features - ensure it's a valid JSON array
            $keyFeatures = !empty($_POST['key_features']) ? 
                json_encode(array_filter(array_map('trim', $_POST['key_features']))) : null;
            
            // Handle project outcomes
            $projectOutcomes = !empty($_POST['project_outcomes']) ? 
                json_encode(array_filter($_POST['project_outcomes'], function($item) {
                    return !empty($item['metric']) && !empty($item['value']);
                })) : null;

            // Handle architecture details
            $architectureDetails = null;
            if (!empty($_POST['architecture_components'])) {
                $components = array_filter($_POST['architecture_components'], function($comp) {
                    return !empty($comp['name']) && !empty($comp['description']);
                });
                if (!empty($components)) {
                    $architectureDetails = json_encode(['components' => $components]);
                }
            }

            // Handle implementation details
            $implementationDetails = null;
            if (!empty($_POST['implementation_details'])) {
                $hardware = $_POST['implementation_details']['hardware'] ?? [];
                $software = $_POST['implementation_details']['software'] ?? [];
                
                $implementationDetails = json_encode([
                    'hardware' => array_filter(array_map('trim', $hardware)),
                    'software' => array_filter(array_map('trim', $software))
                ]);
            }

            // Handle team roles
            $teamRoles = null;
            if (!empty($_POST['team_roles'])) {
                $roles = array_filter($_POST['team_roles'], function($role) {
                    return !empty($role['name']) && !empty($role['role']);
                });
                if (!empty($roles)) {
                    $teamRoles = json_encode($roles);
                }
            }

            // Handle project timeline
            $projectTimeline = null;
            if (!empty($_POST['project_timeline'])) {
                $timeline = $_POST['project_timeline'];
                if (!empty($timeline['start_date']) || !empty($timeline['end_date']) || !empty($timeline['milestones'])) {
                    $projectTimeline = json_encode($timeline);
                }
            }

            // Handle awards and recognition
            $awardsRecognition = !empty($_POST['awards_recognition']) ? 
                json_encode(array_filter(array_map('trim', $_POST['awards_recognition']))) : null;

            // Handle media gallery
            $mediaGallery = null;
            if (!empty($_FILES['media_gallery_images']) || !empty($_POST['media_gallery_videos'])) {
                $gallery = [
                    'images' => [],
                    'videos' => array_filter(array_map('trim', $_POST['media_gallery_videos'] ?? []))
                ];
                
                // Handle image uploads
                if (!empty($_FILES['media_gallery_images']['name'][0])) {
                    foreach ($_FILES['media_gallery_images']['name'] as $key => $value) {
                        if ($_FILES['media_gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                            $gallery['images'][] = $value;
                        }
                    }
                }
                
                if (!empty($gallery['images']) || !empty($gallery['videos'])) {
                    $mediaGallery = json_encode($gallery);
                }
            }

            $sql = "INSERT INTO repo_projects (
                        title, description, project_type, department,
                        team_members, supervisor, year, github_url, demo_url,
                        technologies_used, key_features, project_outcomes,
                        full_description, architecture_details, implementation_details,
                        team_roles, project_timeline, awards_recognition,
                        media_gallery, status, submitted_by
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param(
                    "ssssssisssssssssssssi",
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['project_type'],
                    $_POST['department'],
                    $_POST['team_members'],
                    $_POST['supervisor'],
                    $year,
                    $_POST['github_url'],
                    $_POST['demo_url'],
                    $technologies,
                    $keyFeatures,
                    $projectOutcomes,
                    $_POST['full_description'],
                    $architectureDetails,
                    $implementationDetails,
                    $teamRoles,
                    $projectTimeline,
                    $awardsRecognition,
                    $mediaGallery,
                    $status,
                    $_SESSION['user_id']
                );

                if ($stmt->execute()) {
                    $_SESSION['success_msg'] = [
                        'title' => $isDraft ? 'Draft Saved!' : 'Project Submitted!',
                        'message' => $isDraft 
                            ? 'Your project has been saved as a draft. You can continue editing it later.'
                            : 'Your project has been submitted successfully and is pending approval.',
                        'type' => 'success'
                    ];
                    header("Location: my-projects.php");
                    exit();
                } else {
                    throw new Exception($stmt->error);
                }
            }
        } catch (Exception $e) {
            $errors[] = "Error saving project: " . $e->getMessage();
            error_log("Error in submit-project.php: " . $e->getMessage());
        }
    }
}

// List of departments
$departments = [
    'Computer Engineering',
    'Civil Engineering',
    'Electronics Engineering'
];

// Project types
$projectTypes = [
    'Project I' => 'Project I (3rd Sem)',
    'Project II' => 'Project II (5th Sem)',
    'Project III' => 'Project III (7th Sem)',
    'Project IV' => 'Project IV (8th Sem)'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Project - Repository System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/pages/projects/submit-project.css" rel="stylesheet">
</head>
<body>
    <?php 
    include '../../includes/components/sidebar.php';
    include '../../includes/components/admin-menu.php'; 
    ?>

    <div class="main-content">
        <div class="container">
            <div class="form-card">
                <!-- Form Header -->
                <div class="form-header">
                    <h1 class="h3 mb-2">Submit Your Project</h1>
                    <p class="mb-0">Share your work with the academic community</p>
                </div>

                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger m-4">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" action="" id="projectForm" class="needs-validation" novalidate>
                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $activeTab === 'basic' ? 'active' : ''; ?>" 
                                    id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" 
                                    type="button" role="tab">
                                <i class="bi bi-file-text me-2"></i>Basic Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $activeTab === 'advanced' ? 'active' : ''; ?>" 
                                    id="advanced-tab" data-bs-toggle="tab" data-bs-target="#advanced" 
                                    type="button" role="tab">
                                <i class="bi bi-gear me-2"></i>Additional Details
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content p-4">
                    <!-- Basic Details Tab -->
                    <div class="tab-pane fade <?php echo $activeTab === 'basic' ? 'show active' : ''; ?>" 
                        id="basic" role="tabpanel">
                        <div class="row g-4">
                            <!-- Project Title -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="title" name="title" 
                                        placeholder="Enter project title" required minlength="5"
                                        value="<?php echo htmlspecialchars($formData['title']); ?>">
                                    <label class="required-field" for="title">Project Title</label>
                                    <div class="invalid-feedback">
                                        Please provide a project title (minimum 5 characters).
                                    </div>
                                </div>
                            </div>

                            <!-- Project Description -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="description" name="description" 
                                            style="height: 120px" placeholder="Enter project description" 
                                            required minlength="50"><?php echo htmlspecialchars($formData['description']); ?></textarea>
                                    <label class="required-field" for="description">Project Description</label>
                                    <div class="invalid-feedback">
                                        Please provide a project description (minimum 50 characters).
                                    </div>
                                </div>
                                <div class="form-text">Brief overview of your project (100-200 words)</div>
                            </div>

                            <!-- Project Type & Department -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="project_type" name="project_type" required>
                                        <option value="">Select Project Type</option>
                                        <?php foreach($projectTypes as $value => $label): ?>
                                            <option value="<?php echo $value; ?>" 
                                                <?php echo $formData['project_type'] === $value ? 'selected' : ''; ?>>
                                                <?php echo $label; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label class="required-field" for="project_type">Project Type</label>
                                    <div class="invalid-feedback">
                                        Please select a project type.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="department" name="department" required>
                                        <option value="">Select Department</option>
                                        <?php foreach($departments as $dept): ?>
                                            <option value="<?php echo $dept; ?>" 
                                                <?php echo $formData['department'] === $dept ? 'selected' : ''; ?>>
                                                <?php echo $dept; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label class="required-field" for="department">Department</label>
                                    <div class="invalid-feedback">
                                        Please select a department.
                                    </div>
                                </div>
                            </div>

                            <!-- Team Members -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <textarea class="form-control" id="team_members" name="team_members" 
                                            style="height: 100px" placeholder="Enter team members" required
                                            ><?php echo htmlspecialchars($formData['team_members']); ?></textarea>
                                    <label class="required-field" for="team_members">Team Members</label>
                                    <div class="invalid-feedback">
                                        Please enter team member names.
                                    </div>
                                </div>
                                <div class="form-text">Enter team member names separated by commas</div>
                            </div>

                            <!-- Supervisor -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="supervisor" name="supervisor" 
                                        placeholder="Enter supervisor name" required
                                        value="<?php echo htmlspecialchars($formData['supervisor']); ?>">
                                    <label class="required-field" for="supervisor">Project Supervisor</label>
                                    <div class="invalid-feedback">
                                        Please enter supervisor name.
                                    </div>
                                </div>
                            </div>

                            <!-- Year -->
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="year" name="year" 
                                        placeholder="Enter year" required min="2000" max="2100"
                                        value="<?php echo htmlspecialchars($formData['year'] ?? date('Y')); ?>">
                                    <label class="required-field" for="year">Project Year</label>
                                    <div class="invalid-feedback">
                                        Please enter a valid year.
                                    </div>
                                </div>
                            </div>

                            <!-- URLs -->
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="url" class="form-control" id="github_url" name="github_url"
                                        placeholder="Enter GitHub URL" pattern="https://.*"
                                        value="<?php echo htmlspecialchars($formData['github_url']); ?>">
                                    <label for="github_url">GitHub URL</label>
                                    <div class="invalid-feedback">
                                        Please enter a valid URL starting with https://
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="url" class="form-control" id="demo_url" name="demo_url"
                                        placeholder="Enter Demo URL" pattern="https://.*"
                                        value="<?php echo htmlspecialchars($formData['demo_url']); ?>">
                                    <label for="demo_url">Demo URL</label>
                                    <div class="invalid-feedback">
                                        Please enter a valid URL starting with https://
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Image -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="preview_image">Preview Image</label>
                                    <input type="file" class="form-control" id="preview_image" name="preview_image"
                                        accept="image/*">
                                    <div class="form-text">Upload a preview image for your project (Max size: 2MB)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details Tab -->
                    <div class="tab-pane fade <?php echo $activeTab === 'advanced' ? 'show active' : ''; ?>" 
                        id="advanced" role="tabpanel">
                        <div class="row g-4">
                            <!-- Full Description -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="full_description" name="full_description"
                                            style="height: 200px" placeholder="Enter full description"
                                            ><?php echo htmlspecialchars($formData['full_description']); ?></textarea>
                                    <label for="full_description">Full Project Description</label>
                                    <div class="form-text">Provide a detailed description of your project including objectives, methodology, and implementation.</div>
                                </div>
                            </div>

                            <!-- Report URL -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="url" class="form-control" id="report_url" name="report_url"
                                        placeholder="Enter Report URL" pattern="https://.*"
                                        value="<?php echo htmlspecialchars($formData['report_url'] ?? ''); ?>">
                                    <label for="report_url">Report URL</label>
                                    <div class="form-text">Link to your project report document</div>
                                </div>
                            </div>

                            <!-- Technologies Used -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="technologies_used">Technologies Used</label>
                                    <select class="form-select" id="technologies_used" name="technologies_used[]" 
                                            multiple data-placeholder="Select technologies used">
                                        <!-- Common technology options -->
                                        <option value="Python">Python</option>
                                        <option value="JavaScript">JavaScript</option>
                                        <option value="React">React</option>
                                        <option value="Node.js">Node.js</option>
                                        <option value="MongoDB">MongoDB</option>
                                        <option value="MySQL">MySQL</option>
                                        <option value="Arduino">Arduino</option>
                                        <option value="Raspberry Pi">Raspberry Pi</option>
                                        <option value="TensorFlow">TensorFlow</option>
                                        <option value="OpenCV">OpenCV</option>
                                    </select>
                                    <div class="form-text">Hold Ctrl/Cmd to select multiple technologies</div>
                                </div>
                            </div>

                            <!-- Architecture Details -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Architecture Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Architecture Diagram -->
                                        <div class="mb-3">
                                            <label for="architecture_diagram" class="form-label">Architecture Diagram</label>
                                            <input type="file" class="form-control" id="architecture_diagram" 
                                                name="architecture_diagram" accept="image/*">
                                        </div>
                                        
                                        <!-- Architecture Components -->
                                        <div id="architecture_components">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="architecture_components[0][name]" 
                                                        placeholder="Component Name">
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="architecture_components[0][description]" 
                                                        placeholder="Component Description">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add_component">
                                            <i class="bi bi-plus"></i> Add Component
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Key Features -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="key_features">Key Features</label>
                                    <div id="key_features_container">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="key_features[]" 
                                                placeholder="Enter a key feature">
                                            <button class="btn btn-outline-danger remove-feature" type="button">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="add_feature">
                                        <i class="bi bi-plus"></i> Add Feature
                                    </button>
                                </div>
                            </div>

                            <!-- Project Outcomes -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Project Outcomes</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="project_outcomes">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="project_outcomes[0][metric]" 
                                                        placeholder="Metric Name">
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="project_outcomes[0][value]" 
                                                        placeholder="Achievement/Result">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add_outcome">
                                            <i class="bi bi-plus"></i> Add Outcome
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Challenges Faced -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="challenges_faced">Challenges Faced</label>
                                    <div id="challenges_container">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="challenges_faced[]" 
                                                placeholder="Enter a challenge faced">
                                            <button class="btn btn-outline-danger remove-challenge" type="button">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="add_challenge">
                                        <i class="bi bi-plus"></i> Add Challenge
                                    </button>
                                </div>
                            </div>

                            <!-- Implementation Details -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Implementation Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Hardware Requirements -->
                                        <div class="mb-3">
                                            <label class="form-label">Hardware Requirements</label>
                                            <div id="hardware_requirements">
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" name="implementation_details[hardware][]" 
                                                        placeholder="Enter hardware requirement">
                                                    <button class="btn btn-outline-danger remove-hardware" type="button">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="add_hardware">
                                                <i class="bi bi-plus"></i> Add Hardware
                                            </button>
                                        </div>

                                        <!-- Software Requirements -->
                                        <div class="mb-3">
                                            <label class="form-label">Software Requirements</label>
                                            <div id="software_requirements">
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" name="implementation_details[software][]" 
                                                        placeholder="Enter software requirement">
                                                    <button class="btn btn-outline-danger remove-software" type="button">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="add_software">
                                                <i class="bi bi-plus"></i> Add Software
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Team Roles -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Team Roles</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="team_roles">
                                            <div class="team-role-entry mb-3">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="team_roles[0][name]" 
                                                            placeholder="Team Member Name">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="team_roles[0][role]" 
                                                            placeholder="Role Title">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="team_roles[0][responsibilities]" 
                                                                placeholder="Responsibilities">
                                                            <button class="btn btn-outline-danger remove-role" type="button">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add_role">
                                            <i class="bi bi-plus"></i> Add Team Role
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Project Timeline -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Project Timeline</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" class="form-control" name="project_timeline[start_date]">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">End Date</label>
                                                <input type="date" class="form-control" name="project_timeline[end_date]">
                                            </div>
                                        </div>
                                        <div id="timeline_milestones">
                                            <div class="row mb-3">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="project_timeline[milestones][0][title]" 
                                                        placeholder="Milestone Title">
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <input type="date" class="form-control" name="project_timeline[milestones][0][date]">
                                                        <button class="btn btn-outline-danger remove-milestone" type="button">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add_milestone">
                                            <i class="bi bi-plus"></i> Add Milestone
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Awards & Recognition -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="awards_recognition">Awards & Recognition</label>
                                    <div id="awards_container">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="awards_recognition[]" 
                                                placeholder="Enter award or recognition">
                                            <button class="btn btn-outline-danger remove-award" type="button">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="add_award">
                                        <i class="bi bi-plus"></i> Add Award
                                    </button>
                                </div>
                            </div>

                            <!-- Media Gallery -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Media Gallery</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Images -->
                                        <div class="mb-3">
                                            <label class="form-label">Project Images</label>
                                            <input type="file" class="form-control" name="media_gallery_images[]" 
                                                multiple accept="image/*">
                                            <div class="form-text">Upload multiple project images (Max 5 images, 2MB each)</div>
                                        </div>

                                        <!-- Videos -->
                                        <div class="mb-3">
                                            <label class="form-label">Project Videos</label>
                                            <div id="video_links">
                                                <div class="input-group mb-2">
                                                    <input type="url" class="form-control" name="media_gallery_videos[]" 
                                                        placeholder="Enter video URL (YouTube/Vimeo)">
                                                    <button class="btn btn-outline-danger remove-video" type="button">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="add_video">
                                                <i class="bi bi-plus"></i> Add Video Link
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
                        <button type="submit" name="save_draft" class="btn btn-outline-primary">
                            <i class="bi bi-save me-2"></i>Save as Draft
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="toggleTabs()">
                                <i class="bi bi-arrow-left-right me-2"></i>Switch Section
                            </button>
                            <button type="submit" name="submit_project" class="btn btn-primary">
                                <i class="bi bi-check2-circle me-2"></i>Submit Project
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/pages/projects/submit-project.js"></script>
</body>
</html>