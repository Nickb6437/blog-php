<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta 
  http-equiv="X-UA-Compatible"
  content="IE=edge">
  <meta 
  name="viewport"
  content="width=device-width, initial-scale=1.0">
  <title>CMS learning</title>

  <link 
  rel="stylesheet" 
  href="/php_course/blog/bootstrap/css/bootstrap.min.css">

  <link 
  rel="stylesheet" 
  href="/php_course/blog/css/jquery.datetimepicker.min.css">

  <link 
  rel="stylesheet" 
  href="/php_course/blog/css/style.css">

</head>
<body>
<div class="container">
  <header> 
    <h1>My Blog</h1> 
  </header>

  <nav>
    <ul class="nav">
      <li class="nav-item">
        <a href="/php_course/blog/" class="nav-link">Home</a>
      </li>
  
      <?php if ( Auth::isLoggedIn() ) : ?>
        <li class="nav-item">
          <a href="/php_course/blog/admin/" class="nav-link">Admin</a>
        </li>
        <li class="nav-item">
          <a href="/php_course/blog/logout.php" class="nav-link">Logout</a>
        </li>

      <?php else : ?>

        <li class="nav-item">
          <a href="/php_course/blog/login.php" class="nav-link">Login</a>
        </li>

      <?php endif ; ?>

      <li class="nav-item">
        <a href="/php_course/blog/contact.php" class="nav-link">Contact</a>
      </li>

    </ul>
  </nav>

  <main>