<?php
// pages/register.php
session_start();
require_once '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Prevent SQL Injection using Prepared Statements
        // We check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Username already taken. Please choose another one.";
        } else {
            // Hash password for security before storing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user into database securely
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashed_password])) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Something went wrong. Please try again later.";
            }
        }
    }
}

require_once '../includes/header.php';
?>
<h1 class="page-title">Register</h1>

<?php if ($error): ?>
    <div class="alert" style="background:var(--danger-color); color:white; padding: 1rem; margin-bottom: 1rem; border-radius: 5px;">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert success"><?php echo htmlspecialchars($success); ?> <a href="login.php" style="font-weight: bold;">Login here</a></div>
<?php endif; ?>

<!-- Registration Form -->
<form method="POST" action="register.php" style="max-width: 400px; margin: 0 auto; background: var(--card-bg); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" class="form-control" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Register</button>
    <p style="text-align: center; margin-top: 1rem; color: var(--text-muted);">Already have an account? <a href="login.php" style="color: var(--primary-color);">Login</a></p>
</form>

<?php require_once '../includes/footer.php'; ?>
