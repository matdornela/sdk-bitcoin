<?php

/**
 * Handling database connection
 */
class DbConnect {

    private $conn;

    function __construct() {        
    }

    /**
     * Establishing database connection
     * @return database connection handler
     */
    function connect() {
        include_once dirname(__FILE__) . '/Config.php';

        // Connecting to mysql database
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // check connection
        if ($this->conn->connect_error) {
          trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
      }

        // returing connection resource
      return $this->conn;
  }

}

?>
