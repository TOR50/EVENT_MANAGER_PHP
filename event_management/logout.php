<?php
// logout.php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Perform logout
Auth::logout();

// Redirect to the login page
redirect('login.php');
?>