<?php
require '../../config/config.php';

session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image = $_FILES['image'];

    if (empty($title) || empty($content)) {
        $error = 'Title and content are required!';
    } else {
        if (!isset($_SESSION['admin_id'])) {
            $error = 'You must be logged in to create a post.';
        } else {
            $admin_id = $_SESSION['admin_id'];

            if ($image['error'] === UPLOAD_ERR_OK) {
                $uploadDir = './uploads/';
                $imageExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
                $imageName = 'post_' . uniqid() . '.' . $imageExtension;
                $uploadFilePath = $uploadDir . $imageName;

                if (move_uploaded_file($image['tmp_name'], $uploadFilePath)) {
                    $imagePath = 'uploads/' . $imageName;

                    try {
                        $stmt = $pdo->prepare("INSERT INTO posts (title, content, admin_id, image) 
                                               VALUES (:title, :content, :admin_id, :image)");

                        $stmt->execute([
                            'title' => $title,
                            'content' => $content,
                            'admin_id' => $admin_id,
                            'image' => $imagePath,
                        ]);

                        $success = 'Post created successfully!';
                        $title = '';
                        $content = '';
                    } catch (PDOException $e) {
                        error_log("Database error: " . $e->getMessage());
                        $error = 'Error creating post. Please try again later.';
                    }
                } else {
                    $error = 'Failed to upload image.';
                }
            } else {
                $error = 'Please select an image file to upload.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Post</title>
    <link rel="stylesheet" href="createPost.style.css">
</head>

<body>
    <div class="container">
        <h1>Create a New Post</h1>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <label for="title">Post Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($title ?? '') ?>" required>

            <label for="content">Content:</label>
            <textarea name="content" rows="5" required><?= htmlspecialchars($content ?? '') ?></textarea>

            <label for="image">Upload Image (Optional):</label>
            <input type="file" name="image" accept="image/jpeg, image/png, image/jpg">

            <input type="submit" value="Create Post">
        </form>

        <div class="back-link">
            <p><a href="dashboard.php">Back to Dashboard</a></p>
        </div>
    </div>

    <?php if (!empty($success)): ?>
    <div class="modal" id="successModal">
        <div class="modal-content">
            <h2><?= htmlspecialchars($success) ?></h2>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>
    <?php endif; ?>

    <script>
        <?php if (!empty($success)): ?>
        document.getElementById('successModal').style.display = 'flex';
        <?php endif; ?>

        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
        }
    </script>
</body>

</html>
