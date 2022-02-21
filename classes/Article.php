<?php 
/**
 * Article
 * 
 * Published Article for the blogs
 */
class Article {

  /**
   * Article Id
   * @var Interger
   */
  public $id;

  /**
   * Article Title
   * @var String
   */
  public $title;

  /**
   * Article Content
   * @var String
   */
  public $content;

  /**
   * Article published date & time
   * @var datetime
   */
  public $published;

  /**
   * Path to the image
   * @var string
   */
  public $image_file;

  /**
   * Validation errors
   * @var array
   */
  public $val_errs = [] ;

  /**
   * Get all articles
   * 
   * @param object $conn gets connection to database
   * 
   * @return array an associative array of all articles
   */
  public static function getAll($conn) {

    // Query args
    $sql = "SELECT *
    FROM article
    -- used to test error function
    -- WHERE id = 0
    ORDER BY published; ";
  
    //makes query and store in variable  
    $res =  $conn->query( $sql );
  
    //checks for errors and returns result
     return $res->fetchAll(PDO::FETCH_ASSOC);

  }
  
  /**
   * Get articles by page
   * 
   * @param object $conn gets connection to database
   * @param integer $limit number of articles to return
   * @param integer $offset number of articles to skip
   * 
   * @return array an associative array of all articles
   */
  public static function getPage($conn, $limit, $offset, $only_published = false) {
    
    $condition = $only_published ? ' WHERE published IS NOT NULL' : '' ;

    $sql = "SELECT a.*, category.name AS category_name
    FROM (SELECT *
    FROM article
    $condition
    ORDER BY published
    LIMIT :limit
    OFFSET :offset) AS a
    LEFT JOIN article_category
    ON a.id = article_category.article_id
    LEFT JOIN category
    ON article_category.category_id = category.id";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $articles = [] ;

    $prev_id = null ;

    foreach ( $res as $row ) {

      $article_id = $row['id'];

      if ( $article_id != $prev_id ) {

        $row['category_names'] = [] ;

        $articles[$article_id] = $row;
 
      }

      $articles[$article_id]['category_names'][] = $row['category_name'] ;

      $prev_id = $article_id ;

    } 

    return $articles ;

  }

  /**
   * Get the article record based on the ID
   * 
   * @param object $conn Connection to the database
   * @param interger $id The article ID
   * @param string $columns Optional list of column for select, defaults to *
   * 
   * @return mixed An object of this class, or NULL if not found.
   */
    public static function getById( $conn, $id, $columns = '*' ) {

      $sql = "SELECT $columns
      FROM article
      WHERE id = :id" ;

      $stmt = $conn->prepare( $sql ) ;

      $stmt->bindValue( ':id', $id, PDO::PARAM_INT ) ;

      $stmt->setFetchMode(PDO::FETCH_CLASS, 'Article');

      if (  $stmt->execute() ) {

        return $stmt->fetch();

      }

  }

 /**
   * Get the article record based on the ID along with category
   * 
   * @param object $conn Connection to the database
   * @param interger $id The article ID
   * 
   * @return array The article data with category.
   */
   public static function getWithCategories( $conn, $id, $only_published = false) {

    $sql = "SELECT article.*, category.name AS category_name
      FROM article
      LEFT JOIN article_category
      on article.id = article_category.article_id
      LEFT JOIN category
      ON article_category.category_id = category.id
      WHERE article.id = :id";

    if ($only_published) {
      $sql .= ' AND article.published IS NOT NULL';
    }

    $stmt = $conn->prepare( $sql ) ;

    $stmt->bindValue( ':id', $id, PDO::PARAM_INT ) ;

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
     
   }

  /**
  * Get the article's categories
  *
  *@param object $connn Connection to the database
  *
  *@return array The Category data
  */
   public function getCategories( $conn ) {
      $sql = "SELECT category.*
      FROM category
      JOIN article_category
      on category.id = article_category.category_id
      WHERE article_id = :id";

      $stmt = $conn->prepare( $sql ) ;

      $stmt->bindValue( ':id', $this->id, PDO::PARAM_INT ) ;

      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

  /**
   * Update the article with its current property values
   * 
   * @param object $conn Connection to database
   * 
   * @return boolean True if the update was sucessful, false otherwise
   */
  public function update($conn) {

    if ($this->validate()) {

      $sql = "UPDATE article
        SET title = :title,
            content = :content,
            published = :published
        WHERE id = :id" ;

      $stmt = $conn->prepare($sql);

      $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
      $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
      $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);

      if ($this->published == '') {
        $stmt->bindValue(':published', null, PDO::PARAM_NULL);
      } else {
        $stmt->bindValue(':published', $this->published, PDO::PARAM_STR);
      }

      return $stmt->execute();

    } else {

      return false;

    }

  }

