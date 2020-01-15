<?php

class DBClass
{

  private $host = "db";
  private $username = "devuser";
  private $password = "devpass";
  private $database = "knowledge_city_test";

  public $connection;

  // get the database connection
  public function getConnection()
  {

    $this->connection = null;

    try {
      $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
      $this->connection->exec("set names utf8");
    } catch (PDOException $exception) {
      echo "Error: " . $exception->getMessage();
    }

    return $this->connection;
  }
}
