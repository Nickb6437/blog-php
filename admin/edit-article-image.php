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

if ( $_SERVER[ "REQUEST_METHOD" ] == "POST" ) {

  try {

    if (empty($_FILES)) {
      throw new Exception('Invalid upload');
    }
    
    switch ($_FILES['image']['error']) {
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new Exception('No File Uploaded');
        break;
      case UPLOAD_ERR_INI_SIZE:
        throw new Exception('File is too large (2mb)');
        break;
      default:
        throw new Exception('Opps there was an error!');
    } 
    
    if ( $_FILES['image']['size'] > 1000000 ) {
      throw new Exception('File is too large');
    }

    // checking file type
    $mime_types = ['image/gif', 'image/png', 'image/jpg', 'image/jpeg'];

    $finfo = finfo_open( FILEINFO_MIME_TYPE );
    $mime_type = finfo_file( $finfo, $_FILES['image']['tmp_name'] );

    if ( ! in_array( $mime_type, $mime_types ) ) {
      throw new Exception('Invalid file type!');
    }

    // move file
    $pathinfo = pathinfo( $_FILES['image']['name'] );

    $base = $pathinfo['filename'];

    $base = preg_replace( '/[^a-zA-Z0-9_-]/', '_', $base );

    $base = mb_substr( $base, 0, 200 );

    $filename = $base . '.' . $pathinfo['extension'];

    $destination = "../uploads/$filename";

    $i = 1 ;

    while ( file_exists( $destination ) ) {

      $filename = $base . "-$i." . $pathinfo['extension'];
      $destination = "../uploads/$filename";

      $i++;

    }

    if ( move_uploaded_file( $_FILES['image']['tmp_name'], $destination ) ) {

      $previous_image = $article->image_file;
      
      if ( $article->setImageFile( $conn, $filename ) ) {

        if ( $previous_image ) {
          unlink( "../uploads/$previous_image" );
        }
        
        Url::redirect( "/php_course/blog/admin/edit-article-image.php?id={$article->id}" );
        
      }

    } else {

      throw new Exception('upload failed');

    }

  } catch (Exception $e) {
    $err = $e->getMessage();
  }

};

?>

<?php require '../includes/header.php'; ?>

<h2 class="">Edit article images</h2>

<?php if ( $article->image_file ) : ?>

  <img 
    src="../uploads/<?= $article->image_file; ?>" 
    alt="article image"
    width="300"
    height="auto"
  >
  <a class='delete' href="/php_course/blog/admin/delete-article-image.php?id=<?= $article->id ?>">Remove Image</a>

<?php endif ; ?>

<?php if ( isset( $err ) ) : ?>
  <p><?= $err ?></p>
<?php endif ; ?>

<form method='post' enctype='multipart/form-data'>

  <div class="">
    <label for="image">Image File</label>
    <input id="image" name="image" type="file">
  </div>

  <button>Upload</button>

</form>

<?php require '../includes/footer.php'; ?>
