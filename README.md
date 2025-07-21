# Watch & Earn Laravel System

A comprehensive Laravel-based earning system where users watch YouTube videos to earn money, with package management and referral system.

## Features

### 🎯 User Features
- **Dashboard**: View earnings, video limits, and referral income
- **Video Watching**: Watch YouTube videos with anti-cheat protection
- **Package System**: Buy packages to unlock earning potential
- **Withdrawal System**: Request money withdrawals (requires 3+ referrals)
- **Referral Program**: Earn Rs. 50 per successful referral

### 👨‍💼 Manager Features
- **Video Management**: Add, edit, delete YouTube videos with rewards
- **Package CRUD**: Full package management (price, duration, limits)
- **Withdrawal Approval**: Approve/reject user withdrawal requests
- **User Management**: Ban users with custom messages
- **Referral Tracking**: Monitor referral system performance

### 🔧 Admin Features
- **System Monitoring**: Overview of all platform activity
- **Manager Management**: Create and manage manager accounts
- **Withdrawal History**: View all withdrawal transactions
- **User Analytics**: Track user engagement and earnings

## Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Blade Templates + TailwindCSS
- **Database**: MySQL
- **JavaScript**: jQuery for anti-cheat video watching
- **Authentication**: Custom manual auth system

## Installation

1. **Clone the repository**
\`\`\`bash
git clone <repository-url>
cd watch-earn-laravel
\`\`\`

2. **Install dependencies**
\`\`\`bash
composer install
npm install
\`\`\`

3. **Environment setup**
\`\`\`bash
cp .env.example .env
php artisan key:generate
\`\`\`

4. **Database setup**
\`\`\`bash
# Configure your database in .env file
php artisan migrate
php artisan db:seed
\`\`\`

5. **Start the application**
\`\`\`bash
php artisan serve
npm run dev
\`\`\`

## Default Accounts

After seeding, you can login with:

- **Admin**: admin@watchearn.com / password
- **Manager**: manager@watchearn.com / password

## Package Structure

### Controllers
- `Auth/` - Login and Registration
- `User/` - User dashboard, videos, packages, withdrawals
- `Manager/` - Video/package management, withdrawal approvals
- `Admin/` - System administration

### Models
- `User` - Users with role-based access
- `Package` - Earning packages with limits and rewards
- `Video` - YouTube videos with earning rewards
- `UserVideo` - Track user video watching history
- `Purchase` - User package purchases
- `WithdrawalRequest` - Money withdrawal requests
- `Referral` - Referral system tracking

### Key Features

#### Anti-Cheat System
- Tab switching detection
- Full video duration requirement
- No skipping allowed
- Right-click and keyboard shortcut prevention

#### Role-Based Access
- Custom middleware for user/manager/admin roles
- Protected routes based on user permissions

#### Referral System
- Unique referral codes for each user
- Rs. 50 reward per successful referral
- Minimum 3 referrals required for withdrawals

## Usage

### For Users
1. Register with optional referral code
2. Purchase a package (Rs. 1000 - Rs. 5000)
3. Watch videos daily within package limits
4. Earn money for each completed video
5. Refer friends to unlock withdrawal eligibility
6. Request withdrawals after 3+ referrals

### For Managers
1. Add YouTube videos with custom rewards
2. Create and manage earning packages
3. Approve user withdrawal requests
4. Monitor user activity and ban if needed

### For Admins
1. Create manager accounts
2. Monitor overall system performance
3. View all withdrawal history
4. Oversee manager activities

## Security Features

- CSRF protection on all forms
- Role-based middleware protection
- Anti-cheat video watching system
- User ban system with custom messages
- Secure password hashing

## Database Schema

The system uses 7 main tables:
- `users` - User accounts and roles
- `packages` - Earning packages
- `videos` - YouTube videos with rewards
- `user_videos` - Video watching history
- `purchases` - Package purchases
- `withdrawal_requests` - Money withdrawal requests
- `referrals` - Referral tracking

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is open-sourced software licensed under the MIT license.
