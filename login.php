<?php 
require_once('config.php');

if (isset($_SESSION['admin'])) {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - User Management</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <img src="img/icon.png" alt="Admin" width="80" class="mb-3">
                    <h2 class="text-white">User Management System</h2>
                    <p class="text-secondary">Admin Panel</p>
                </div>
                
                <div class="card bg-dark border-secondary">
                    <div class="card-body p-4">
                        <form action="auth.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label text-white">Username</label>
                                <input type="text" name="username" class="form-control bg-dark text-white border-secondary" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label text-white">Password</label>
                                <input type="password" name="password" class="form-control bg-dark text-white border-secondary" id="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        
                        <div class="alert alert-info mt-3 mb-0">
                            <small>Default: <strong>admin / admin123</strong></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.7.0.min.js"></script>
</body>
</html>