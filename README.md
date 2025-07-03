# 🛒 Grocery Club - E-commerce Management System

**University Project - Web Application Development**

A comprehensive grocery store management system built with Laravel, featuring modern web technologies and real-time functionality. This project was developed as part of a university course to demonstrate full-stack web development skills and modern software engineering practices.

## 📋 Project Overview

Grocery Club is a multi-role e-commerce platform that simulates a complete grocery store ecosystem. The system supports different user types (Board Members, Employees, and Members) with role-specific functionalities, from inventory management to customer shopping experiences.

### 🎯 Key Objectives

- Implement a scalable e-commerce solution using Laravel framework
- Demonstrate understanding of modern web development patterns
- Practice database design and management
- Implement real-time features and background job processing
- Apply security best practices and user authentication
- Create an intuitive user interface with responsive design

## ✨ Features

### 🔐 Authentication & User Management
- **Multi-role Authentication**: Board members, employees, and customers with specific permissions
- **Email Verification**: Automated email verification system using Mailtrap.io
- **Password Recovery**: Secure password reset functionality
- **User Profiles**: Comprehensive profile management with role-specific customization
- **Virtual Card System**: Automatic virtual card generation for payments
- **Membership Management**: Fee-based membership activation system

### 🛍️ E-commerce Platform
- **Product Catalog**: Browse products with advanced filtering and sorting
- **Shopping Cart**: Full-featured cart with real-time updates
- **Secure Checkout**: Multi-step checkout process with payment validation
- **Order Management**: Complete order lifecycle from creation to completion
- **Stock Management**: Real-time inventory tracking and alerts

### 💳 Payment System
- **Multiple Payment Methods**: Visa, PayPal, and MB WAY simulation
- **Payment Validation**: Comprehensive validation for each payment type
- **Virtual Wallet**: Card-based balance system
- **Transaction History**: Detailed operation logs with PDF receipts

### 📦 Inventory & Operations
- **Stock Control**: Real-time inventory management
- **Restock Orders**: Automated reordering system
- **Order Processing**: Employee workflow for order fulfillment
- **PDF Generation**: Automated receipt and document generation
- **Email Notifications**: Queue-based email system for order updates

### ⚙️ Business Management
- **Category Management**: CRUD operations for product categories
- **Product Management**: Comprehensive product administration
- **Pricing Control**: Dynamic pricing with discount support
- **Shipping Configuration**: Flexible shipping cost management
- **Business Settings**: Configurable membership fees and operational parameters

### 🚀 Technical Features
- **Queue System**: Background job processing for emails and PDF generation
- **Caching**: Optimized performance with strategic caching implementation
- **Real-time Updates**: Live cart and inventory updates
- **Responsive Design**: Mobile-friendly interface
- **Database Optimization**: Efficient queries and relationship management

## 🛠️ Technology Stack

- **Backend**: Laravel 12 (PHP)
- **Frontend**: Livewire, Tailwind CSS
- **Database**: MySQL
- **Queue**: Laravel Horizon
- **Email**: Laravel Mail with Mailtrap.io
- **PDF Generation**: Laravel PDF
- **Build Tools**: Vite

## 📊 Database Schema

The system uses a normalized database design with the following key entities:
- Users (with role-based access)
- Products & Categories
- Orders & Order Items
- Cards & Operations
- Stock Adjustments
- Supply Orders
- And more...

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL
- Git

### Installation Steps

1. **Clone the repository**
```bash
git clone https://github.com/shadowoff09/GroceryClub.git
cd GroceryClub
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Environment configuration**
```bash
cp .env.example .env
```

4. **Configure your database in `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=grocery_club
DB_USERNAME=root
DB_PASSWORD=
```

5. **Generate application key**
```bash
php artisan key:generate
```

6. **Verify database connection**
```bash
php artisan db:check
```

7. **Run database migrations**
```bash
php artisan migrate:fresh
```

8. **Seed the database**
```bash
# Note: Disable Windows Defender real-time protection for faster seeding
php artisan db:seed
```

9. **Install frontend dependencies**
```bash
npm install
```

10. **Build assets**
```bash
npm run build
```

11. **Start the development server**
```bash
composer run dev
```

12. **Access the application**
Open your browser and navigate to `http://localhost:8000`

## 👥 Demo Credentials

### Board Member
- **Email**: b1@mail.pt
- **Password**: 123
- **Access**: Full system administration

### Employee
- **Email**: e1@mail.pt
- **Password**: 123
- **Access**: Order processing, inventory management

### Member (Customer)
- **Email**: m1@mail.pt
- **Password**: 123
- **Access**: Shopping, order history, profile management

## 📁 Project Structure

```
app/
├── Http/Controllers/     # Application controllers
├── Livewire/            # Livewire components
├── Models/              # Eloquent models
├── Services/            # Business logic services
├── Jobs/                # Background jobs
├── Mail/                # Email templates
└── Policies/            # Authorization policies

resources/
├── views/               # Blade templates
├── css/                 # Stylesheets
└── js/                  # JavaScript files

database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders
```

## 🎓 Academic Context

This project was developed as part of a university course in Web Application Development. It demonstrates:

- **Full-stack Development**: Complete application from database to user interface
- **Software Engineering**: Clean architecture and design patterns
- **Database Design**: Normalized schema with efficient relationships
- **Security Implementation**: Authentication, authorization, and data validation
- **Performance Optimization**: Caching strategies and queue processing
- **Modern Web Standards**: Responsive design and progressive enhancement
- **Project Management**: Version control and structured development process

## 🤝 Contributing

This is an academic project. For educational purposes, feel free to:
- Review the code structure and implementation
- Study the design patterns used
- Examine the database relationships
- Test the functionality with the provided demo accounts