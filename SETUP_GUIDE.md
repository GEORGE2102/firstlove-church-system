# First Love Church Management System - Setup Guide

## 🚀 Quick Start Guide

Follow these steps to get your First Love Church Management System up and running on XAMPP.

### Prerequisites

1. **XAMPP** - Download and install from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. **Composer** - Download from [https://getcomposer.org/](https://getcomposer.org/)
3. **Git** (optional) - For version control

### Step 1: Start XAMPP Services

1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL** services
3. Make sure both services are running (green status)

### Step 2: Create Database

1. Open your browser and go to `http://localhost/phpmyadmin`
2. Click "New" to create a new database
3. Name it: `firstlove_cms`
4. Set Collation to: `utf8mb4_unicode_ci`
5. Click "Create"

### Step 3: Configure Environment

1. Copy `.env.example` to `.env` in your project root
2. Update the database settings in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=firstlove_cms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Step 4: Install Dependencies

Open Command Prompt/Terminal in your project folder and run:

```bash
composer install
```

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

### Step 6: Run Database Migrations

```bash
php artisan migrate
```

### Step 7: Seed Sample Data

```bash
php artisan db:seed
```

This will create sample users, fellowships, and data for initial setup.

### Step 8: Start the Application

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

## 🔑 Default Login Credentials

After seeding, you can login with these accounts:

| Role | Name | Email | Password | Description |
|------|------|-------|----------|-------------|
| **Admin** | System Administrator | admin@firstlove.church | password | Full system access |
| **Pastor** | Pastor John Mwale | pastor@firstlove.church | password | Oversee all fellowships |
| **Treasurer** | Mary Banda | treasurer@firstlove.church | password | Manage offerings |
| **Leader** | James Phiri | james@firstlove.church | password | UNILUS Fellowship Leader |
| **Leader** | Grace Mulenga | grace@firstlove.church | password | CBU Fellowship Leader |
| **Member** | Peter Mwanza | peter@firstlove.church | password | Regular member |

⚠️ **Important:** Change these default passwords before production use!

## 📋 System Features

The system comes pre-configured with:

- **4 Fellowships**: UNILUS, CBU, Youth, Women's
- **15+ Users** across all roles with realistic names
- **8 weeks** of historical attendance records
- **8 weeks** of offering data with different payment methods
- **4 Church announcements** with different priorities
- **Professional UI** with church branding

## 🎯 Using the System

### As an Admin:
1. Login with admin credentials
2. View comprehensive statistics dashboard
3. Manage users across all roles
4. Oversee all fellowships and activities
5. Access complete system reports

### As a Pastor:
1. Login with pastor credentials
2. Monitor fellowships under your oversight
3. Review attendance and offering trends
4. Create church-wide announcements
5. Generate pastoral reports

### As a Fellowship Leader:
1. Login with leader credentials
2. Record weekly attendance for your fellowship
3. Submit offering reports for confirmation
4. View your fellowship's growth statistics
5. Manage fellowship member information

### As a Treasurer:
1. Login with treasurer credentials
2. Review all pending offering submissions
3. Confirm or request clarification on offerings
4. Generate financial reports
5. Monitor church financial health

## 🛠️ Troubleshooting

### Common Issues:

**1. "Target class does not exist" error**
```bash
composer dump-autoload
```

**2. Migration errors**
```bash
php artisan migrate:fresh
php artisan db:seed
```

**3. Permission errors (Linux/Mac)**
```bash
sudo chmod -R 755 storage
sudo chmod -R 755 bootstrap/cache
```

**4. Database connection error**
- Verify MySQL is running in XAMPP
- Check database name exists in phpMyAdmin
- Verify `.env` database settings

### Reset Everything:
```bash
php artisan migrate:fresh --seed
```

## 🌐 Deployment to Production

### For Online Hosting:

1. **Upload files** to your hosting provider
2. **Update .env** with production database settings:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   DB_HOST=your_production_host
   DB_DATABASE=your_production_database
   DB_USERNAME=your_production_username
   DB_PASSWORD=your_production_password
   ```
3. **Run migrations** on production database
4. **Configure web server** to point to `public/` folder

### Security Checklist:
- [ ] Change all default passwords
- [ ] Set strong APP_KEY
- [ ] Enable HTTPS/SSL
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Configure automated database backups
- [ ] Update church contact information
- [ ] Remove development tools and debug information

## 📱 Mobile Access

The system is fully responsive and works on:
- Desktop computers
- Tablets (iPad, Android tablets)
- Mobile phones (iOS, Android)
- All modern web browsers

## 📞 Support & Maintenance

### For Technical Issues:
1. Check the main **README.md** for detailed documentation
2. Review Laravel documentation at [https://laravel.com/docs](https://laravel.com/docs)
3. Contact your system administrator

### Regular Maintenance:
- Weekly database backups
- Monthly password updates for active users
- Quarterly system updates and security patches
- Annual review of user accounts and permissions

## 📈 System Growth

As your church grows, the system can be expanded with:
- Additional fellowship creation
- New user role types
- Enhanced reporting features
- Integration with other church tools
- Mobile app development

## 🎉 Congratulations!

Your First Love Church Management System is now ready for full church operations. The system provides a professional, secure, and user-friendly platform for managing your church community.

**Next Steps:**
1. Customize church information and branding
2. Train fellowship leaders on system usage
3. Begin recording actual attendance and offerings
4. Set up regular backup procedures
5. Plan for future enhancements

---

*Built with ❤️ for First Love Church - Foxdale, Lusaka* 