# admin-panel-for-sibers

Simple admin panel for managing users with dark theme.

## Requirements

- PHP 7.x or 8.x
- MySQL 5.x or newer
- Web server (Apache, nginx)

## Installation

### 1. Import Database

**Option A: phpMyAdmin**
1. Open phpMyAdmin
2. Create new database: `db_admin`
3. Select the database
4. Click "Import" tab
5. Choose `db_admin.sql` file
6. Click "Go"

**Option B: Command line**
```bash
mysql -u root -p < database.sql
```

### 2. Configure Database

Edit `config.php`:
```php
$servername = "localhost";
$username = "root";
$password = "";  // Your MySQL password
$dbname = "db_admin";
```

### 3. Upload Files

Copy all files to your web server folder (htdocs, www, or public_html)

### 4. Open in Browser

### 5. Login

- Username: `admin`
- Password: `admin123`

**Important:** The admin account is already created in database.sql with the correct password hash!

## Files

```
database.sql     - Database dump (ready for phpMyAdmin import)
config.php       - Database connection settings
login.php        - Admin login page
auth.php         - Authentication handler
logout.php       - Logout handler
index.php        - Users list (pagination & sorting)
view_user.php    - View user details
add_user.php     - Add new user form
edit_user.php    - Edit user form
delete_user.php  - Delete user handler
header.php       - Navigation menu
style.css        - Dark theme styles
README.md        - Installation instructions
```

## Features

- View all users (with pagination - 10 per page)
- Sort by any column (click on table header)
- View user details (with age calculation)
- Add new user (with validation)
- Edit user (can change password or keep current)
- Delete user (with confirmation)
- Admin login/logout
- Dark theme design
- Responsive layout (Bootstrap 5)

## Database Tables

**users**
- id (INT) - Primary key
- login (VARCHAR) - Unique username
- password (VARCHAR) - Hashed password
- first_name (VARCHAR)
- last_name (VARCHAR)
- gender (ENUM: male, female, other)
- birth_date (DATE)
- created_at (TIMESTAMP)

**admins**
- id (INT) - Primary key
- username (VARCHAR)
- password (VARCHAR) - Hashed password

## Security

- Password hashing with `password_hash()`
- Session-based authentication
- Login check on every page
- SQL injection protection (mysqli)
- All passwords are hashed in database

## Technologies

- PHP 7/8
- MySQL
- Bootstrap 5 (dark theme)
- Pure PHP (no frameworks)
