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

This will create sample users, fellowships, and data for testing.

### Step 8: Start the Application

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

## 🔑 Default Login Credentials

After seeding, you can login with these accounts:

| Role | Email | Password | Description |
|------|-------|----------|-------------|
| **Admin** | admin@firstlove.church | password | Full system access |
| **Pastor** | pastor@firstlove.church | password | Oversee all fellowships |
| **Treasurer** | treasurer@firstlove.church | password | Manage offerings |
| **Leader** | james@firstlove.church | password | UNILUS Fellowship Leader |
| **Leader** | grace@firstlove.church | password | CBU Fellowship Leader |
| **Member** | peter@firstlove.church | password | Regular member |

## 📋 Sample Data Included

The system comes pre-loaded with:

- **4 Fellowships**: UNILUS, CBU, Youth, Women's
- **15+ Users** across all roles
- **8 weeks** of attendance records
- **8 weeks** of offering data
- **4 Announcements** with different priorities
- Realistic phone numbers and names

## 🎯 Testing the System

### As an Admin:
1. Login with admin credentials
2. View overall statistics
3. Manage users and fellowships
4. Access all reports

### As a Pastor:
1. Login with pastor credentials
2. View fellowships under your oversight
3. Monitor attendance and offerings
4. Create announcements

### As a Fellowship Leader:
1. Login with leader credentials
2. Record weekly attendance
3. Submit offering reports
4. View your fellowship statistics

### As a Treasurer:
1. Login with treasurer credentials
2. Review pending offerings
3. Confirm or reject submissions
4. Generate financial reports

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
2. **Update .env** with production database settings
3. **Set APP_ENV=production** and **APP_DEBUG=false**
4. **Run migrations** on production database
5. **Configure web server** to point to `public/` folder

### Security Checklist:
- [ ] Change all default passwords
- [ ] Set strong APP_KEY
- [ ] Enable HTTPS
- [ ] Set proper file permissions
- [ ] Configure backup system

## 📞 Support

For help with setup or usage:

1. Check the main **README.md** for detailed documentation
2. Review Laravel documentation at [https://laravel.com/docs](https://laravel.com/docs)
3. Contact your system administrator

## 🎉 Congratulations!

Your First Love Church Management System is now ready to use. Start by exploring the different dashboards and familiarizing yourself with the features.

**Next Steps:**
1. Change default passwords
2. Add real fellowship members
3. Start recording actual attendance
4. Configure announcements
5. Train users on the system

---

*Built with ❤️ for First Love Church - Foxdale, Lusaka* 