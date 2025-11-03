<?php
/**
 * Admin setup script
 * 
 * Creates or updates admin user with correct password hash
 * Run this file once, then delete it for security
 */

// Database configuration - UPDATE THESE!
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_admin');
define('DB_USER', 'root');
define('DB_PASS', ''); // Your database password

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Admin Setup</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
        h1 { color: #333; }
        .credentials { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; }
        strong { color: #000; }
    </style>
</head>
<body>
    <h1>Admin Setup Script</h1>";

try {
    // Connect to database
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "<div class='success'>✓ Database connection successful!</div>";
    
    // Create admin credentials
    $username = 'admin';
    $password = 'admin123';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    echo "<div class='info'>Generating password hash...</div>";
    echo "<div class='info'>Hash generated: <code>" . substr($hashedPassword, 0, 30) . "...</code></div>";
    
    // Check if admins table exists
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'admins'");
    if ($tableCheck->rowCount() == 0) {
        echo "<div class='error'>Error: 'admins' table does not exist! Please import database.sql first.</div>";
        exit;
    }
    
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $existingAdmin = $stmt->fetch();
    
    if ($existingAdmin) {
        // Update existing admin
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
        $stmt->execute([$hashedPassword, $username]);
        echo "<div class='success'>✓ Admin password updated successfully!</div>";
    } else {
        // Insert new admin
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);
        echo "<div class='success'>✓ Admin user created successfully!</div>";
    }
    
    // Display credentials
    echo "<div class='credentials'>
        <h3>Login Credentials:</h3>
        <p><strong>Username:</strong> admin</p>
        <p><strong>Password:</strong> admin123</p>
    </div>";
    
    // Verify the password works
    $stmt = $pdo->prepare("SELECT password FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if (password_verify($password, $admin['password'])) {
        echo "<div class='success'>✓ Password verification successful! You can now login.</div>";
    } else {
        echo "<div class='error'>✗ Password verification failed! Something went wrong.</div>";
    }
    
    echo "<div class='info'><strong>Next steps:</strong><br>
        1. Go to <a href='login.php'>login.php</a> and try logging in<br>
        2. After successful login, <strong>DELETE this setup_admin.php file</strong> for security!
    </div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>Database Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<div class='info'>Please check your database configuration in this file.</div>";
}

echo "</body></html>";
?>