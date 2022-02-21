<?php

require 'includes/init.php';

$conn = require 'includes/db.php';

$paginator = new Paginator($_GET['page'] ?? 1 , 4, Article::getTotal($conn, true));

$articles = Article::getPage( $conn, $paginator->limit, $paginator->offset, true); 

?>

<?php require 'includes/header.php'; ?>

<main>

  <!-- checks to see if articles -->
  <?php if ( empty( $articles ) ) : ?>
    <p>No articles found!</p>
  <?php else : ?>

    <!-- if articles this code is run -->
    <ul class="list-reset">
      <!-- loops all articles and displays in browser -->
      <?php foreach ( $articles as $article ) : ?>
        <li>
          <article>
            <a href="article.php?id=<?= $article['id'] ; ?>">
              <h2><?= htmlspecialchars( $article['title'] ) ; ?></h2>
            </a>

            <time datetime="<?= $article['published'] ?>">
              <?php
              $dateTime = new DateTime($article['published']) ;
              echo $dateTime->format('j F, Y');
              ?>
            </time>

            <?php if ( $article['category_names'] ) : ?>
              <p>Categories: 
                <?php foreach ( $article['category_names'] as $name ) : ?>
                  <?= htmlspecialchars( $name ) ; ?>
                <?php endforeach ; ?>
              </p>
            <?php endif ; ?>

            <p><?= htmlspecialchars( $article['content'] ) ; ?></p>
          </article>
        </li>
        <?php endforeach ; ?>
    </ul>

    <?php require 'includes/pagination.php'; ?>

  <?php endif ; ?>

  <?php require 'includes/footer.php'; ?>