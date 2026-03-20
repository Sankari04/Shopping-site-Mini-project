<?php
// pages/index.php
session_start();
require_once '../config/db.php';

$message = '';

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        // Require login to add to cart
        header("Location: login.php");
        exit();
    }
    
    $product_id = (int)$_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    
    // Fetch product details from DB safely
    $stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if ($product) {
        // Initialize user-specific cart if it doesn't exist
        if (!isset($_SESSION['cart'][$user_id])) {
            $_SESSION['cart'][$user_id] = [];
        }
        
        // Add or update quantity in the user's cart
        if (isset($_SESSION['cart'][$user_id][$product_id])) {
            $_SESSION['cart'][$user_id][$product_id]['quantity']++;
        } else {
            $_SESSION['cart'][$user_id][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
        $message = "Added to cart successfully!";
    }
}

// Fetch all products from Database
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<h1 class="page-title">Our Products</h1>
<?php if ($message): ?>
    <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<div class="product-grid">
    <?php foreach ($products as $product): ?>
    <div class="product-card">
        <img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
        <div class="product-info">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p class="price">₹<?php echo htmlspecialchars($product['price']); ?></p>
            <form method="POST" action="index.php">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
