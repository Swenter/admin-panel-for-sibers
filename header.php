<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="img/icon.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
            Admin Panel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_user.php">Add User</a>
                </li>
                <li class="nav-item">
                    <span class="nav-link text-secondary">Admin: <?php echo $_SESSION['admin']['username']; ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>