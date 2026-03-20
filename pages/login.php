<?php
// pages/login.php
session_start();
require_once '../config/db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Fetch user from database securely
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        // Use password_verify to check if the hashed password matches the input
        if ($user && password_verify($password, $user['password'])) {
            // Setup session variables on successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Redirect to home page
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}

require_once '../includes/header.php';
?>
<h1 class="page-title">Login</h1>

<?php if ($error): ?>
    <div class="alert" style="background:var(--danger-color); color:white; padding: 1rem; margin-bottom: 1rem; border-radius: 5px;">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<!-- Login Form -->
<form method="POST" action="login.php" style="max-width: 400px; margin: 0 auto; background: var(--card-bg); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" class="form-control" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Login</button>
    <p style="text-align: center; margin-top: 1rem; color: var(--text-muted);">Don't have an account? <a href="register.php" style="color: var(--primary-color);">Register</a></p>
</form>

<?php require_once '../includes/footer.php'; ?>
