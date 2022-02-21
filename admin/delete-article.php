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

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
  
  if ($article->delete($conn)) {

    Url::redirect( "/php_course/blog/admin/" ) ;

  }

};

?>

// now all handled by js

<!-- <?php require '../includes/header.php' ; ?>

<h2>Delete Article</h2>

<p class="">Are you sure?</p>

<form method="post">
  <button>Delete</button>
</form>
<a href="article.php?id=<?= $article->id ; ?>" class="">Cancel</a>

<?php require '../includes/footer.php' ; ?> -->