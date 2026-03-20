-- Create the database
CREATE DATABASE IF NOT EXISTS shopping_db;

-- Use the newly created database
USE shopping_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL
);

-- Insert initial dummy products (ignoring if they already exist, but for simple script, we just insert)
-- Using INSERT IGNORE to prevent duplicates if script is run multiple times
INSERT IGNORE INTO products (id, name, price, image) VALUES
(1, 'Book', 200.00, 'images/book.png'),
(2, 'Pen', 20.00, 'images/pen.png'),
(3, 'Notebook', 80.00, 'images/notebook.png');
