<?php if ( !empty( $article->val_errs ) ) : ?>
  <ul>
    <?php foreach ( $article->val_errs as $err ) : ?>
      <li><?= $err ?></li>
    <?php endforeach ?>
  </ul>
<?php endif ; ?>

<form method="post" id="articleForm">

  <div class="form-group">
    <label for="title">Title</label>
    <input 
    name="title" 
    id="title" 
    placholder="Article title" 
    value="<?= htmlspecialchars( $article->title ) ; ?>"
    class="form-control"
    >
  </div> 

  <div class="form-group">
    <label for="content">Content</label>
    <textarea 
    name="content" 
    id="content" 
    cols="30" 
    rows="10" 
    placholder="Article content"
    class="form-control"
    >
      <?= htmlspecialchars( $article->content ) ; ?>
    </textarea>
  </div>

  <div class="form-group">
    <label for="published">Publication time and date</label>
    <input 
    name="published" 
    id="published" 
    value="<?= $article->published ?>"
    class="form-control"
    >
  </div>

  <fieldset>

    <legend>Categories</legend>
    
    <?php foreach ( $categories as $category ) : ?>
      <div class="form-check">
        <input
          class="form-check-input"
          id="category<?= $category['id'] ?>" 
          type="checkbox" 
          name="category[]" 
          value="<?= $category['id'] ?>"
          <?php if ( in_array( $category['id'], $category_id ) ) : ?>
            checked
          <?php endif ; ?>
        >
        <label 
        class="form-check-label"
        for="category<?= $category['id'] ?>"
        >
          <?= htmlspecialchars( $category['name'] ) ?>
        </label>
      </div>
    <?php endforeach ; ?>

  </fieldset>

  <button>Save</button>

</form>