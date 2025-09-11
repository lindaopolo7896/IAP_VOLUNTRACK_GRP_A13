
<?php
require __DIR__ . '/vendor/autoload.php';
require 'helpers/MailHelper.php';

use Helpers\MailHelper;

// Test recipient
$testEmail = "lindaopolo261@gmail.com";
$testName  = "Linda Opolo";

if (MailHelper::sendWelcomeEmail($testEmail, $testName)) {
    echo "Email sent successfully to $testEmail";
} else {
    echo "Email failed. Check Apache error log for details.";
}
