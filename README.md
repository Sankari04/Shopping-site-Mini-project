# PHP Shopping Cart - Full-Stack Upgrade

Welcome to the upgraded, professional version of your PHP Shopping Cart! 
This project has been enhanced with a robust folder structure, MySQL database integration, a secure user authentication system, and improved session handling.

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

---

## 🚀 Setup Instructions (Using XAMPP)

Follow these steps to run the application locally:

### 1. Start XAMPP
Open your XAMPP Control Panel and start **Apache** and **MySQL**.

### 2. Set Up the Database
1. Open your browser and go to `http://localhost/phpmyadmin/`.
2. Click on the **SQL** tab at the top.
3. Open the `setup.sql` file provided in this folder, copy all of its contents, and paste it into the SQL box.
4. Click **Go** (bottom right). This will automatically:
   - Create a database called `shopping_db`.
   - Create the `users` and `products` tables.
   - Insert 3 dummy products into the database.

### 3. Run the Application
1. Ensure this entire project folder (`shopping-site`) is inside `XAMPP/htdocs/`.
2. Open your browser and go to `http://localhost/shopping-site/`.
3. You should see the Home page. Click "Register" to create an account, then "Login" to start shopping!

---

## 🧠 Core Concepts Explained

Here’s a breakdown of how the professional features work:

### 1. How the Login System Works
In `pages/register.php` and `pages/login.php`, we handle authentication:
- **Registration**: When a user registers, we take their password and run it through `password_hash()`. This creates a secure, scrambled string. We NEVER save plain-text passwords in the database (a critical security rule!).
- **Login Check**: During login, we fetch the user by their username. We then use `password_verify()` to securely check if the entered password matches the hashed version in the database.
- **Session State**: If correct, we store their unique `id` and `username` inside the `$_SESSION` array (`$_SESSION['user_id'] = $user['id'];`). This allows the server to remember who they are as they move from page to page.

### 2. How Sessions Are Used (User-Specific Cart)
The superglobal `$_SESSION` is used to persist data across page reloads.
- To prevent different logged-in users on the same computer from sharing a cart, we structure the session cart like this: `$_SESSION['cart'][ $user_id ]`.
- Example: If User 1 logs in, their cart is stored at `$_SESSION['cart'][1]`. If User 5 logs in, their cart is at `$_SESSION['cart'][5]`.
- This ensures absolute data separation!

### 3. How the Database is Connected (`config/db.php`)
Instead of the older `mysqli_*` functions, we used **PDO (PHP Data Objects)**.
- **Why PDO?** It’s modern, supports many different database types, and makes security easier.
- We wrap our connection string in a `try { ... } catch { ... }` block so if the database server is down, the website gracefully stops and shows an error instead of leaking sensitive connection details.

### 4. Security Enhancements Added
- **Prepared Statements**: Look at the queries in `login.php` or `register.php`. Instead of injecting variables directly into SQL (`SELECT * FROM users WHERE username = '$user'`), we use `?` placeholders (`SELECT * FROM users WHERE username = ?`) and then strictly pass variables via `$stmt->execute([$username])`. This completely eliminates the risk of **SQL Injection**.
- **Cross-Site Scripting (XSS) Prevention**: Whenever we output data from the database or from a user form (like names or product titles), we wrap it in `htmlspecialchars()`. This turns dangerous code inputs into harmless text.

Happy coding! This structure is an excellent foundation for any future backend internship or project.
