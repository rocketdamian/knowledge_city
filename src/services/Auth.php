<?php

include_once __DIR__ . "/../entities/User.php";
include_once __DIR__ . "/JWT.php";

class Auth
{

  private $connection;
  private $user;
  private $jwtSecret = '09nwsfng2f4m0lrk93';
  public $userToken;

  public function __construct($connection)
  {
    $this->connection = $connection;
    $this->user = new User($connection);
  }

  public function decodeToken($token)
  {
    return JWT::decode($token, $this->jwtSecret, array('HS256'));
  }

  public function handleAuthToken(String $username, String $password, $remove = false, $set = true)
  {
    $sth = $this->connection->prepare("SELECT * FROM api_users WHERE username = :username");
    $sth->bindParam("username", $username);
    $sth->execute();
    $user = $sth->fetchObject();

    if (empty($user) || !password_verify($password, $user->password)) {
      return false;
    } else {
      $this->user->setUser($user);
    }

    if ($set) {
      if ($remove) {
        $this->userToken = "";
      } else {
        $this->userToken = JWT::encode(['id' => $user->id, 'username' => $user->username, 'password' => $password], $this->jwtSecret);
      }

      $this->user->updateToken($this->userToken);
    } else {
      $this->userToken = $user->token;
    }
  }

  public function removeToken(String $token) {
    $this->user->fetchUserByToken($token);
    $this->user->clearToken();
  }
}
