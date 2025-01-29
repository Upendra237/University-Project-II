<?php
// test/list_users.php
require_once '../config/database.php';

$conn = connectDB();
$query = "SELECT id, username, email, full_name, user_type, status, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($query);

$users = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/datatables@1.10.18/media/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">User List</h4>
                <a href="create-user.php" class="btn btn-primary">Create New Test User</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="usersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Full Name</th>
                                <th>User Type</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo match($user['user_type']) {
                                                'admin' => 'danger',
                                                'department' => 'warning',
                                                'faculty' => 'info',
                                                'student' => 'success',
                                                default => 'secondary'
                                            };
                                        ?>">
                                            <?php echo ucfirst(htmlspecialchars($user['user_type'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $user['status'] === 'active' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst(htmlspecialchars($user['status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('Y-m-d H:i:s', strtotime($user['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                order: [[0, 'desc']]
            });
        });
    </script>
</body>
</html>