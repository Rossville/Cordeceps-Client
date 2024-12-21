<?php
require '../config/config.php';
require '../src/posts.php';

$posts = getPosts($pdo);
?>
<!DOCTYPE html>
<html>

<head>
    <title>My Blog</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Welcome to My Blog</h1>
    <?php foreach ($posts as $post): ?>
        <article>
            <div class="container">
                <h2><?= htmlspecialchars($post['title']) ?></h2>
                <?php if (!empty($post['image'])): ?>
                    <img src="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/blogApp/public/admin/' . htmlspecialchars($post['image']) ?>" alt="Post Image" class="post-image">
                <?php endif; ?>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small>By <?= htmlspecialchars($post['author']) ?> on <?= $post['created_at'] ?></small>
            </div>
        </article>
    <?php endforeach; ?>
</body>

</html>