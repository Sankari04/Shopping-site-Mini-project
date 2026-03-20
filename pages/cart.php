<?php
// pages/cart.php
session_start();
require_once '../config/db.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle remove item
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$user_id][$remove_id])) {
        unset($_SESSION['cart'][$user_id][$remove_id]);
    }
    header("Location: cart.php");
    exit();
}

// Handle update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity > 0 && isset($_SESSION['cart'][$user_id][$product_id])) {
        $_SESSION['cart'][$user_id][$product_id]['quantity'] = $quantity;
    } elseif ($quantity <= 0 && isset($_SESSION['cart'][$user_id][$product_id])) {
        // Remove item if quantity is set to 0 or less
        unset($_SESSION['cart'][$user_id][$product_id]);
    }
    header("Location: cart.php");
    exit();
}

// Calculate total amount
$total_amount = 0;
if (isset($_SESSION['cart'][$user_id])) {
    foreach ($_SESSION['cart'][$user_id] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
}

require_once '../includes/header.php';
?>

<h1 class="page-title">Your Shopping Cart</h1>

<?php if (!isset($_SESSION['cart'][$user_id]) || empty($_SESSION['cart'][$user_id])): ?>
    <div class="empty-cart">
        <h2>Your cart is empty</h2>
        <p>Looks like you haven't added anything to your cart yet.</p>
        <div style="margin-top: 2rem;">
            <a href="index.php" class="btn btn-primary">Start Shopping</a>
        </div>
    </div>
<?php else: ?>
    <div class="cart-container">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'][$user_id] as $id => $item): 
                    $product_image = "../images/" . strtolower($item['name']) . ".png";
                ?>
                <tr>
                    <td>
                        <div class="product-name-with-img">
                            <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-thumbnail">
                            <span><?php echo htmlspecialchars($item['name']); ?></span>
                        </div>
                    </td>
                    <td>₹<?php echo htmlspecialchars($item['price']); ?></td>
                    <td>
                        <form method="POST" action="cart.php" style="display:flex; gap:0.5rem; align-items:center;">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <input type="number" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1" style="width: 60px; padding: 0.3rem;">
                            <button type="submit" name="update_quantity" class="btn btn-primary btn-sm" style="padding:0.3rem 0.6rem;">Update</button>
                        </form>
                    </td>
                    <td style="font-weight:600; color:var(--primary-color);">₹<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></td>
                    <td>
                        <a href="cart.php?remove=<?php echo $id; ?>" class="btn btn-danger btn-sm" style="padding:0.5rem 1rem; width:auto; font-size:0.9rem;">Remove</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-summary">
            <h3><span>Total Amount:</span> <span>₹<?php echo number_format($total_amount, 2); ?></span></h3>
            <div class="cart-actions">
                <a href="index.php" class="btn" style="background:var(--secondary-color); color:var(--text-main); border:1px solid var(--border-color);">Continue Shopping</a>
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>
