<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require 'connect.php';
require 'vendor/autoload.php';
use Helpers\MailHelper;

session_start();

$userId = $_SESSION['two_factor_user_id'] ?? null;

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

// Generate new OTP
$otp = rand(100000, 999999);
$expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));

// Update user with new OTP
$stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires = ? WHERE id = ?");
$stmt->execute([$otp, $expires, $userId]);

// Send new OTP email
if (MailHelper::sendOtpEmail($user['email'], $user['name'], $otp)) {
    echo json_encode([
        "status" => "success",
        "message" => "New OTP sent to your email"
    ]);
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "Could not send OTP. Please try again."
    ]);
}
?>
