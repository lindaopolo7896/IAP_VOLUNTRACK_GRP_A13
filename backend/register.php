<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require 'connect.php';
require 'vendor/autoload.php';
use Helpers\MailHelper;

header('Content-Type: application/json');

// Get  POST data
$raw = file_get_contents("php://input");
error_log("Raw POST data: " . $raw);
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

// Validate required fields
$required = ['name', 'email', 'password', 'role'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(["status" => "error", "message" => ucfirst($field) . " is required"]);
        exit;
    }
}

$name = $conn->real_escape_string($data['name']);
$email = $conn->real_escape_string($data['email']);
$password = password_hash($data['password'], PASSWORD_DEFAULT);
$role = $conn->real_escape_string($data['role']);

// Check if email already exists
$checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();
if ($checkStmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email already registered"]);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $password, $role);

if ($stmt->execute()) {
    // Send welcome email 
    $emailSent = MailHelper::sendWelcomeEmail($email, $name);

    echo json_encode([
        "status" => "success",
        "message" => $emailSent
            ? "User registered successfully. Welcome email sent."
            : "User registered successfully, but email could not be sent."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $stmt->error
    ]);
}


$stmt->close();
$conn->close();
?>
