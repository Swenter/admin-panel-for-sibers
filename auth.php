<?php
require_once('config.php');

$username = $_POST['username'];
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    echo "<script>alert('Fill all fields'); window.location.href='login.php';</script>";
} else {
    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = [
                "id" => $admin['id'],
                "username" => $admin['username']
            ];
            header('Location: index.php');
            exit;
        } else {
            echo "<script>alert('Wrong password'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location.href='login.php';</script>";
    }
}
?>