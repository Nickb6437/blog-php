<?php 

require '../includes/init.php';

Auth::requireLogin();

$article = new Article();

$category_id = [];

$conn = require '../includes/db.php';

$categories = Category::getAll($conn);

if ( $_SERVER[ "REQUEST_METHOD" ] == "POST" ) {

  
  $article->title = $_POST['title'] ;
  $article->content = $_POST['content'] ;
  $article->published = $_POST['published'] ;

  $category_ids = $_POST['category'] ?? [] ;

  if ($article->create($conn)) {

    $article->setCategories($conn, $category_ids);

    Url::redirect("/php_course/blog/admin/article.php?id={$article->id}");

  };

}



?>

<?php require '../includes/header.php'; ?>

<a href="index.php"> Back to Blog</a>

<h2 class="">New Artciles</h2>

<?php require 'includes/article-form.php'; ?>

<?php require '../includes/footer.php'; ?>