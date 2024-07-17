<?php
/*******w******** 
    Name: Kwinton Cochrane  
    Date: June 20th
    Description: PHP
****************/

require('connect.php');

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $id = $_GET['id'];

    $query = "SELECT * FROM blog_posts WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $post = $statement->fetch();
} else {
    header("Location: index.php");
    exit;
}

function formatDate($date) {
    return date("F j, Y, g:i a", strtotime($date));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title><?= $post['title'] ?></title>
</head>
<body>
    <h1><?= $post['title'] ?></h1>
    <p><?= formatDate($post['created_at']) ?></p>
    <p><?= $post['content'] ?></p>
    <a href="edit.php?id=<?= $post['id'] ?>">Edit</a>
    <a href="index.php">Back to Home</a>
</body>
</html>
