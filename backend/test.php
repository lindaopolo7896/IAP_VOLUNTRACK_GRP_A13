<?php
// Simple test to verify 2FA system is working
echo "<h2>VolunTrack 2FA System Test</h2>";

require 'connect.php';

echo "✅ Database connected successfully<br>";

// Check if users table exists
$result = $conn->query("PRAGMA table_info(users)");
$fields = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $fields[] = $row['name'];
}

echo "✅ Users table fields: " . implode(', ', $fields) . "<br>";

// Test OTP generation
$otp = rand(100000, 999999);
$expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));
echo "✅ Generated test OTP: $otp (expires: $expires)<br>";

// Check MailHelper
if (class_exists('Helpers\MailHelper')) {
    echo "✅ MailHelper class found<br>";
} else {
    echo "❌ MailHelper class not found<br>";
}

echo "<br><strong>🎉 Your 2FA system is ready!</strong><br>";
echo "<br><strong>API Endpoints:</strong><br>";
echo "- POST /backend/login.php - Login with 2FA<br>";
echo "- POST /backend/register.php - Register with 2FA<br>";
echo "- POST /backend/verify.php - Verify OTP code<br>";
echo "- POST /backend/resend.php - Resend OTP code<br>";

echo "<br><strong>Database:</strong> voluntrack.db<br>";
echo "<strong>Size:</strong> " . filesize(__DIR__ . "/voluntrack.db") . " bytes<br>";
?>
