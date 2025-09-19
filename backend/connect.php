<?php
// SQLite database connection (no server required)
require __DIR__ . '/vendor/autoload.php';

$db_file = __DIR__ . '/voluntrack.db';

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create users table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL CHECK (role IN ('volunteer', 'organization')),
        otp_code TEXT DEFAULT NULL,
        otp_expires TEXT DEFAULT NULL,
        auth_token TEXT DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    
} catch (PDOException $e) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $e->getMessage()]));
}
?>
