<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require 'connect.php';
require 'vendor/autoload.php';
use Helpers\MailHelper;

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// Check user
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Generate OTP
    $otp = rand(100000, 999999);
    $expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));

    // Update user with OTP
    $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires = ? WHERE id = ?");
    $stmt->execute([$otp, $expires, $user['id']]);

    // Send OTP email
    if (MailHelper::sendOtpEmail($user['email'], $user['name'], $otp)) {
        // Store user ID in session for resend functionality
        session_start();
        $_SESSION['two_factor_user_id'] = $user['id'];
        
        echo json_encode([
            "status" => "success",
            "requires_two_factor" => true,
            "user_id" => $user['id'],
            "message" => "OTP sent to your email"
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Could not send OTP. Please try again."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid email or password"
    ]);
}
?>
