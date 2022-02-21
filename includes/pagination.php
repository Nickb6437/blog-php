<?php $base = strtok($_SERVER['REQUEST_URI'], '?' ); ?>

<nav>
  <ul class=pagination>
    <?php if ($paginator->previous) : ?>
      <li class="page-item">
        <a 
        href="<?= $base; ?>?page=<?= $paginator->previous; ?>"
        class="page-link"
        >
          Previous
        </a>
      </li>
    <?php endif ; ?>
    <?php if ($paginator->next) : ?>
      <li class="page-item">
        <a 
        href="<?= $base; ?>?page=<?= $paginator->next; ?>" 
        class="page-link"
        >
          Next
        </a>
      </li>
    <?php endif ; ?>
  </ul>
</nav>