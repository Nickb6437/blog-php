<?php

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';

if ( isset( $_GET['id'] )) {

   $article = Article::getById( $conn, $_GET['id'] ) ;

  if ( ! $article ) {

    die( "No articles found") ;

  };

} else {

  die( "No articles found") ;

};

$category_id = ( array_column( $article->getCategories($conn), 'id' ) );
$categories = Category::getAll($conn);

if ( $_SERVER[ "REQUEST_METHOD" ] == "POST" ) {

  $article->title = $_POST['title'] ;
  $article->content = $_POST['content'] ;
  $article->published = $_POST['published'] ;

  $category_ids = $_POST['category'] ?? [] ;

  if ($article->update($conn)) {

    $article->setCategories($conn, $category_ids);

    Url::redirect("/php_course/blog/admin/article.php?id={$article->id}");

  };

};

?>

<?php require '../includes/header.php'; ?>

<h2 class="">Edit Artciles</h2>


<?php require 'includes/article-form.php'; ?>

<?php require '../includes/footer.php'; ?>
