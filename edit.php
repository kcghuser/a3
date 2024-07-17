<?php
/*******w******** 
    Name: Kwinton Cochrane  
    Date: June 20th
    Description: PHP
****************/

require('connect.php');
require('authenticate.php');

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $id = $_GET['id'];

    if($_POST && !empty($_POST['title']) && !empty($_POST['content'])) {
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $query = "UPDATE blog_posts SET title = :title, content = :content WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        if($statement->execute()) {
            header("Location: post.php?id=$id");
            exit;
        }
    } else {
        $query = "SELECT * FROM blog_posts WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $post = $statement->fetch();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['id'])) {
    if($_POST && !empty($_POST['title']) && !empty($_POST['content'])) {
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $date = date('Y-m-d H:i:s');

        $query = "INSERT INTO blog_posts (title, content, created_at) VALUES (:title, :content, :created_at)";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':created_at', $date);

        if($statement->execute()) {
            header("Location: index.php");
            exit;
        }
    }
} else {
    $post = ['title' => '', 'content' => ''];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title><?= isset($id) ? 'Edit Post' : 'New Post' ?></title>
</head>
<body>
    <h1><?= isset($id) ? 'Edit Post' : 'New Post' ?></h1>
    <form method="post" action="edit.php<?= isset($id) ? '?id=' . $id : '' ?>">
        <label for="title">Title</label>
        <input id="title" name="title" value="<?= $post['title'] ?>">
        <label for="content">Content</label>
        <textarea rows="10" cols="50" id="content" name="content"><?= $post['content'] ?></textarea>
        <input type="submit">
    </form>
    <a id = "newpost" href="index.php">Back to Home</a>
</body>
</html>
