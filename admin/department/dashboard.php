<?php
// admin/department/dashboard.php
session_start();
require_once '../../auth/session.php';
require_once '../../config/database.php';
checkLogin();
checkUserType('department');

// Get department stats
$conn = connectDB();
$department_id = $_SESSION['department_id'] ?? 1; // Replace with actual department ID

// In real application, replace these with actual database queries
$stats = [
    'faculty_count' => 24,
    'student_count' => 450,
    'document_count' => 856,
    'pending_count' => 15
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Dashboard - Repository Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Dashboard Card Styles */
        .dashboard-card {
            border: none;
            border-radius: 10px;
            padding: 20px;
            height: 100%;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-card .icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.2;
            font-size: 48px;
        }

        .dashboard-card h2 {
            font-size: 36px;
            font-weight: bold;
            margin: 10px 0;
        }

        .dashboard-card.purple { background: #6610f2; }
        .dashboard-card.green { background: #198754; }
        .dashboard-card.blue { background: #0dcaf0; }
        .dashboard-card.yellow { background: #ffc107; }

        /* Table Styles */
        .activity-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .activity-table th {
            font-weight: 500;
            color: #6c757d;
            border-bottom-width: 1px;
        }

        /* Progress bar custom styling */
        .progress {
            height: 8px;
            margin-top: 6px;
        }

        /* Custom badge colors */
        .badge.bg-pending { background-color: #ffc107; }
        .badge.bg-approved { background-color: #198754; }
        .badge.bg-rejected { background-color: #dc3545; }

        /* Chart container */
        .chart-container {
            height: 300px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include '../../includes/components/sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">Department Dashboard</h2>
                <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
            </div>
            <div>
                <button class="btn btn-outline-primary me-2">
                    <i class="bi bi-download me-2"></i>Download Report
                </button>
                <button class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Add New Faculty
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Faculty Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-card purple text-white">
                    <p class="mb-1">Total Faculty</p>
                    <h2><?php echo $stats['faculty_count']; ?></h2>
                    <i class="bi bi-person-workspace icon"></i>
                </div>
            </div>

            <!-- Students Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-card green text-white">
                    <p class="mb-1">Total Students</p>
                    <h2><?php echo $stats['student_count']; ?></h2>
                    <i class="bi bi-mortarboard icon"></i>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-card blue text-white">
                    <p class="mb-1">Total Documents</p>
                    <h2><?php echo $stats['document_count']; ?></h2>
                    <i class="bi bi-file-text icon"></i>
                </div>
            </div>

            <!-- Pending Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-card yellow">
                    <p class="mb-1">Pending Review</p>
                    <h2><?php echo $stats['pending_count']; ?></h2>
                    <i class="bi bi-clock-history icon"></i>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="row">
            <!-- Recent Submissions Table -->
            <div class="col-12 col-xl-8 mb-4">
                <div class="card activity-table">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Submissions</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                This Week
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Week</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Machine Learning Research Paper</td>
                                        <td>John Doe</td>
                                        <td>Research Paper</td>
                                        <td><span class="badge bg-pending">Pending</span></td>
                                        <td>2024-01-19</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Database Systems Thesis</td>
                                        <td>Jane Smith</td>
                                        <td>Thesis</td>
                                        <td><span class="badge bg-approved">Approved</span></td>
                                        <td>2024-01-18</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Statistics -->
            <div class="col-12 col-xl-4 mb-4">
                <div class="card activity-table h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Department Statistics</h5>
                    </div>
                    <div class="card-body">
                        <!-- Document Types -->
                        <div class="mb-4">
                            <h6 class="mb-3">Document Types</h6>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Research Papers</span>
                                    <span>65%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" style="width: 65%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Theses</span>
                                    <span>25%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: 25%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Dissertations</span>
                                    <span>10%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-info" style="width: 10%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div>
                            <h6 class="mb-3">Recent Activity</h6>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">New faculty member added</h6>
                                        <small>3h ago</small>
                                    </div>
                                    <p class="mb-1 text-muted">Dr. Sarah Johnson joined Computer Science</p>
                                </div>
                                <div class="list-group-item px-0">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Document approved</h6>
                                        <small>5h ago</small>
                                    </div>
                                    <p class="mb-1 text-muted">AI Research Paper by John Smith</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize any JavaScript features here
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            });
        });
    </script>
</body>
</html>