<?php 

/**
 * User
 * 
 * A login to the site
 */
class User {

  /**
   * Unique identifier
   * @var integer
   */
  public $id;

  /**
   * Unique username
   * @var string
   */
  public $username;

  /**
   * Password
   * @var string
   */
  public $password;

  /**
   * Authenticate the users credientials 
   * 
   * @param object $conn Connection form Database
   * @param string $username Username
   * @param string $password Password
   * 
   * @return boolean True if the credentials are correct, null otherwise
   */
  public static function authenticate($conn, $username, $password) {

    $sql = "SELECT *
      FROM user
      WHERE username = :username";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);

    $stmt->setFetchMode(PDO::FETCH_CLASS, 'user');

    $stmt->execute();

    if ($user = $stmt->fetch()) {

      return password_verify($password, $user->password);

    }

  }

}