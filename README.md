# Rees Consult - Education Platform

<p align="center">
  <img src="public/images/logo.png" alt="Rees Consult Logo" width="300">
</p>

## About Rees Consult

Rees Consult is a modern educational platform built with Laravel 12, designed to provide a seamless experience for students, educators, and administrators. This custom-built system offers superior performance, security, and maintainability compared to traditional CMS solutions.

## 🚀 Key Features

- **Modern, Responsive Design** - Optimized for all devices
- **Intuitive Admin Panel** - Easy content management for non-technical staff
- **High Performance** - Built with speed and efficiency in mind
- **Secure** - Enterprise-grade security features
- **Scalable** - Ready to grow with your institution's needs

## 🛠 Tech Stack

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript, TailwindCSS
- **Server**: Apache/Nginx

## 📦 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/rees-consult.git
   cd rees-consult
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM dependencies**
   ```bash
   npm install
   npm run build
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Update .env file**
   Update the database configuration and other settings in the `.env` file

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

## 🔐 Default Admin Credentials

- **Email**: admin@reesconsult.com
- **Password**: password (change this immediately after first login)

## 📝 Documentation

For detailed documentation, please refer to the [Documentation](docs/README.md) folder.

## 🛡 Security

If you discover any security related issues, please email security@reesconsult.com instead of using the issue tracker.

## 📄 License

This project is proprietary and confidential. All rights reserved.

## 🤝 Contributing

If you're interested in contributing to this project, please contact the development team.

## 📧 Contact

For more information, please visit [reesconsult.com](https://reesconsult.com) or contact us at info@reesconsult.com
