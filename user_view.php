<?php
require_once('config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = '$id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: index.php');
    exit;
}

// Calculate age
$birthDate = new DateTime($user['birth_date']);
$today = new DateTime();
$age = $birthDate->diff($today)->y;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User - <?php echo $user['login']; ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark">
    <?php include 'header.php'; ?>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white">User Details</h2>
            <div>
                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">Edit</a>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </div>
        </div>
        
        <div class="card bg-dark border-secondary">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-secondary">ID:</label>
                        <p class="text-white fs-5"><?php echo $user['id']; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-secondary">Login:</label>
                        <p class="text-white fs-5"><?php echo $user['login']; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-secondary">First Name:</label>
                        <p class="text-white fs-5"><?php echo $user['first_name']; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-secondary">Last Name:</label>
                        <p class="text-white fs-5"><?php echo $user['last_name']; ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-secondary">Gender:</label>
                        <p class="text-white fs-5"><?php echo ucfirst($user['gender']); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-secondary">Birth Date:</label>
                        <p class="text-white fs-5"><?php echo date('F d, Y', strtotime($user['birth_date'])); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-secondary">Age:</label>
                        <p class="text-white fs-5"><?php echo $age; ?> years old</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-secondary">Created At:</label>
                        <p class="text-white fs-5"><?php echo date('F d, Y', strtotime($user['created_at'])); ?></p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">Edit User</a>
                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this user?')">Delete User</a>
                </div>
            </div>
        </div>
    </div> 
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.7.0.min.js"></script>
</body>
</html>