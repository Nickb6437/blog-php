<?php 

/**
 * URL
 * 
 * Response Method
 */
class Url {

  /**
   * Redirect to another URL on the same site
   * 
   * @param string $path The Path to redirect to
   * 
   * @return void
   */
  public static function redirect( $path ) {
    
    // Check for protocol beign used
    if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
      $protocol = 'https' ;
    } else {
      $protocol = 'http' ;
    }
    // Redirect to article on submit
    header( "Location: $protocol://" . $_SERVER['HTTP_HOST'] . $path ) ;
    exit ;

  }

}