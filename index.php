<?php
session_start();

// Initialize products
$products = [
    1 => ['name' => 'Book', 'price' => 200, 'image' => 'images/book.png'],
    2 => ['name' => 'Pen', 'price' => 20, 'image' => 'images/pen.png'],
    3 => ['name' => 'Notebook', 'price' => 80, 'image' => 'images/notebook.png'],
];

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    if (isset($products[$product_id])) {
        // Initialize cart session if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Add or update quantity
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $products[$product_id]['name'],
                'price' => $products[$product_id]['price'],
                'quantity' => 1
            ];
        }
        $message = "Added to cart successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Shopping Cart - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="brand">StudentShop</a>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>)</a></li>
                <li><a href="checkout.php">Checkout</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <h1 class="page-title">Our Products</h1>
        <?php if (isset($message)): ?>
            <div class="alert success"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="product-grid">
            <?php foreach ($products as $id => $product): ?>
            <div class="product-card">
                <!-- <div class="product-image-placeholder">
                    <span><?php echo $product['name']; ?> Image</span>
                </div> -->
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="price">₹<?php echo htmlspecialchars($product['price']); ?></p>
                    <form method="POST" action="index.php">
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                        <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
