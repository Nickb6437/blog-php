<?php

require 'includes/init.php';

$conn = require 'includes/db.php';

if ( isset( $_GET['id'] )) {

  // $article = Article::getById( $conn, $_GET['id'] ) ;
  $article = Article::getWithCategories( $conn, $_GET['id'], true) ;

} else {

  $article = null;

};

require 'includes/header.php'; 

?>

  <a href="index.php"> Back to Blog</a>

  <!-- checks to see if article -->
  <?php if ( $article ) : ?>

    <!-- if article its rendered to browser -->
    <article>
      <h2><?= htmlspecialchars( $article[0]['title'] ) ?></h2>

      <time datetime="<?= $article[0]['published'] ?>">
        <?php
        $dateTime = new DateTime($article[0]['published']) ;
        echo $dateTime->format('j F, Y');
        ?>
      </time>

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
    
  <?php else : ?>

    <p>Article not found!</p>

  <?php endif ; ?>

<?php require 'includes/footer.php'; ?>