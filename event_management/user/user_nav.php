<?php
// user_nav.php
?>

<nav class="user-nav">
    <div class="logo">
        <a href="dashboard.php" style="color: white; text-decoration: none;">User Dashboard</a>
    </div>
    <div class="nav-links">
        <a href="../index.php" class="btn btn-primary">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="../logout.php" class="btn btn-logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</nav>

<style>
.user-nav {
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

.nav-links .btn-primary {
    background-color: #007bff;
    color: white;
    border: 2px solid #007bff;
}

.nav-links .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
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