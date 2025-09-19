<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

$code = $data['code'];
$userId = $data['user_id'] ?? null;

// If no user_id provided, try to get from session (for resend functionality)
if (!$userId) {
    session_start();
    $userId = $_SESSION['two_factor_user_id'] ?? null;
}

if (!$userId) {
    echo json_encode(["status" => "error", "message" => "Session expired. Please login again."]);
    exit;
}

// Get user by ID
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

// Check OTP
if ($user['otp_code'] == $code && strtotime($user['otp_expires']) > time()) {
    // Clear OTP after successful verification
    $clearStmt = $conn->prepare("UPDATE users SET otp_code = NULL, otp_expires = NULL WHERE id = ?");
    $clearStmt->execute([$userId]);
    
    // Generate a simple token
    $token = bin2hex(random_bytes(32));
    
    // Store token in database
    $tokenStmt = $conn->prepare("UPDATE users SET auth_token = ? WHERE id = ?");
    $tokenStmt->execute([$token, $userId]);
    
    echo json_encode([
        "status" => "success",
        "token" => $token,
        "user" => [
            "id" => $user['id'],
            "name" => $user['name'],
            "email" => $user['email'],
            "role" => $user['role']
        ],
        "redirect" => "/dashboard"
    ]);
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid or expired code"
    ]);
}
?>
