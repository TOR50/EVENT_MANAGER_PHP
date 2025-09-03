<?php
// patch_db.php
require_once 'event_management/includes/config.php';

echo "Patching Database...\n";

// Add deleted_at column
try {
    $conn->query("ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL");
    echo "Added deleted_at column successfully.\n";
} catch (Exception $e) {
    echo "deleted_at column might already exist or error: " . $e->getMessage() . "\n";
}

// Add last_login column
try {
    $conn->query("ALTER TABLE users ADD COLUMN last_login TIMESTAMP NULL DEFAULT NULL");
    echo "Added last_login column successfully.\n";
} catch (Exception $e) {
    echo "last_login column might already exist or error: " . $e->getMessage() . "\n";
}

echo "Patch complete.";
?>
