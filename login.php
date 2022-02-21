<?php 

require 'includes/init.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

  $conn = require 'includes/db.php';

  if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {

    Auth::login();

    Url::redirect( '/php_course/blog' ) ;

  } else {

    $err = 'Login Invalid' ;

  }
}

?>

<?php require 'includes/header.php' ; ?>

<h2>Login</h2>

<?php if ( !empty( $err ) ) : ?>
  <p><?= $err ?></p>
<?php endif ; ?>

<form method="POST" action="">
  
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" class="form-control">
  </div>

  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" name="password" id="password" class="form-control">
  </div>
  
  <button>login</button>

</form>

<?php require 'includes/footer.php' ; ?>

