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
            <div class="alert success" style="text-align:center; padding: 4rem 2rem; background: var(--card-bg); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); margin-top:2rem;">
                <div style="font-size: 4rem; color: var(--success-color); margin-bottom: 1rem;">✓</div>
                <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--text-main);">Order Confirmed!</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 0.5rem;">Thank you for your purchase, <strong><?php echo htmlspecialchars($username); ?></strong>.</p>
                <p style="color: var(--text-muted); margin-bottom: 2.5rem;">Your order has been placed successfully. The session cart has been cleared.</p>
                <a href="index.php" class="btn btn-primary" style="width: auto; padding: 1rem 2rem; font-size: 1.1rem;">Continue Shopping</a>
            </div>
        <?php else: ?>
            <h1 class="page-title">Checkout</h1>
            
            <?php if ($username): ?>
                <div class="alert" style="background:var(--secondary-color); color:var(--text-main); border-left:4px solid var(--primary-color);">
                    Welcome back, <strong><?php echo htmlspecialchars($username); ?></strong>! Please confirm your order.
                </div>
            <?php endif; ?>

            <div class="checkout-layout">
                <div class="checkout-form-container">
                    <h2 style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">Shipping Details</h2>
                    <form method="POST" action="checkout.php" class="checkout-form">
                        <div class="form-group">
                            <label for="username">Your Full Name</label>
                            <input type="text" id="username" name="username" class="form-control" required 
                                value="<?php echo htmlspecialchars($username); ?>" 
                                placeholder="e.g. John Doe">
                            <small style="color:var(--text-muted); font-size:0.85rem; display:block; margin-top:0.5rem;">Your name will be saved in a cookie for future visits.</small>
                        </div>
                        <div style="margin-top: 2rem;">
                            <button type="submit" name="checkout" class="btn btn-primary" style="font-size: 1.1rem; padding: 1.2rem;">Complete Order</button>
                        </div>
                    </form>
                </div>

                <div class="checkout-summary-container">
                    <div class="product-info" style="border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 2rem; box-shadow: var(--shadow-sm);">
                        <h2 style="margin-bottom: 1.5rem;">Order Summary</h2>
                        <table class="checkout-table" style="margin-top: 0; box-shadow: none; width: 100%;">
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $item): 
                                    $product_image = "images/" . strtolower($item['name']) . ".png";
                                ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 1rem 0;">
                                        <div class="product-name-with-img">
                                            <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-thumbnail" style="width: 50px; height: 50px;">
                                            <div>
                                                <div style="font-weight: 600; color: var(--text-main);"><?php echo htmlspecialchars($item['name']); ?></div>
                                                <div style="font-size: 0.85rem; color: var(--text-muted);">Qty: <?php echo htmlspecialchars($item['quantity']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align: right; padding: 1rem 0; font-weight: 600;">₹<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div style="margin-top: 1.5rem; padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 1.2rem; font-weight: 600;">Total Amount to Pay</span>
                            <span style="font-size: 1.8rem; font-weight: 800; color: var(--primary-color);">₹<?php echo $total_amount; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
