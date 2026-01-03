<?php
// Database migration script to add first_name and last_name columns to users table

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'clothing_store';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if first_name column exists
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `users` LIKE 'first_name'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        // Add first_name column
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `first_name` VARCHAR(100) NULL DEFAULT NULL AFTER `password`");
        echo "Added first_name column to users table\n";
    } else {
        echo "first_name column already exists\n";
    }
    
    // Check if last_name column exists
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `users` LIKE 'last_name'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        // Add last_name column
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `last_name` VARCHAR(100) NULL DEFAULT NULL AFTER `first_name`");
        echo "Added last_name column to users table\n";
    } else {
        echo "last_name column already exists\n";
    }
    
    echo "Migration completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>