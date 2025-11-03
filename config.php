<?php
// Database connection

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_admin";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

// Start session
session_start();
?>