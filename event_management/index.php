<?php
require_once 'includes/config.php';
include 'includes/header.php';

// Fetch featured events
$featured_query = "SELECT e.*, 
    COUNT(p.id) as participant_count 
    FROM events e 
    LEFT JOIN participants p ON e.id = p.event_id 
    WHERE e.event_date >= CURRENT_DATE 
    GROUP BY e.id 
    ORDER BY e.created_at DESC 
    LIMIT 3";
$featured_events = mysqli_query($conn, $featured_query);
?>

<!-- Hero Section -->
<section class="relative overflow-hidden py-24 sm:py-32">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 bg-background">
        <div class="absolute inset-0 bg-[url('/event_management/assets/images/hero-bg.png')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
        <div class="absolute top-0 -left-4 w-72 h-72 bg-primary/30 rounded-full mix-blend-screen filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 -right-4 w-96 h-96 bg-secondary/20 rounded-full mix-blend-screen filter blur-[120px] animation-delay-2000 animate-pulse"></div>
    </div>

    <div class="relative container mx-auto px-4 text-center z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-6 rounded-2xl bg-white/5 border border-white/5 hover:border-primary/30 transition-colors group">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-bolt text-primary text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-white">Instant Creation</h3>
                <p class="text-sm text-gray-400 mt-2">Create events in seconds with our optimized workflow.</p>
            </div>
            <div class="p-6 rounded-2xl bg-white/5 border border-white/5 hover:border-secondary/30 transition-colors group">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-secondary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-globe text-secondary text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-white">Global Reach</h3>
                <p class="text-sm text-gray-400 mt-2">Connect with participants from around the world effortlessly.</p>
            </div>
            <div class="p-6 rounded-2xl bg-white/5 border border-white/5 hover:border-purple-500/30 transition-colors group">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-purple-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-pie text-purple-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-white">Real-time Analytics</h3>
                <p class="text-sm text-gray-400 mt-2">Track participation and engagement live.</p>
            </div>
        </div>
    </div>
</div>

<!-- Featured Events -->
<section id="featured" class="py-24 relative">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-bold text-white font-outfit">Featured Events</h2>
                <p class="text-gray-400 mt-2">Don't miss out on these trending gatherings.</p>
            </div>
            <a href="user/dashboard.php" class="text-primary hover:text-secondary text-sm font-semibold flex items-center gap-1 group">
                View All <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while ($event = mysqli_fetch_assoc($featured_events)): ?>
                <!-- Event Card -->
                <article class="group relative flex flex-col justify-between h-full bg-surface/40 backdrop-blur-md border border-white/5 rounded-2xl overflow-hidden hover:border-primary/50 transition-all duration-300 hover:shadow-[0_0_30px_rgba(0,0,0,0.5)] hover:-translate-y-1">
                    
                    <!-- Glow Effect -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-primary to-secondary opacity-0 group-hover:opacity-20 blur-xl transition-opacity duration-500"></div>

                    <div class="relative p-6 flex-grow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-medium text-primary ring-1 ring-inset ring-primary/20">
                                <?php echo date('M d', strtotime($event['event_date'])); ?>
                            </span>
                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-users"></i> <?php echo $event['participant_count']; ?>
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-primary transition-colors line-clamp-2" style="view-transition-name: event-title-<?php echo $event['id']; ?>">
                            <?php echo htmlspecialchars($event['title']); ?>
                        </h3>
                        
                        <p class="text-gray-400 text-sm line-clamp-3 mb-4">
                            <?php echo htmlspecialchars($event['description']); ?>
                        </p>
                        
                        <div class="flex items-center text-sm text-gray-500 mb-6">
                            <i class="fas fa-map-marker-alt mr-2 text-secondary"></i>
                            <?php echo htmlspecialchars($event['location']); ?>
                        </div>
                    </div>

                    <div class="relative p-6 pt-0 mt-auto">
                        <a href="event_details.php?id=<?php echo $event['id']; ?>" class="block w-full text-center py-3 rounded-xl bg-white/5 hover:bg-primary hover:text-background text-white font-medium transition-all duration-300 border border-white/10 hover:border-transparent">
                            View Details
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>