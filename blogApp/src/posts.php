<?php
function createPost($pdo, $title, $content, $authorId) {
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, author_id) VALUES (:title, :content, :author_id)");
    return $stmt->execute([
        'title' => $title,
        'content' => $content,
        'author_id' => $authorId
    ]);
}

function getPosts($pdo) {
    $stmt = $pdo->query("SELECT posts.*, admins.username AS author, posts.image
                         FROM posts 
                         JOIN admins ON posts.admin_id = admins.id 
                         ORDER BY posts.created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function updatePost($pdo, $id, $title, $content) {
    $stmt = $pdo->prepare("UPDATE posts SET title = :title, content = :content, updated_at = NOW() WHERE id = :id");
    return $stmt->execute([
        'id' => $id,
        'title' => $title,
        'content' => $content
    ]);
}

function deletePost($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}