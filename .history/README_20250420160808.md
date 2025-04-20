# UniMart - Complete eCommerce Platform

A comprehensive PHP-based eCommerce solution built with modern web technologies. UniMart offers a feature-rich shopping experience for customers and powerful management tools for administrators.

![eshop](assets/theme/images/home/logo.png)

## 📋 Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Structure](#database-structure)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Admin Dashboard](#admin-dashboard)
- [Extending the Application](#extending-the-application)
- [Security Features](#security-features)
- [Contributing](#contributing)
- [License](#license)

## ✨ Features

### Customer Features
- **User Account Management**
  - Registration and authentication system
  - Profile management (personal information, addresses)
  - Order history tracking
  - Password reset functionality
  
- **Shopping Experience**
  - Browse products by categories
  - Advanced product search and filtering
  - Detailed product pages with multiple images
  - Related product suggestions
  - Recently viewed products tracking
  
- **Shopping Cart**
  - AJAX-powered cart operations
  - Real-time cart updates
  - Cart persistence between sessions
  
- **Checkout Process**
  - Multi-step checkout flow
  - Address management
  - Order review and confirmation
  - Support for guest checkout
  
- **Order Management**
  - Order status tracking
  - Order history view
  - Order details and items display

### Admin Features
- **Dashboard Overview**
  - Sales statistics and metrics
  - Recent orders and activity logs
  
- **Comprehensive Product Management**
  - Add/edit/delete products
  - Product categories and attributes
  - Product image management
  - Inventory tracking
  
- **Order Processing**
  - View and manage customer orders
  - Update order status (pending, processing, shipped, delivered)
  - Update payment status
  
- **User Management**
  - Customer accounts overview
  - User roles and permissions
  - Account status management

## 🔧 Technology Stack

- **Backend**
  - PHP 7.4 or higher
  - Custom MVC framework
  - PDO for database operations
  
- **Frontend**
  - HTML5, CSS3, JavaScript
  - Bootstrap for responsive design
  - jQuery for DOM manipulation
  - AJAX for asynchronous operations
  
- **Database**
  - MySQL/MariaDB
  
- **Other**
  - Composer for dependency management
  - Custom form validation
  - Session-based authentication

## 📦 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- mod_rewrite enabled
- Composer
- GD Library for image processing
- At least 20MB of disk space

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/unimart.git
   cd unimart
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Set up the database**
   - Create a MySQL database named `eshopper_db`
   - Import the database schema from `app/database/schema.sql` (if available)

4. **Configure web server**
   - Point your web server's document root to the `public` directory
   - Ensure the `uploads` directory is writable by the web server
   ```bash
   chmod -R 755 public/uploads/
   ```

5. **Set up virtual host (optional)**
   - Configure a virtual host in your web server pointing to the public directory
   - Example for Apache:
   ```apache
   <VirtualHost *:80>
       ServerName unimart.local
       DocumentRoot /path/to/unimart/public
       <Directory "/path/to/unimart/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

6. **Update hosts file (if using virtual host)**
   - Add the following line to your hosts file:
   ```
   127.0.0.1 unimart.local
   ```

7. **Start your application**
   - Visit your domain (e.g., http://unimart.local) in a web browser

## ⚙️ Configuration

### Database Configuration
Edit `app/core/config.php` to match your database settings:

```php
// Database
define('DB_NAME', 'eshopper_db');
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_TYPE', 'mysql');
```

### Application Settings
Other settings in the same file:

```php
define('WEBSITE_TITLE', 'UniMart');
define('THEME', 'eshop');
define('DEBUG', false); // Set to true for development
```

## 🗄️ Database Structure

The application uses several key tables:

- **users**: Stores customer and admin account information
- **products**: Contains product details including prices and inventory
- **categories**: Product categories and hierarchy
- **orders**: Customer orders and their status
- **order_items**: Individual items within orders
- **cart**: Shopping cart data for logged-in users

## 🔍 Usage

### Customer Journey
1. Browse products or search for specific items
2. View product details and add to cart
3. Review cart and proceed to checkout
4. Enter shipping details
5. Confirm order
6. Track order status from account dashboard

### Admin Management
1. Access admin panel at `/admin`
2. Log in with admin credentials
3. Manage products, orders, and customers
4. View sales reports and statistics

## 📁 Project Structure

```
unimart/
├── app/                    # Application core files
│   ├── controllers/        # Controller classes
│   ├── core/               # Framework core components
│   ├── database/           # Database migrations and seeds
│   ├── models/             # Database models
│   └── views/              # View templates
│       └── eshop/          # Main theme views
│           └── admin/      # Admin panel views
├── assets/                 # Public assets
│   ├── admin/              # Admin panel assets
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   └── theme/              # Theme-specific assets
├── public/                 # Publicly accessible files
│   ├── uploads/            # User uploaded files
│   ├── .htaccess           # URL rewrite rules
│   └── index.php           # Application entry point
├── vendor/                 # Composer dependencies
├── composer.json           # Composer configuration
└── README.md               # Project documentation
```

## 🛠️ Admin Dashboard

Access the admin dashboard at `/admin` with the following default credentials:
- Email: admin@example.com
- Password: admin123

The admin panel includes:
- **Products Management**: Add, edit, delete products and manage inventory
- **Categories Management**: Create and organize product categories
- **Orders Management**: View and process customer orders
- **User Management**: Manage customer accounts and admin users
- **Reports**: View sales data and generate reports

## 🔌 Extending the Application

### Adding a New Controller
1. Create a new file in `app/controllers/` (e.g., `NewFeatureController.php`)
2. Extend the base Controller class:
   ```php
   <?php
   namespace App\Controllers;
   
   use App\Core\Controller;
   
   class NewFeatureController extends Controller
   {
       public function index()
       {
           $data['page_title'] = 'New Feature';
           $this->view('/new_feature', $data);
       }
   }
   ```
3. Create corresponding view file in `app/views/eshop/`

### Creating a New Model
1. Create a new file in `app/models/` (e.g., `NewModel.php`)
2. Implement your model class:
   ```php
   <?php
   namespace App\Models;
   
   use App\Core\Database;
   
   class NewModel
   {
       private $db;
       
       public function __construct()
       {
           $this->db = Database::getInstance();
       }
       
       // Add your methods here
   }
   ```

## 🔒 Security Features

- CSRF protection for forms
- Input sanitization and validation
- Prepared statements for database queries
- Password hashing with modern algorithms
- Session security measures
- XSS protection

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

## 📜 License

This project is licensed under the [MIT License](LICENSE) - see the LICENSE file for details.

## 📧 Contact

For support or inquiries, please contact [adelshurrab2003@gmail.com]

---

© [2025] UniMart. All Rights Reserved.
