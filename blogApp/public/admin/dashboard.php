<?php
require '../../config/config.php';
require '../../src/posts.php';
require 'navbar.php';

session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ./login.php');
    exit;
}

$adminName = $_SESSION['username'];
$posts = getPosts($pdo);

?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <div class="container">
        <!-- cordyceps navbar -->
        <div class="navbar">
            <div>
                Cordyceps
            </div>
            <div class="navbar-btn">
                <div>
                    <span>Create Post</span>
                </div>
                <div>
                    <span>Logout</span>
                </div>
            </div>
        </div>
        <h1>Welcome, <?= htmlspecialchars($adminName) ?></h1>
        <h2>Posts</h2>
        <a href="create_post.php" class="create-post">Create Post</a>
        <table>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= htmlspecialchars($post['title']) ?></td>
                    <td><?= htmlspecialchars($post['author']) ?></td>
                    <td><?= $post['created_at'] ?></td>
                    <td class="actions">
                        <a href="edit_post.php?id=<?= $post['id'] ?>">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                        <a href="delete_post.php?id=<?= $post['id'] ?>">
                            <span class="material-symbols-outlined">delete</span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>