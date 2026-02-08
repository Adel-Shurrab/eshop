# 🛒 UniMart - E-Commerce Platform

> **A complete e-commerce platform built from scratch using pure PHP and custom MVC architecture.**  
> This project demonstrates advanced PHP development skills and modern web application architecture, created as a comprehensive training project before transitioning to Laravel framework.

![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)
![MySQL](https://img.shields.io/badge/MySQL-10.4%2B-orange)
![MVC](https://img.shields.io/badge/Architecture-MVC-purple)
![Status](https://img.shields.io/badge/Status-Completed-success)

## 📋 Table of Contents

- [✨ Features](#-features)
- [🛠️ Tech Stack](#️-tech-stack)
- [💻 System Requirements](#-system-requirements)
- [🚀 Installation](#-installation)
- [⚙️ Configuration](#️-configuration)
- [📁 Project Structure](#-project-structure)
- [📖 Usage](#-usage)
- [🗄️ Database Schema](#️-database-schema)
- [🔒 Security Features](#-security-features)
- [🎯 Project Purpose](#-project-purpose)
- [🤝 Contributing](#-contributing)
- [📄 License](#-license)

## ✨ Features

### Customer Features

- 🛍️ **Product Catalog** - Browse products with hierarchical category navigation
- 🔍 **Advanced Search** - Search and filter products by multiple criteria
- 🛒 **Shopping Cart** - Full cart management with real-time stock validation
- 👤 **User Accounts** - Registration, login, profile management with avatar upload
- 📦 **Order Tracking** - Complete order history and status tracking
- 💳 **Checkout System** - Streamlined checkout process with address validation
- 🎨 **Multi-Image Gallery** - Products support up to 4 images

### Admin Features

- 📊 **Dashboard** - Real-time statistics and analytics
- 📦 **Product Management** - CRUD operations with multi-image upload
- 🗂️ **Category Management** - Hierarchical category system with unlimited depth
- 👥 **User Management** - Complete user administration with role assignment
- 📋 **Order Management** - Order processing with status updates
- 🔍 **Search & Filter** - Advanced search across all entities
- ♻️ **Soft Delete** - Safe deletion with restore capability
- 🎯 **AJAX Operations** - Seamless UI with no page reloads

## 🛠️ Tech Stack

### Backend

- **PHP 8.2+** - Core application logic with strict typing
- **MySQL/MariaDB 10.4+** - Relational database
- **PDO** - Database abstraction layer
- **Custom MVC Framework** - No external frameworks, built from scratch

### Frontend

- **HTML5** - Semantic markup
- **CSS3** - Custom styling with responsive design
- **JavaScript** - AJAX-driven interactions
- **Bootstrap** - UI components (optional)

### Development Tools

- **Composer** - Dependency management and PSR-4 autoloading
- **PHPUnit 11.5+** - Testing framework
- **Git** - Version control

## 💻 System Requirements

- **PHP:** 8.2 or higher
- **MySQL/MariaDB:** 10.4 or higher
- **Apache/Nginx** - Web server with mod_rewrite enabled
- **Composer** - For dependency management
- **Extensions:** PDO, PDO_MySQL, GD (for image processing)

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/unimart.git
cd unimart
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Database Setup

Create a new MySQL database:

```sql
CREATE DATABASE eshopper_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Import the database schema:

```bash
# Import each table schema from app/database/
mysql -u root -p eshopper_db < app/database/users.sql
mysql -u root -p eshopper_db < app/database/products.sql
mysql -u root -p eshopper_db < app/database/categories.sql
mysql -u root -p eshopper_db < app/database/carts.sql
mysql -u root -p eshopper_db < app/database/cart_items.sql
mysql -u root -p eshopper_db < app/database/orders.sql
mysql -u root -p eshopper_db < app/database/order_items.sql
mysql -u root -p eshopper_db < app/database/countries.sql
mysql -u root -p eshopper_db < app/database/states.sql
```

### 4. Configuration

Update database credentials in `app/core/config.php`:

```php
define('DB_NAME', 'eshopper_db');
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_TYPE', 'mysql');
```

### 5. Web Server Configuration

#### Apache (.htaccess already configured)

Ensure mod_rewrite is enabled:

```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

Set document root to `/public` directory.

#### Nginx

Add this configuration to your server block:

```nginx
location / {
    try_files $uri $uri/ /index.php?url=$uri&$args;
}
```

### 6. Set Permissions

```bash
chmod -R 755 public/uploads
chmod -R 755 public/assets
```

### 7. Access the Application

```
Frontend: http://localhost/
Admin Panel: http://localhost/admin
```

**Default Admin Credentials:**

- Email: `admin@example.com`
- Password: `admin123` (change immediately after first login)

## ⚙️ Configuration

### Environment Settings

Edit `app/core/config.php`:

```php
define('WEBSITE_TITLE', 'UniMart');    // Site title
define('THEME', 'eshop');              // Active theme
define('DEBUG', false);                 // Debug mode (true/false)
```

### Session Configuration

Session settings are in `public/index.php`:

```php
session_start([
    'cookie_lifetime' => 86400,        // 24 hours
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'use_strict_mode' => true,
]);
```

## 📁 Project Structure

```
unimart/
├── app/
│   ├── controllers/        # Request handlers
│   │   ├── HomeController.php
│   │   ├── AdminController.php
│   │   ├── AjaxUserController.php
│   │   └── ...
│   ├── models/            # Business logic & data access
│   │   ├── UserModel.php
│   │   ├── ProductModel.php
│   │   ├── CategoryModel.php
│   │   └── ...
│   ├── views/             # Presentation layer
│   │   └── eshop/
│   │       ├── index.php
│   │       ├── admin/
│   │       └── ...
│   ├── core/              # Framework core
│   │   ├── app.php        # Front controller
│   │   ├── database.php   # Database singleton
│   │   ├── controller.php # Base controller
│   │   └── functions.php  # Helper functions
│   ├── database/          # SQL schema files
│   └── init.php           # Bootstrap file
├── public/                # Public web root
│   ├── assets/            # CSS, JS, images
│   ├── uploads/           # User uploads
│   ├── index.php          # Entry point
│   └── .htaccess          # URL rewriting
├── vendor/                # Composer dependencies
├── composer.json
└── README.md
```

## 📖 Usage

### Customer Workflow

1. **Browse Products** - Navigate categories or use search
2. **Add to Cart** - Select products and quantities
3. **Register/Login** - Create account or login (optional for browsing)
4. **Checkout** - Enter shipping details and place order
5. **Track Orders** - View order history in profile

### Admin Workflow

1. **Login** - Access admin panel at `/admin`
2. **Manage Products** - Add/edit/delete products with images
3. **Organize Categories** - Create hierarchical category structure
4. **Process Orders** - Update order and payment status
5. **Manage Users** - Control user accounts and permissions
6. **View Analytics** - Monitor sales and user activity

## 🗄️ Database Schema

### Core Tables

**users** - User accounts with authentication

- Supports admin and customer roles
- Soft delete capability
- Profile information with avatar

**products** - Product catalog

- Multi-image support (4 images)
- Category association
- Stock management
- Soft delete

**categories** - Hierarchical category system

- Parent-child relationships
- Enable/disable status
- Soft delete

**carts** - Shopping carts

- Session and user carts
- Timestamp tracking

**cart_items** - Cart contents

- Product associations
- Quantity and pricing

**orders** - Order records

- Customer information
- Status tracking (pending, processing, shipped, delivered, cancelled)
- Payment status (paid, unpaid, failed)

**order_items** - Order details

- Product snapshots at purchase time

**countries & states** - Location data for addresses

### Relationships

```
users (1) ──< orders
users (1) ──< carts
carts (1) ──< cart_items
products (1) ──< cart_items
products (1) ──< order_items
categories (1) ──< products
categories (self-referencing for hierarchy)
```

## 🔒 Security Features

- **Password Security** - Bcrypt hashing with `password_hash()`
- **SQL Injection Prevention** - PDO prepared statements
- **XSS Protection** - Input sanitization and output escaping
- **CSRF Protection** - Session-based validation
- **Session Security** - HTTP-only, secure cookies
- **File Upload Validation** - Image type and size verification
- **Role-Based Access Control** - Admin/customer permissions
- **Soft Deletes** - Data recovery capability

## 🧪 Testing

Run tests with PHPUnit:

```bash
composer test
```

_Note: Test suite is currently under development._

## 🔧 Development

### Coding Standards

- Follow PSR-4 autoloading
- Use strict types (`declare(strict_types=1)`)
- Implement proper error handling
- Document complex functions
- Follow MVC separation of concerns

### Adding New Features

1. **Model** - Create in `app/models/`
2. **Controller** - Create in `app/controllers/`
3. **Views** - Add to `app/views/eshop/`
4. **Routes** - Automatic based on controller name

Example:

```
URL: /products/view/123
Maps to: ProductsController::view(123)
```

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

Please ensure:

- Code follows existing style
- All tests pass
- New features include tests
- Documentation is updated

## 🎯 Project Purpose

This project was developed as a **comprehensive PHP training project** to master core web development concepts before transitioning to modern frameworks like Laravel. It demonstrates the ability to build a complete, production-ready application from scratch without relying on external frameworks.

### Learning Objectives Achieved ✅

- ✅ **Custom MVC Architecture** - Built from scratch without frameworks
- ✅ **Object-Oriented PHP** - Classes, namespaces, and PSR-4 autoloading
- ✅ **Database Design** - Normalized schema with relationships and constraints
- ✅ **Security Best Practices** - Authentication, authorization, SQL injection prevention
- ✅ **Session Management** - Secure session handling and user state
- ✅ **File Uploads** - Image processing and validation
- ✅ **AJAX Integration** - Asynchronous operations without page reloads
- ✅ **Soft Delete Pattern** - Data recovery and audit trails
- ✅ **Design Patterns** - Singleton, Factory, Active Record
- ✅ **Clean Code** - Maintainable, documented, and well-structured codebase

### What This Project Demonstrates

- **Full-Stack Development** - Complete application from database to UI
- **Architectural Skills** - Ability to design scalable system architecture
- **Problem-Solving** - Complex features like hierarchical categories, cart management
- **Production-Ready Code** - Security, error handling, and user experience focus

## 🚀 Next Steps

This project marks the completion of pure PHP training. The next phase involves:

- Learning **Laravel Framework** and ecosystem
- Applying these foundational concepts in a modern framework context
- Building on this knowledge with advanced features like API development, queue systems, and microservices

## 💡 Potential Enhancements

While this project is complete for its training purpose, potential additions for portfolio expansion could include:

- Payment gateway integration (Stripe, PayPal)
- Email notifications (SMTP/mail services)
- Product reviews and ratings system
- Wishlist functionality
- RESTful API layer
- Advanced search with filters
- Multi-language support

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👤 Author

**Your Name**

- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

## 🙏 Acknowledgments

- Inspired by modern e-commerce platforms
- Built with careful attention to security and user experience
- Community feedback and contributions

## 📞 Support

For support, email support@unimart.com or open an issue on GitHub.

---

**Built with ❤️ using Pure PHP and MVC Architecture**
