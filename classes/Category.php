<?php
/**
 * Category
 * 
 * Grouping for articles
 */
 class Category {
 
 /**
   * Get all articles
   * 
   * @param object $conn gets connection to database
   * 
   * @return array an associative array of all articles
   */
  public static function getAll($conn) {

    $sql = "SELECT *
    FROM category
    ORDER BY name; ";
  
    //makes query and store in variable  
    $res =  $conn->query( $sql );
  
    //checks for errors and returns result
     return $res->fetchAll(PDO::FETCH_ASSOC);

  }
}