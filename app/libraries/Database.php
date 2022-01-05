<?php
  /*
   * PDO Database class
   * Connent, create, prepare statements
   * Bind Values , retutn rows and results
   */
  class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
      // Set DSN
      $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
      $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      );
    
      // Create PDO instance
      try {
        $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
      } catch(PDOException $e) {
        $this->error = $e->getMessage();
        echo $this->error;
      }
    }

    // Prepare statement with query
    public function query($sql) {
      $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null) {
      if(is_null($type)) {
        switch(true) {
          case is_int($value) :
            $type = PDO::PARAM_INT;
            break;
          case is_bool($value) :
            $type = PDO::PARAM_BOOL;
            break;
          case is_null($value) :
            $type = PDO::PARAM_NULL;
            break;
          default :
            $type = PDO::PARAM_STR;
            break;
        }
      }

      $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
      return $this->stmt->execute();
    }

    // Result sets , object or assoc array

    public function resultset_all_obj() {
      $this->execute();
      return $this->stmt->fetchAll(PDO::FETCH_OBJ); 
    }

    public function resultset_all_asoc() {
      $this->execute();
      return $this->stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function resultset_single_obj() {
      $this->execute();
      return $this->stmt->fetch(PDO::FETCH_OBJ); 
    }

    public function resultset_single_assoc() {
      $this->execute();
      return $this->stmt->fetch(PDO::FETCH_ASSOC); 
    }
    
    // Get row count
    public function row_count() {
      return $this->stmt->rowCount();
    }

  }

