<?php
require_once('config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    
    if (empty($login) || empty($password) || empty($first_name) || empty($last_name) || empty($gender) || empty($birth_date)) {
        echo "<script>alert('Fill all fields');</script>";
    } else {
        // Check if login exists
        $check_sql = "SELECT id FROM users WHERE login = '$login'";
        $check_result = $conn->query($check_sql);
        
        if ($check_result->num_rows > 0) {
            echo "<script>alert('Login already exists');</script>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (login, password, first_name, last_name, gender, birth_date) VALUES ('$login', '$hashed_password', '$first_name', '$last_name', '$gender', '$birth_date')";
            
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "User created successfully";
                $_SESSION['msg_type'] = "success";
                header('Location: index.php');
                exit;
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark">
    <?php include 'header.php'; ?>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white">Add New User</h2>
            <a href="index.php" class="btn btn-secondary">Back to List</a>
        </div>
        
        <div class="card bg-dark border-secondary">
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="login" class="form-label text-white">Login *</label>
                            <input type="text" name="login" class="form-control bg-dark text-white border-secondary" id="login" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label text-white">Password *</label>
                            <input type="password" name="password" class="form-control bg-dark text-white border-secondary" id="password" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label text-white">First Name *</label>
                            <input type="text" name="first_name" class="form-control bg-dark text-white border-secondary" id="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label text-white">Last Name *</label>
                            <input type="text" name="last_name" class="form-control bg-dark text-white border-secondary" id="last_name" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label text-white">Gender *</label>
                            <select name="gender" class="form-select bg-dark text-white border-secondary" id="gender" required>
                                <option value="">Select gender...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="birth_date" class="form-label text-white">Birth Date *</label>
                            <input type="date" name="birth_date" class="form-control bg-dark text-white border-secondary" id="birth_date" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Create User</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div> 
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.7.0.min.js"></script>
</body>
</html>