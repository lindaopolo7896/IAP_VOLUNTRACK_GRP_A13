<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require 'connect.php';
require 'vendor/autoload.php';
use Helpers\MailHelper;

header('Content-Type: application/json');

// Get POST data
$raw = file_get_contents("php://input");
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

$name = $data['name'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);
$role = $data['role'];

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(["status" => "error", "message" => "Email already registered"]);
    exit;
}

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $password, $role]);

if ($stmt->rowCount() > 0) {
    $userId = $conn->lastInsertId();
    
    // Generate OTP for email verification
    $otp = rand(100000, 999999);
    $expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));

    // Update user with OTP
    $otpStmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires = ? WHERE id = ?");
    $otpStmt->execute([$otp, $expires, $userId]);

    // Send OTP email for verification
    $emailSent = MailHelper::sendOtpEmail($email, $name, $otp);

    // Store user ID in session for resend functionality
    session_start();
    $_SESSION['two_factor_user_id'] = $userId;

    echo json_encode([
        "status" => "success",
        "requires_two_factor" => true,
        "user_id" => $userId,
        "message" => $emailSent
            ? "Registration successful. Please verify your email with the code sent."
            : "Registration successful, but verification email could not be sent."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Database error"
    ]);
}
?>
