<?php

use \Firebase\JWT\JWT;

class User
{
  private $connection;

  private $tableName = "api_users";

  public $id;
  public $username;
  private $password;
  private $token;

  public function __construct(PDO $connection)
  {
    $this->connection = $connection;
  }

  public function __get($name)
  {
    return $this->$name;
  }

  private function setUser($user) {
    $this->id = $user->id;
    $this->username = $user->username;
    $this->password = $user->password;
    $this->token = $user->token;
  }

  public function handleAuthToken(String $username, String $password, $remove = false, $set = true)
  {
    // this is not secure but seed users are not hashed
    $sth = $this->connection->prepare("SELECT * FROM $this->tableName WHERE username = :username AND password = :password");
    $sth->bindParam("username", $username);
    $sth->bindParam("password", $password);
    $sth->execute();
    $user = $sth->fetchObject();

    if (!$user) {
      return false;
    } else {
      $this->setUser($user);
    }


    if ($set) {
      if ($remove) {
        $this->token = "";
      } else {
        $this->token = JWT::encode(['id' => $this->id, 'username' => $this->username, 'password' => $this->password], $_ENV["JWT_SECRET"], "HS256");
      }

      $this->updateToken();
    }
  }

  public function fetchUserByToken(String $token) {
    $sth = $this->connection->prepare("SELECT * FROM $this->tableName WHERE token = :token");
    $sth->bindParam("token", $token);

    $sth->execute();
    $user = $sth->fetchObject();

    if (!$user) {
      return false;
    } else {
      $this->setUser($user);
    }
  }

  public function clearToken() {
    $this->token = null;
    $this->updateToken();
  }

  private function updateToken()
  {
    $sth = $this->connection->prepare("UPDATE $this->tableName SET token = :token  WHERE id = :id");
    $sth->bindParam("token", $this->token);
    $sth->bindParam("id", $this->id);
    $sth->execute();
  }
}
