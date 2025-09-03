<?php
// includes/header.php
require_once __DIR__ . '/vite_helper.php';

// Determine if we are in admin or user folder to adjust relative paths if needed
// But since we use BASE_URL for links, it should be fine.
// Ensure BASE_URL is defined in config.php
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <!-- View Transitions API support -->
    <meta name="view-transition" content="same-origin" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php vite_assets(); ?>
</head>
<body class="bg-background text-text-main font-sans antialiased selection:bg-primary selection:text-background min-h-screen flex flex-col">

<!-- Navigation -->
<nav class="sticky top-0 z-50 w-full border-b border-white/10 bg-background/80 backdrop-blur-md transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo -->
            <div class="flex-shrink-0 view-transition-[logo]">
                <a href="<?php echo BASE_URL; ?>index.php" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-background font-bold text-lg group-hover:scale-110 transition-transform">E</div>
                    <span class="font-outfit font-bold text-xl tracking-tight text-white group-hover:text-primary transition-colors">EventMS</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    <a href="<?php echo BASE_URL; ?>index.php" class="text-gray-300 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Home</a>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                            <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="text-gray-300 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Admin Dashboard</a>
                            <a href="<?php echo BASE_URL; ?>admin/create_event.php" class="text-gray-300 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Create Event</a>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>user/dashboard.php" class="text-gray-300 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">My Dashboard</a>
                            <a href="<?php echo BASE_URL; ?>user/profile.php" class="text-gray-300 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Profile</a>
                        <?php endif; ?>
                        
                        <a href="<?php echo BASE_URL; ?>logout.php" class="bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500 hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition-all ml-4">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>login.php" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Login</a>
                        <a href="<?php echo BASE_URL; ?>register.php" class="bg-primary text-background hover:bg-secondary hover:shadow-[0_0_15px_rgba(45,212,191,0.5)] px-4 py-2 rounded-lg text-sm font-bold transition-all transform hover:-translate-y-0.5">
                            Get Started
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex md:hidden">
                <button type="button" class="inline-flex items-center justify-center rounded-md bg-white/5 p-2 text-gray-400 hover:bg-white/10 hover:text-white focus:outline-none" aria-controls="mobile-menu" aria-expanded="false" id="mobile-menu-btn">
                    <span class="sr-only">Open main menu</span>
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden hidden bg-background/95 backdrop-blur-xl border-b border-white/10" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
             <a href="<?php echo BASE_URL; ?>index.php" class="text-gray-300 hover:bg-white/5 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Home</a>
             
             <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="text-gray-300 hover:bg-white/5 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>admin/create_event.php" class="text-gray-300 hover:bg-white/5 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Create Event</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>user/dashboard.php" class="text-gray-300 hover:bg-white/5 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Dashboard</a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>logout.php" class="text-red-400 hover:bg-red-500/10 block rounded-md px-3 py-2 text-base font-medium">Logout</a>
             <?php else: ?>
                <a href="<?php echo BASE_URL; ?>login.php" class="text-gray-300 hover:bg-white/5 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Login</a>
                <a href="<?php echo BASE_URL; ?>register.php" class="text-primary hover:bg-primary/10 block rounded-md px-3 py-2 text-base font-medium">Register</a>
             <?php endif; ?>
        </div>
    </div>
</nav>

<main class="flex-grow relative z-10">
<!-- Mobile Menu Script included inline or via main.js -->
<script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
