<?php
// config/db.php

// Database configuration settings
$host = 'localhost';
$dbname = 'shopping_db';
$username = 'root'; // default XAMPP username
$password = ''; // default XAMPP password is empty

// We use PDO (PHP Data Objects) for a secure, professional way to connect to MySQL
// This helps prevent SQL injection when combined with prepared statements.
try {
    // Determine the DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    
    // Create a new PDO instance
    $pdo = new PDO($dsn, $username, $password);
    
    // Set attributes for better default exception handling and data types
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // If connection fails, stop execution and show error message
    die("Database connection failed: " . $e->getMessage());
}
?>
