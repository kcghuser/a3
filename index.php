<?php
/*******w******** 
    Name: Kwinton Cochrane  
    Date: June 20th
    Description: PHP
****************/

require('connect.php');

function formatDate($date) {
    return date("F j, Y, g:i a", strtotime($date));
}

$query = "SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 5";
$statement = $db->prepare($query);
$statement->execute();
$posts = $statement->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require('authenticate.php');
    if (isset($_POST['delete_id']) && filter_var($_POST['delete_id'], FILTER_VALIDATE_INT)) {
        $deleteId = $_POST['delete_id'];
        $query = "DELETE FROM blog_posts WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $deleteId, PDO::PARAM_INT);
        if ($statement->execute()) {
            header("Location: index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Welcome to my Blog!</title>
</head>
<body>
    <h1>My Blog</h1>
    <?php if(!empty($posts)): ?>
        <?php foreach($posts as $post): ?>
            <div class="post">
                <h2><a href="post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a></h2>
                <p><?= formatDate($post['created_at']) ?></p>
                <p>
                    <?= substr($post['content'], 0, 200) ?>
                    <?php if(strlen($post['content']) > 200): ?>
                        ... <a href="post.php?id=<?= $post['id'] ?>">Read Full Post</a>
                    <?php endif; ?>
                </p>
                <a href="edit.php?id=<?= $post['id'] ?>">Edit</a>
                <form method="post" action="index.php">
                    <input type="hidden" name="delete_id" value="<?= $post['id'] ?>">
                    <input type="submit" value="Delete">
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No blog posts available.</p>
    <?php endif; ?>
    <a id="newpost" href="edit.php">New Post</a>
</body>
</html>
