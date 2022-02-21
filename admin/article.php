<?php

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';

if ( isset( $_GET['id'] )) {

  $article = Article::getWithCategories( $conn, $_GET['id'] ) ;

} else {

  $article = null;

};

require '../includes/header.php'; 

?>

  <a href="index.php"> Back to Blog</a>

  <!-- checks to see if article -->
  <?php if ( $article ) : ?>

    <!-- if article its rendered to browser -->
    <article>
      <h2><?= htmlspecialchars( $article[0]['title'] ) ?></h2>

      <?php if ( $article[0]['published'] ) : ?>
        <time><?= $article[0]['published'] ?></time>
      <?php else : ?>
        Unpublished
      <?php endif ; ?>

      <?php if ( $article[0]['category_name'] ) : ?>
          <p>Categories:
            <?php foreach ( $article as $a ) : ?>
              <?= htmlspecialchars( $a['category_name'] ) ; ?>
            <?php endforeach ; ?> 
          </p>
        <?php endif ; ?>

      <?php if ( $article[0]['image_file'] ) : ?>

        <img 
          src="uploads/<?= $article[0]['image_file']; ?>" 
          alt="article image"
          width="300"
          height="auto"
        >

      <?php endif ; ?>

      <p><?= htmlspecialchars( $article[0]['content'] ) ; ?></p>
    </article>

    <a href="edit-article.php?id=<?= $article[0]['id']; ?>" class="">Edit</a>
    <a class="delete" href="delete-article.php?id=<?= $article[0]['id']; ; ?>" class="">Delete</a>
    <a href="edit-article-image.php?id=<?= $article[0]['id']; ; ?>" class="">Edit image</a>

  <?php else : ?>

    <p>Article not found!</p>

  <?php endif ; ?>

<?php require '../includes/footer.php'; ?>