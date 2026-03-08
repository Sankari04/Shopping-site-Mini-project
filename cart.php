<?php
session_start();

// Handle remove item
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    header("Location: cart.php");
    exit();
}

// Calculate total amount
$total_amount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Shopping Cart - Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="brand">StudentShop</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php" class="active">Cart (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>)</a></li>
                <li><a href="checkout.php">Checkout</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <h1 class="page-title">Your Shopping Cart</h1>

        <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
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
                        <?php foreach ($_SESSION['cart'] as $id => $item): 
                            $product_image = "images/" . strtolower($item['name']) . ".png";
                        ?>
                        <tr>
                            <td>
                                <div class="product-name-with-img">
                                    <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-thumbnail">
                                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                                </div>
                            </td>
                            <td>₹<?php echo htmlspecialchars($item['price']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td style="font-weight:600; color:var(--primary-color);">₹<?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></td>
                            <td>
                                <a href="cart.php?remove=<?php echo $id; ?>" class="btn btn-danger btn-sm" style="padding:0.5rem 1rem; width:auto; font-size:0.9rem;">Remove</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="cart-summary">
                    <h3><span>Total Amount:</span> <span>₹<?php echo $total_amount; ?></span></h3>
                    <div class="cart-actions">
                        <a href="index.php" class="btn" style="background:var(--secondary-color); color:var(--text-main); border:1px solid var(--border-color);">Continue Shopping</a>
                        <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
