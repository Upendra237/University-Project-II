<?php
// admin/admin/dashboard.php
session_start();
require_once '../../auth/session.php';
require_once '../../config/database.php';
checkLogin();
checkUserType('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Repository Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        
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
        .dashboard-card.yellow { background: #ffc107; }
        .dashboard-card.blue { background: #0dcaf0; }

        .activity-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .main-content {
            padding-top: 80px; /* Space for admin menu */
        }
    </style>
</head>
<body>
    <?php 
    include '../../includes/components/sidebar.php';
    include '../../includes/components/admin-menu.php'; 
    ?>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row g-4 mb-4">
                <!-- Total Users Card -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="dashboard-card purple text-white">
                        <p class="mb-1">Total Users</p>
                        <h2>250</h2>
                        <i class="bi bi-people icon"></i>
                    </div>
                </div>

                <!-- Documents Card -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="dashboard-card green text-white">
                        <p class="mb-1">Documents</p>
                        <h2>1,234</h2>
                        <i class="bi bi-file-text icon"></i>
                    </div>
                </div>

                <!-- Pending Card -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="dashboard-card yellow">
                        <p class="mb-1">Pending</p>
                        <h2>15</h2>
                        <i class="bi bi-clock-history icon"></i>
                    </div>
                </div>

                <!-- Downloads Card -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="dashboard-card blue text-white">
                        <p class="mb-1">Downloads</p>
                        <h2>3,456</h2>
                        <i class="bi bi-download icon"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card activity-table mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Document</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>Uploaded</td>
                                    <td>Research Paper.pdf</td>
                                    <td>2024-01-19 14:30:00</td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>Downloaded</td>
                                    <td>Thesis.pdf</td>
                                    <td>2024-01-19 13:15:00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>