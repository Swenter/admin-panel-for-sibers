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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    
    if (empty($login) || empty($first_name) || empty($last_name) || empty($gender) || empty($birth_date)) {
        echo "<script>alert('Fill all fields');</script>";
    } else {
        // Check if login exists for other users
        $check_sql = "SELECT id FROM users WHERE login = '$login' AND id != '$id'";
        $check_result = $conn->query($check_sql);
        
        if ($check_result->num_rows > 0) {
            echo "<script>alert('Login already exists');</script>";
        } else {
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET login='$login', password='$hashed_password', first_name='$first_name', last_name='$last_name', gender='$gender', birth_date='$birth_date' WHERE id='$id'";
            } else {
                $sql = "UPDATE users SET login='$login', first_name='$first_name', last_name='$last_name', gender='$gender', birth_date='$birth_date' WHERE id='$id'";
            }
            
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "User updated successfully";
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
    <title>Edit User</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark">
    <?php include 'header.php'; ?>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white">Edit User</h2>
            <a href="index.php" class="btn btn-secondary">Back to List</a>
        </div>
        
        <div class="card bg-dark border-secondary">
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="login" class="form-label text-white">Login *</label>
                            <input type="text" name="login" class="form-control bg-dark text-white border-secondary" id="login" value="<?php echo $user['login']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label text-white">Password <small class="text-secondary">(leave empty to keep current)</small></label>
                            <input type="password" name="password" class="form-control bg-dark text-white border-secondary" id="password">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label text-white">First Name *</label>
                            <input type="text" name="first_name" class="form-control bg-dark text-white border-secondary" id="first_name" value="<?php echo $user['first_name']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label text-white">Last Name *</label>
                            <input type="text" name="last_name" class="form-control bg-dark text-white border-secondary" id="last_name" value="<?php echo $user['last_name']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label text-white">Gender *</label>
                            <select name="gender" class="form-select bg-dark text-white border-secondary" id="gender" required>
                                <option value="male" <?php echo ($user['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo ($user['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo ($user['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="birth_date" class="form-label text-white">Birth Date *</label>
                            <input type="date" name="birth_date" class="form-control bg-dark text-white border-secondary" id="birth_date" value="<?php echo $user['birth_date']; ?>" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-warning">Update User</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.7.0.min.js"></script>
</body>
</html>