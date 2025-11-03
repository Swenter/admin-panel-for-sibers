<?php
/**
 * User form page - Add/Edit user
 * 
 * Handles creating new users and editing existing ones
 */

require_once 'config.php';
requireAuth();

$isEdit = false;
$userId = 0;
$errors = [];
$formData = [
    'login' => '',
    'password' => '',
    'first_name' => '',
    'last_name' => '',
    'gender' => '',
    'birth_date' => ''
];

// Check if editing existing user
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    if ($userId > 0) {
        $isEdit = true;
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if ($user) {
                $formData = [
                    'login' => $user['login'],
                    'password' => '',
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'gender' => $user['gender'],
                    'birth_date' => $user['birth_date']
                ];
            } else {
                setFlashMessage('User not found', 'error');
                redirect('index.php');
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $formData['login'] = trim($_POST['login'] ?? '');
    $formData['password'] = $_POST['password'] ?? '';
    $formData['first_name'] = trim($_POST['first_name'] ?? '');
    $formData['last_name'] = trim($_POST['last_name'] ?? '');
    $formData['gender'] = $_POST['gender'] ?? '';
    $formData['birth_date'] = $_POST['birth_date'] ?? '';
    
    // Validation
    if (empty($formData['login'])) {
        $errors[] = 'Login is required';
    } elseif (strlen($formData['login']) < 3) {
        $errors[] = 'Login must be at least 3 characters';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $formData['login'])) {
        $errors[] = 'Login can only contain letters, numbers and underscores';
    }
    
    if (!$isEdit && empty($formData['password'])) {
        $errors[] = 'Password is required';
    } elseif (!empty($formData['password']) && strlen($formData['password']) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    if (empty($formData['first_name'])) {
        $errors[] = 'First name is required';
    }
    
    if (empty($formData['last_name'])) {
        $errors[] = 'Last name is required';
    }
    
    if (empty($formData['gender'])) {
        $errors[] = 'Gender is required';
    } elseif (!in_array($formData['gender'], ['male', 'female', 'other'])) {
        $errors[] = 'Invalid gender value';
    }
    
    if (empty($formData['birth_date'])) {
        $errors[] = 'Birth date is required';
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $formData['birth_date']);
        if (!$date) {
            $errors[] = 'Invalid birth date format';
        } else {
            $today = new DateTime();
            if ($date > $today) {
                $errors[] = 'Birth date cannot be in the future';
            }
            $age = $date->diff($today)->y;
            if ($age > 150) {
                $errors[] = 'Birth date seems incorrect';
            }
        }
    }
    
    // Check login uniqueness
    if (empty($errors)) {
        try {
            $pdo = getDbConnection();
            if ($isEdit) {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ? AND id != ?");
                $stmt->execute([$formData['login'], $userId]);
            } else {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
                $stmt->execute([$formData['login']]);
            }
            
            if ($stmt->fetch()) {
                $errors[] = 'This login is already taken';
            }
        } catch (PDOException $e) {
            $errors[] = 'Database error occurred';
        }
    }
    
    // Save user if no errors
    if (empty($errors)) {
        try {
            $pdo = getDbConnection();
            
            if ($isEdit) {
                // Update existing user
                if (!empty($formData['password'])) {
                    // Update with new password
                    $hashedPassword = password_hash($formData['password'], PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET login = ?, password = ?, first_name = ?, last_name = ?, gender = ?, birth_date = ? WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $formData['login'],
                        $hashedPassword,
                        $formData['first_name'],
                        $formData['last_name'],
                        $formData['gender'],
                        $formData['birth_date'],
                        $userId
                    ]);
                } else {
                    // Update without changing password
                    $sql = "UPDATE users SET login = ?, first_name = ?, last_name = ?, gender = ?, birth_date = ? WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $formData['login'],
                        $formData['first_name'],
                        $formData['last_name'],
                        $formData['gender'],
                        $formData['birth_date'],
                        $userId
                    ]);
                }
                setFlashMessage('User updated successfully', 'success');
            } else {
                // Create new user
                $hashedPassword = password_hash($formData['password'], PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (login, password, first_name, last_name, gender, birth_date) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $formData['login'],
                    $hashedPassword,
                    $formData['first_name'],
                    $formData['last_name'],
                    $formData['gender'],
                    $formData['birth_date']
                ]);
                setFlashMessage('User created successfully', 'success');
            }
            
            redirect('index.php');
            
        } catch (PDOException $e) {
            $errors[] = 'Database error: Unable to save user';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit User' : 'Add New User'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1><?php echo $isEdit ? 'Edit User' : 'Add New User'; ?></h1>
            <a href="index.php" class="btn btn-secondary">Back to List</a>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Please correct the following errors:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo escape($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="form-card">
            <form method="POST" class="user-form">
                <div class="form-section">
                    <h2>Account Information</h2>
                    
                    <div class="form-group">
                        <label for="login">Login <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="login" 
                            name="login" 
                            value="<?php echo escape($formData['login']); ?>"
                            required
                            pattern="[a-zA-Z0-9_]+"
                            minlength="3"
                            maxlength="50"
                        >
                        <small>Only letters, numbers and underscores. Minimum 3 characters.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">
                            Password 
                            <?php if ($isEdit): ?>
                                <span class="optional">(leave empty to keep current)</span>
                            <?php else: ?>
                                <span class="required">*</span>
                            <?php endif; ?>
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            <?php echo $isEdit ? '' : 'required'; ?>
                            minlength="6"
                        >
                        <small>Minimum 6 characters.</small>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Personal Information</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name <span class="required">*</span></label>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                value="<?php echo escape($formData['first_name']); ?>"
                                required
                                maxlength="100"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="last_name">Last Name <span class="required">*</span></label>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                value="<?php echo escape($formData['last_name']); ?>"
                                required
                                maxlength="100"
                            >
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="gender">Gender <span class="required">*</span></label>
                            <select id="gender" name="gender" required>
                                <option value="">Select gender...</option>
                                <option value="male" <?php echo $formData['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo $formData['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo $formData['gender'] === 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="birth_date">Birth Date <span class="required">*</span></label>
                            <input 
                                type="date" 
                                id="birth_date" 
                                name="birth_date" 
                                value="<?php echo escape($formData['birth_date']); ?>"
                                required
                                max="<?php echo date('Y-m-d'); ?>"
                            >
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $isEdit ? 'Update User' : 'Create User'; ?>
                    </button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>