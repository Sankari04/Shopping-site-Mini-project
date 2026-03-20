<?php
// pages/checkout.php
session_start();
require_once '../config/db.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$order_success = false;

// Calculate total amount
$total_amount = 0;
if (isset($_SESSION['cart'][$user_id])) {
    foreach ($_SESSION['cart'][$user_id] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
}

// Redirect to cart if empty and not just ordered
if (!$order_success && empty($_SESSION['cart'][$user_id]) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit();
}

// Handle checkout submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    // In a real application, we would save order details to the DB here (e.g., `orders` and `order_items` tables)
    // For this simple project, we simulate a successful order and clear the cart.
    $order_success = true;
    
    // Clear the cart session for this user after completion
    unset($_SESSION['cart'][$user_id]);
}

require_once '../includes/header.php';
?>

<h1 class="page-title">Checkout</h1>

<main class="container">
    <?php if ($order_success): ?>
        <div class="alert success" style="text-align:center; padding: 4rem 2rem; background: var(--card-bg); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); margin-top:2rem;">
            <div style="font-size: 4rem; color: var(--success-color); margin-bottom: 1rem;">✓</div>
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--text-main);">Order Confirmed!</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 0.5rem;">Thank you for your purchase, <strong><?php echo htmlspecialchars($username); ?></strong>.</p>
            <p style="color: var(--text-muted); margin-bottom: 2.5rem;">Your order has been placed successfully. Your cart has been cleared.</p>
            <a href="index.php" class="btn btn-primary" style="width: auto; padding: 1rem 2rem; font-size: 1.1rem;">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="checkout-layout">
            <div class="checkout-form-container">
                <h2 style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">Shipping Details</h2>
                <form method="POST" action="checkout.php" class="checkout-form">
                    <div class="form-group">
                        <label for="username">Name for Order</label>
                        <!-- Pre-fill with session username -->
                        <input type="text" id="username" name="username" class="form-control" required value="<?php echo htmlspecialchars($username); ?>" readonly>
                        <small style="color:var(--text-muted); font-size:0.85rem; display:block; margin-top:0.5rem;">Ordering as registered user.</small>
                    </div>
                    <!-- Additional fields like Address could go here -->
                    <div style="margin-top: 2rem;">
                        <button type="submit" name="checkout" class="btn btn-primary" style="font-size: 1.1rem; padding: 1.2rem;">Complete Order ✓</button>
                    </div>
                </form>
            </div>

            <div class="checkout-summary-container">
                <div class="product-info" style="border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 2rem; box-shadow: var(--shadow-sm);">
                    <h2 style="margin-bottom: 1.5rem;">Order Summary</h2>
                    <table class="checkout-table" style="margin-top: 0; box-shadow: none; width: 100%;">
                        <tbody>
                            <?php foreach ($_SESSION['cart'][$user_id] as $item): 
                                $product_image = "../images/" . strtolower($item['name']) . ".png";
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
                                <td style="text-align: right; padding: 1rem 0; font-weight: 600;">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 1.2rem; font-weight: 600;">Total Amount to Pay</span>
                        <span style="font-size: 1.8rem; font-weight: 800; color: var(--primary-color);">₹<?php echo number_format($total_amount, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php require_once '../includes/footer.php'; ?>
