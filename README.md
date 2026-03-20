# Shopping Cart Web Application

## Features
- User Registration & Login
- Add to Cart / Remove Items
- Session-based Cart (user-specific)
- MySQL Database Integration
- Secure Authentication (password hashing)

## Tech Stack
- HTML, CSS
- PHP
- MySQL

## Setup
1. Start XAMPP (Apache + MySQL)
2. Import setup.sql in phpMyAdmin
3. Run: http://localhost/shopcart_miniproject/
   
## Folder Structure

```
shopping-site/
├── config/
│   └── db.php          <-- Handles secure MySQL connection using PDO.
├── images/             <-- Product images.
├── includes/
│   ├── header.php      <-- Common head, navbar, and session logic.
│   └── footer.php      <-- Common footer and closing tags.
├── pages/
│   ├── index.php       <-- Main products page (fetches from DB).
│   ├── cart.php        <-- Cart page (user-specific sessions).
│   ├── checkout.php    <-- Checkout page (processes order, clears cart).
│   ├── login.php       <-- User login form & logic.
│   ├── register.php    <-- User registration form & logic.
│   └── logout.php      <-- Destroys auth session.
├── index.php           <-- Root redirect to pages/index.php.
├── cart.php            <-- Root redirect to pages/cart.php.
├── checkout.php        <-- Root redirect to pages/checkout.php.
├── style.css           <-- Main stylesheet.
├── setup.sql           <-- SQL file to quickly build the database.
└── README.md           <-- This file!
```

## What I Learned
- PHP Sessions for state management
- User authentication system
- Database integration using PDO
- Basic web security (SQL Injection prevention)
