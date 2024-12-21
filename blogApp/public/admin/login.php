<?php
require '../../config/config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username']); // Correct variable name
    $password = $_POST['password'];

    try {
        // Check if the admin exists
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            // Regenerate session ID for security
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = 'admin';

            // Redirect to dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    } catch (PDOException $e) {
        // Log the error and display a generic message
        error_log("Database error: " . $e->getMessage());
        $error = 'An error occurred. Please try again later.';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="login.style.css">
</head>

<body>
    <div class="container">
        <h1>Cordyceps Admin Login</h1>
        <!-- Display error message if set -->
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Login form -->
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br><br>

            <input type="submit" value="Login">
        </form>

        <!-- Link to the sign-up page -->
        <div class="signup-container">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>
</body>

</html>
