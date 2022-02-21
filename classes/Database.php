<?php 

/**
 * Database 
 * 
 * Connection to the database
 */
class Database {

  /**
   * Hostname
   * @var string
   */
  protected $db_host ;

  /**
   * Database Name
   * @var string
   */
  protected $db_name ;

  /**
   * Username
   * @var string
   */
  protected $db_user ;

  /**
   * Password
   * @var string
   */
  protected $db_pass ;

  /**
   * Constructor
   * 
   * @param string $host Hostname
   * @param string $name Database Name
   * @param string $user Username
   * @param string $password Database password
   * 
   * @return void
   */
  public function __construct( $host, $name, $user, $password ) {

    $this->db_host = $host ;
    $this->db_name = $name ;
    $this->db_user = $user ;
    $this->db_pass = $password ;

  }

  /**
   * Get the database connection
   * 
   * @return PDO new database connsection object 
   */

  public function getConn() {

    $dsn = 'mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . ';charset=utf8';

    try {

      $db = new PDO ($dsn, $this->db_user, $this->db_pass);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $db;

    } catch (PDOException $e) {

      echo $e->getMessage();
      exit;

    }
    
  }

}