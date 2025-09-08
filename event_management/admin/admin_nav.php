<?php
// admin_nav.php
?>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<nav class="admin-nav">
    <div class="logo">
        <a href="dashboard.php" style="color: white; text-decoration: none;">Admin Panel</a>
    </div>
    <div class="nav-links">
        <a href="create_event.php" class="btn btn-create">
            <i class="fas fa-plus"></i> Create Event
        </a>
        <a href="view_users.php" class="btn btn-manage">
            <i class="fas fa-users"></i> Manage Users
        </a>
        <a href="../logout.php" class="btn btn-logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</nav>

<style>
.admin-nav {
    background: #333;
    color: white;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.logo {
    font-size: 1.5em;
    font-weight: bold;
}

.nav-links {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.nav-links .btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    font-size: 0.9em;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.nav-links .btn i {
    font-size: 1em;
}

.nav-links .btn-create {
    background-color: #00d4ff;
    color: #000000;
    border: 2px solid #00d4ff;
    font-weight: 600;
}

.nav-links .btn-create:hover {
    background-color: #00bde0;
    border-color: #00bde0;
    transform: translateY(-2px);
}

.nav-links .btn-manage {
    background-color: #ff9100;
    color: #000000;
    border: 2px solid #ff9100;
    font-weight: 600;
}

.nav-links .btn-manage:hover {
    background-color: #e68200;
    border-color: #e68200;
    transform: translateY(-2px);
}

.nav-links .btn-logout {
    background-color: #ff3366;
    color: #ffffff;
    border: 2px solid #ff3366;
    font-weight: 600;
}

.nav-links .btn-logout:hover {
    background-color: #ff1a53;
    border-color: #ff1a53;
    transform: translateY(-2px);
}
</style>