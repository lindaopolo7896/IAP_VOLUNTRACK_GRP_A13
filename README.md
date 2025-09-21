# VolunTrack Backend


## Requirements

- PHP 8.0 or higher
- Composer
- MySQL database
- Git

## Quick Start

### 1. Clone and Setup
```bash
cp .env.example .env
```

### 2. Configure Environment
Edit `.env` file and set your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=volunTrack
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

For email functionality, configure mail settings:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD="your_app_password"
MAIL_ENCRYPTION=tls
```

### 3. Install Dependencies
```bash
composer install
php artisan optimise
php artisan key:generate
php artisan migrate
```

### 4. Start Development Server
```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`

## Features

### Authentication System
- User registration with email verification
- Email-based account verification
- Secure login/logout functionality
- Password reset capabilities


## API Endpoints

- `POST /api/register` - User registration
- `POST /api/login` - User authentication
- `POST /api/logout` - User logout
- Email verification endpoints

## Troubleshooting

**Email Issues**: Ensure you're using Gmail App Passwords, not regular passwords. Enable 2FA first, then generate an App Password.

**Database Connection**: Verify MySQL is running and credentials are correct in `.env`

**Cache Issues**: Run `php artisan config:clear` if configuration changes don't take effect


