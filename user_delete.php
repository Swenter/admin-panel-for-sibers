<?php
require_once('config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];
$sql = "DELETE FROM users WHERE id = '$id'";

if ($conn->query($sql) === TRUE) {
    $_SESSION['message'] = "User deleted successfully";
    $_SESSION['msg_type'] = "success";
} else {
    $_SESSION['message'] = "Error deleting user";
    $_SESSION['msg_type'] = "danger";
}

header('Location: index.php');
?>