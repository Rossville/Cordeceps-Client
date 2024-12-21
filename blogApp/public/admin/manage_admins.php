<?php
require '../../config/config.php';
require '../../src/auth.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admins = $pdo->query("SELECT * FROM admins")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        deleteAdmin($pdo, $_POST['admin_id']);
        header('Location: manage_admins.php');
        exit;
    } elseif (isset($_POST['update'])) {
        $username = $_POST['username'];
        $password = $_POST['password'] ? $_POST['password'] : null;
        updateAdmin($pdo, $_POST['admin_id'], $username, $password);
        header('Location: manage_admins.php');
        exit;
    } elseif (isset($_POST['add'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        registerAdmin($pdo, $username, $password);
        header('Location: manage_admins.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Admins</title>
</head>
<body>
    <h1>Manage Admins</h1>
    <a href="dashboard.php">Back to Dashboard</a>
    <h2>Admins</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($admins as $admin): ?>
        <tr>
            <td><?= htmlspecialchars($admin['id']) ?></td>
            <td><?= htmlspecialchars($admin['username']) ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="admin_id" value="<?= $admin['id'] ?>">
                    <input type="submit" name="delete" value="Delete">
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="admin_id" value="<?= $admin['id'] ?>">
                    <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>">
                    <input type="password" name="password" placeholder="New password (optional)">
                    <input type="submit" name="update" value="Update">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <h2>Add New Admin</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="add" value="Add Admin">
    </form>
</body>
</html>