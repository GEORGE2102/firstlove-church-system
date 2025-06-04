# First Love Church Management System

A comprehensive digital Church Management System for First Love Church - Foxdale, Lusaka.

## 🎯 Project Overview

The First Love Church Management System is designed to streamline administrative, financial, and operational workflows, connecting the central church, pastors, fellowship leaders, and members through a centralized digital platform.

## 🚀 Features

### Core Modules
- **User Management**: Role-based access control (Admin, Pastor, Fellowship Leader, Treasurer, Member)
- **Fellowship Management**: Create and manage fellowships with leader assignments
- **Attendance Tracking**: Weekly Bible study attendance recording and reporting
- **Offering Management**: Digital offering submission and confirmation workflow
- **Dashboard Systems**: Customized dashboards for different user roles
- **Communication Module**: Announcements and messaging system
- **Analytics & Reports**: Comprehensive reporting and data visualization

### User Roles & Access
- **Admin**: Full system access, user management, system settings
- **Pastor (Overseer)**: Monitor constituencies, view reports, send announcements
- **Fellowship Leader**: Record attendance/offerings, manage fellowship info
- **Treasurer**: Confirm offerings, manage financial reports
- **Member**: View attendance, receive announcements, check events

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend**: PHP 8.1+ with Laravel 10
- **Database**: MySQL 8.0+
- **Development Environment**: XAMPP
- **Version Control**: Git

## 📋 Installation & Setup

### Prerequisites
- XAMPP (Apache, MySQL, PHP 8.1+)
- Composer
- Node.js & NPM (optional, for asset compilation)

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd firstlove-church-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   - Create a MySQL database named `firstlove_cms`
   - Update `.env` file with your database credentials
   - Run migrations:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   ```

## 📊 Database Structure

### Core Tables
- `users` - User authentication and basic info
- `roles` - System roles (Admin, Pastor, Leader, Treasurer, Member)
- `fellowships` - Fellowship information and management
- `attendance` - Weekly attendance records
- `offerings` - Offering submissions and confirmations
- `announcements` - System-wide communications

## 🔐 Default Login Credentials

After seeding, use these credentials:
- **Admin**: admin@firstlove.church / password
- **Pastor**: pastor@firstlove.church / password
- **Treasurer**: treasurer@firstlove.church / password

## 📱 API Endpoints

The system provides RESTful API endpoints for:
- User authentication
- Fellowship management
- Attendance recording
- Offering submissions
- Report generation

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📞 Support

For support and inquiries, contact the development team or create an issue in the repository.

## 📄 License

This project is developed specifically for First Love Church - Foxdale, Lusaka.

---
*Developed with ❤️ for First Love Church Community*
