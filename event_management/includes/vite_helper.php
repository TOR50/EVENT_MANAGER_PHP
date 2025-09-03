<?php
// includes/vite_helper.php

function vite_assets() {
    $devServer = 'http://localhost:5173';
    $basePath = '/dist/'; // Production base path
    
    // Check if Vite Dev Server is running (naive check or manual toggle)
    // For this environment, we'll assume if we can curl local 5173 it's running, 
    // or just default to Dev for now since we are developing.
    // Better practice: check for a 'hot' file.
    
    $isDev = false; // Production mode
    
    if ($isDev) {
        echo '<script type="module" src="' . $devServer . '/@vite/client"></script>';
        echo '<script type="module" src="' . $devServer . '/event_management/assets/js/main.js"></script>';
    } else {
        // In production, read manifest.json
        // __DIR__ is .../event_management/includes
        // We want .../event_management/dist/.vite/manifest.json
        $manifestPath = dirname(__DIR__) . '/dist/.vite/manifest.json';
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            // Main JS
            if (isset($manifest['event_management/assets/js/main.js'])) {
                $file = $manifest['event_management/assets/js/main.js']['file'];
                echo '<script type="module" src="' . $basePath . $file . '"></script>';
                // CSS (if any associated)
                if (isset($manifest['event_management/assets/js/main.js']['css'])) {
                    foreach ($manifest['event_management/assets/js/main.js']['css'] as $css) {
                        echo '<link rel="stylesheet" href="' . $basePath . $css . '">';
                    }
                }
            }
        }
    }
}
?>
