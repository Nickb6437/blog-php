<?php 

require 'includes/init.php';

Auth::logout();

Url::redirect( '/php_course/blog' ) ;