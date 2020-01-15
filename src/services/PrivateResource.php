<?php

include_once __DIR__ . "/../services/Auth.php";
include_once __DIR__ . "/../utils/getHeaderToken.php";

class PrivateResource
{
  public $token;
  private $connection;
  private $authService;

  public function __construct($connection)
  {
    $this->connection = $connection;
    $this->authService = new Auth($this->connection);
  }

  public function doAuth()
  {
    $token = getBearerToken();
    try {
      $decodedToken = $this->authService->decodeToken($token);
    } catch (Exception $exception) {
      $this->halt(400, $exception->getMessage());
    }

    $this->authService->handleAuthToken($decodedToken->username, $decodedToken->password, false, false);

    if ($token !== $this->authService->userToken) {
      $this->halt(401, "unauthorized");
    }
  }

  private function halt(Int $code, String $message)
  {
    http_response_code($code);
    echo json_encode(['error' => true, 'message' => $message]);
    die();
  }
}
