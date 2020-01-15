<?php
class User
{

  private $connection;
  private $tableName = "api_users";

  public $id;
  public $username;
  private $password;
  private $token;

  public function __construct($connection)
  {
    $this->connection = $connection;
  }

  public function setUser($user)
  {
    $this->id = intval($user->id);
    $this->username = $user->username;
    $this->password = $user->password;
    $this->token = $user->token;
  }

  public function updateToken($token = null)
  {
    $tokenToUpdate = !is_null($token) ? $token : $this->token;
    $sth = $this->connection->prepare("UPDATE $this->tableName SET token = :token  WHERE id = :id");
    $sth->bindParam("token", $tokenToUpdate);
    $sth->bindParam("id", $this->id);
    $sth->execute();

    $this->token = $token;
  }

  public function fetchUserByToken(String $token)
  {
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

  public function clearToken()
  {
    $this->token = null;
    $this->updateToken();
  }
}
