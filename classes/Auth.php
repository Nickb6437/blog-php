<?php 

/**
 * Authenticate user is logged in status
 * 
 * @return boolean True if logged in, false otherwise
 */
class Auth {

  /**
   * Return the user Authentication status 
   * 
   * @return boolean True of a user is logged in, false otherwise
   */
  public static function isLoggedIn() {
    return isset( $_SESSION['is_logged_in'] ) && $_SESSION['is_logged_in'] ;
  }

  /**
   * Rewuire the user to be logged in, stopping with an unathorised message if not
   * 
   * @return void
   */
  public static function requireLogin() {

    if (! static::isLoggedIn()) {

      die('unathorised');

    }

  }

  /**
   * Log in using session
   * 
   * @return void
   */
  public static function login() {

    session_regenerate_id(true);

    $_SESSION['is_logged_in'] = true;

  }

  /**
   * Logout session
   * 
   * @return void
   */
  public static function logout() {
    
    $_SESSION = [] ;

    if ( ini_get( 'session.use_cokies' ) ) {
      $params = session_get_cookie_params();
      setcookie( session_name(), '', time() - 42000, 
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
      ) ;
      
    }

    session_destroy();

  }
}