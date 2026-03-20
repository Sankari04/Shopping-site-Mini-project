<?php
// includes/header.php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Calculate cart count specific to the logged-in user
$cart_count = 0;
if ($is_logged_in && isset($_SESSION['cart'][$_SESSION['user_id']])) {
    $cart_count = array_sum(array_column($_SESSION['cart'][$_SESSION['user_id']], 'quantity'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentShop</title>
    <!-- Assuming this header is included from the 'pages' directory -->
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="brand">StudentShop</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php">Cart (<?php echo $cart_count; ?>)</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="checkout.php">Checkout</a></li>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main class="container">