  /**
   * Set the article categories
   * 
   * @param object $conn Database Connection
   * @param array $ids Category Ids
   * 
   * @return void
   */
  public function setCategories( $conn, $ids ) {

    if ($ids) {

      $sql = "INSERT IGNORE INTO article_category (article_id, category_id)
        VALUES ";

      $values = [] ;

      foreach ( $ids as $id ) {
          $values[] = "({$this->id}, ?)" ;
      }

      $sql .= implode( ", ", $values ) ;

      $stmt = $conn->prepare( $sql ) ;

      foreach ( $ids as $i => $id ) {

        $stmt->bindValue( $i + 1, $id, PDO::PARAM_INT);
      
      }

      $stmt->execute();

    }

    $sql ="DELETE FROM article_category
      WHERE article_id = {$this->id}" ;

    if ( $ids ) {

      $placeholders = array_fill( 0, count( $ids ), '?' ) ;

      $sql .= " AND category_id NOT IN (" . implode( ", ", $placeholders ) . ")" ;

    }

    var_dump($sql);

    $stmt = $conn->prepare( $sql ) ;

    foreach ( $ids as $i => $id ) {

      $stmt->bindValue( $i + 1, $id, PDO::PARAM_INT);
    
    }

    $stmt->execute() ;
  }

  /**
 * Validate the article properties 
 * 
 * @return boolean True if the current properties are valid, false otherwise
 */
  protected function validate() {

    if ( $this->title == '' ) {
      $this->val_errs[] = 'Please enter a title';
    }

    if ( $this->content == '' ) {
      $this->val_errs[] = 'Please provide some content';
    }

    if ( $this->published != '' ) {
      // makes sure entry follows a set format pattern
      $date_time = date_create_from_format( 'Y-m-d H:i:s', $this->published ) ; 

        if ( $date_time === false ) 
        {
          $this->val_errs[] = 'Invalid Format, please follow';
          
        } else {
          //beacuse the above validation wont check if a date is valid run the following error function.
          $date_error = date_get_last_errors() ;

            //if an error is returned add a validation error to array.
            if ( $date_error['warning_count'] > 0 ) {
              $this->val_errs[] = 'Invalid date or time, please check and try again';
            }
        }
    }
    
    return empty($this->val_errs) ;
  }

  /**
   * Delete the current article
   * 
   * @param onject $conn Connection to the database
   * 
   * @return boolean True if the delete was successful, false otherwise
   */
  public function delete($conn) {

    $sql = "DELETE FROM article
      WHERE id = :id" ;

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

    return $stmt->execute();

  }

  /**
   * Update the article with its current property values
   * 
   * @param object $conn Connection to database
   * 
   * @return boolean True if the insert was successful, false otherwise
   */
  public function create($conn) {

    if ($this->validate()) {

      $sql = "INSERT article (title, content, published)
        VALUES (:title, :content, :published)";

      $stmt = $conn->prepare($sql);

      $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
      $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);

      if ($this->published == '') {
        $stmt->bindValue(':published', null, PDO::PARAM_NULL);
      } else {
        $stmt->bindValue(':published', $this->published, PDO::PARAM_STR);
      }

      if ($stmt->execute()) {
        $this->id = $conn->lastInsertId();
        return true;
      };

    } else {

      return false;

    }

  }

  /**
   * Get a count of teh total number of records
   * 
   * @param object $conn Connection to the database
   * 
   * @return integer The total number of records
   */
  public static function getTotal($conn, $only_published = false){

    $condition = $only_published ? ' WHERE published IS NOT NULL' : '' ;

    return $conn->query("SELECT COUNT(*) FROM article$condition")->fetchColumn();

  } 

  /**
   * Update image file property
   * 
   * @param object $conn Connection to the database
   * @param string $filename The filename of the image
   * 
   * @return boolean True if it was successful, false otherwise
   */
  public function setImageFile($conn, $filename) {

    $sql = "UPDATE article
    SET image_file = :image_file
    WHERE id = :id" ;

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':image_file', $filename, $filename == null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

    return $stmt->execute();

  } 


  /**
   * Publish the article, setting the published field to current time and date
   * #@param object $conn Connection to the database
   * 
   * @return mixed The published date and time if successful, null otherwise
   */
  public function publish($conn) {
    
    $sql = "UPDATE article
      SET published = :published
      WHERE id = :id";

      $stmt = $conn->prepare($sql);

      $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

      $published = date("Y-m-d H:i:s");
      $stmt->bindValue(':published', $published, PDO::PARAM_STR);

      if ( $stmt->execute() ) {
        return $published;
      }
  }
}