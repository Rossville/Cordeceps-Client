<?php
function registerAdmin($pdo, $username, $password) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
    return $stmt->execute([
        'username' => $username,
        'password' => $hashedPassword
    ]);
    // manage_admins
}

function getPosts($pdo) {
    $stmt = $pdo->query("SELECT posts.*, admins.username AS author 
                         FROM posts 
                         JOIN admins ON posts.author_id = admins.id 
                         ORDER BY posts.created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function deleteAdmin($pdo, $adminId) {
    $stmt = $pdo->prepare("DELETE FROM admins WHERE id = :id");
    return $stmt->execute(['id' => $adminId]);
}

function updateAdmin($pdo, $adminId, $username, $password = null) {
    if ($password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE admins SET username = :username, password = :password WHERE id = :id");
        return $stmt->execute([
            'id' => $adminId,
            'username' => $username,
            'password' => $hashedPassword
        ]);
    } else {
        $stmt = $pdo->prepare("UPDATE admins SET username = :username WHERE id = :id");
        return $stmt->execute([
            'id' => $adminId,
            'username' => $username
        ]);
    }
}