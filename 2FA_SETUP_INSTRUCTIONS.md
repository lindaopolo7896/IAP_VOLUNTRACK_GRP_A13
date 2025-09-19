# Two-Factor Authentication Setup Instructions

This document provides instructions for setting up and using the two-factor authentication (2FA) system that has been implemented in your application.

## Features Implemented

✅ **Backend Components:**
- Database migration to add 2FA fields to users table
- User model with 2FA methods (generate, verify, enable, disable)
- TwoFactorController for handling 2FA operations
- Email notification system for sending 2FA codes
- Middleware to enforce 2FA verification
- Authentication routes with 2FA support

✅ **Frontend Components:**
- TwoFactorVerification component for code input
- TwoFactorSettings component for enabling/disabling 2FA
- TwoFactorPage for login flow verification
- Updated Login component with 2FA flow
- Dashboard with security settings
- Responsive CSS styling

## Setup Instructions

### 1. Database Setup

Run the migration to add 2FA fields to your users table:

```bash
cd Rams
php artisan migrate
```

### 2. Email Configuration

Configure your email settings in `Rams/.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourapp.com"
MAIL_FROM_NAME="Your App Name"
```

For development, you can use Mailtrap or Mailpit for testing.

### 3. Frontend Dependencies

Make sure you have the required React dependencies installed:

```bash
cd frontend
npm install
```

### 4. Backend Dependencies

Install Laravel dependencies:

```bash
cd Rams
composer install
```

## How It Works

### User Registration and Login Flow

1. **Registration**: Users register normally without 2FA
2. **Login**: 
   - If 2FA is disabled: User logs in normally
   - If 2FA is enabled: User enters credentials → 6-digit code sent to email → User enters code → Access granted

### Enabling 2FA

1. User goes to Dashboard → Security Settings
2. Clicks "Enable 2FA"
3. Receives verification code via email
4. Enters code to complete setup
5. 2FA is now enabled for future logins

### Disabling 2FA

1. User goes to Dashboard → Security Settings
2. Clicks "Disable 2FA"
3. Confirms the action
4. 2FA is disabled

## API Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout
- `GET /api/auth/user` - Get current user

### Two-Factor Authentication
- `GET /api/two-factor/show` - Show 2FA verification form
- `POST /api/two-factor/verify` - Verify 2FA code
- `POST /api/two-factor/resend` - Resend 2FA code
- `POST /api/two-factor/enable` - Enable 2FA
- `POST /api/two-factor/disable` - Disable 2FA
- `POST /api/two-factor/verify-enable` - Verify code to complete 2FA setup

## Security Features

- **Code Expiration**: 2FA codes expire after 10 minutes
- **Session Management**: 2FA verification is tracked in session
- **Middleware Protection**: Protected routes require 2FA verification
- **Secure Code Generation**: 6-digit random codes
- **Email Verification**: Codes sent only to verified email addresses

## Frontend Routes

- `/` - Landing page
- `/login` - Login page (with 2FA flow)
- `/signup` - Registration page
- `/two-factor` - 2FA verification page
- `/dashboard` - User dashboard with security settings

## Testing the Implementation

1. **Register a new user** via `/signup`
2. **Login** via `/login` (should work normally)
3. **Go to Dashboard** → Security Settings
4. **Enable 2FA** and verify with email code
5. **Logout and login again** - should now require 2FA code
6. **Check email** for the 6-digit verification code
7. **Enter code** to complete login

## Customization

### Code Expiration Time
Modify the expiration time in `Rams/app/Models/User.php`:
```php
'two_factor_expires_at' => now()->addMinutes(10), // Change 10 to desired minutes
```

### Email Template
Customize the email template in `Rams/app/Notifications/TwoFactorCodeNotification.php`

### Styling
Modify the CSS files in `frontend/src/styles/` to match your design

## Troubleshooting

### Common Issues

1. **Email not sending**: Check your SMTP configuration in `.env`
2. **Migration fails**: Ensure database connection is working
3. **Frontend not loading**: Check if all dependencies are installed
4. **2FA codes not working**: Check if codes have expired (10 minutes)

### Debug Mode

Enable debug mode in `Rams/.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

This will help you see detailed error messages in the logs.

## Security Considerations

- Always use HTTPS in production
- Consider rate limiting for 2FA code requests
- Implement account lockout after multiple failed attempts
- Regularly rotate email credentials
- Monitor for suspicious login attempts

## Next Steps

Consider implementing:
- Backup codes for 2FA recovery
- SMS-based 2FA as an alternative
- TOTP (Time-based One-Time Password) support
- Admin panel for managing user 2FA settings
- Audit logging for 2FA events
