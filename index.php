<?php
require_once('config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$allowed_columns = ['id', 'login', 'first_name', 'last_name', 'gender', 'birth_date'];
if (!in_array($sort, $allowed_columns)) {
    $sort = 'id';
}

// Get total users
$count_sql = "SELECT COUNT(*) as total FROM users";
$count_result = $conn->query($count_sql);
$total_users = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_users / $limit);

// Get users
$sql = "SELECT * FROM users ORDER BY $sort $order LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List - Admin Panel</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark">
    <?php include 'header.php'; ?>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white">Registered Users</h2>
            <a href="add_user.php" class="btn btn-success">Add New User</a>
        </div>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show">
                <?php 
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    unset($_SESSION['msg_type']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="card bg-dark border-secondary">
            <div class="card-body">
                <p class="text-white">Total users: <strong><?php echo $total_users; ?></strong></p>
                
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th><a href="?sort=id&order=<?php echo ($sort == 'id' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-decoration-none text-white">ID</a></th>
                                <th><a href="?sort=login&order=<?php echo ($sort == 'login' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-decoration-none text-white">Login</a></th>
                                <th><a href="?sort=first_name&order=<?php echo ($sort == 'first_name' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-decoration-none text-white">First Name</a></th>
                                <th><a href="?sort=last_name&order=<?php echo ($sort == 'last_name' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-decoration-none text-white">Last Name</a></th>
                                <th><a href="?sort=gender&order=<?php echo ($sort == 'gender' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-decoration-none text-white">Gender</a></th>
                                <th><a href="?sort=birth_date&order=<?php echo ($sort == 'birth_date' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" class="text-decoration-none text-white">Birth Date</a></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['login']; ?></td>
                                <td><?php echo $user['first_name']; ?></td>
                                <td><?php echo $user['last_name']; ?></td>
                                <td><?php echo ucfirst($user['gender']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($user['birth_date'])); ?></td>
                                <td>
                                    <a href="user_view.php?id=<?php echo $user['id']; ?>" class="btn btn-info btn-sm">View</a>
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="user_delete.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($total_pages > 1): ?>
                <nav class="mt-3">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link bg-dark text-white border-secondary" href="?page=<?php echo $page-1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>">Previous</a></li>
                        <?php endif; ?>
                        
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link bg-dark text-white border-secondary" href="?page=<?php echo $i; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item"><a class="page-link bg-dark text-white border-secondary" href="?page=<?php echo $page+1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.7.0.min.js"></script>
</body>
</html>