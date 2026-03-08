<?php
session_start();

// Calculate total amount for display
$total_amount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
}

$order_success = false;
$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';

// Handle checkout submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $submitted_name = htmlspecialchars(trim($_POST['username']));
    
    // Set a cookie for the username, expires in 30 days
    setcookie('username', $submitted_name, time() + (86400 * 30), "/");
    $username = $submitted_name; // update for current view
    
    // Process order (simulated)
    $order_success = true;
    
    // Clear the cart session after completion
    unset($_SESSION['cart']);
}

// Redirect to cart if empty and not just ordered
if (!$order_success && empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Shopping Cart - Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="brand">StudentShop</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>)</a></li>
                <li><a href="checkout.php" class="active">Checkout</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <?php if ($order_success): ?>
            <div class="order-confirmation">
                <div class="success-icon">✓</div>
                <h2>Order Confirmed!</h2>
                <p>Thank you for your purchase, <strong><?php echo htmlspecialchars($username); ?></strong>.</p>
                <p>Your order has been placed successfully. The session cart has been cleared.</p>
                <a href="index.php" class="btn btn-primary mt-3">Back to Home</a>
            </div>
        <?php else: ?>
            <h1 class="page-title">Checkout</h1>
            
            <?php if ($username): ?>
                <div class="welcome-back alert info">
                    Welcome back, <strong><?php echo htmlspecialchars($username); ?></strong>! Please confirm your order.
                </div>
            <?php endif; ?>

            <div class="checkout-grid">
                <div class="order-summary card">
                    <h2>Order Summary</h2>
                    <ul class="summary-list">
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                        <li>
                            <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo htmlspecialchars($item['quantity']); ?>)</span>
                            <span>₹<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="summary-total">
                        <strong>Total Amount to Pay:</strong>
                        <span class="total-price">₹<?php echo $total_amount; ?></span>
                    </div>
                </div>

                <div class="checkout-form-container card">
                    <h2>Shipping Details</h2>
                    <form method="POST" action="checkout.php" class="checkout-form">
                        <div class="form-group">
                            <label for="username">Your Name:</label>
                            <input type="text" id="username" name="username" class="form-control" required 
                                value="<?php echo htmlspecialchars($username); ?>" 
                                placeholder="Enter your full name">
                            <small class="help-text">Your name will be saved in a cookie for future visits.</small>
                        </div>
                        <button type="submit" name="checkout" class="btn btn-success btn-lg mt-3 w-100">Place Order</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
