<?php
require '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username or email already exists
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $email]);
    $existingAdmin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingAdmin) {
        $error = 'Username or Email is already taken';
    } else {
        // Hash the password and insert the new admin
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO admins (firstname, lastname, email, username, password) VALUES (:firstname, :lastname, :email, :username, :password)");
        if ($stmt->execute([
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':email' => $email,
            ':username' => $username,
            ':password' => $hashedPassword
        ])) {
            // Redirect to login page after successful registration
            header('Location: login.php');
            exit;
        } else {
            $error = 'An error occurred during registration';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Sign Up</title>
    <link rel="stylesheet" href="signup.style.css">
</head>
<body>
    <div class="container">
        <h1>Cordyceps Admin Sign Up</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="firstname">First name:</label>
            <input type="text" name="firstname" required><br><br>
            <label for="lastname">Last name:</label>
            <input type="text" name="lastname" required><br><br>
            <label for="email">Email:</label>
            <input type="email" name="email" required><br><br>
            <label for="username">Username:</label>
            <input type="text" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" name="password" required><br><br>
            <input type="submit" value="Sign Up">
        </form>
        <div class="login-container">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
