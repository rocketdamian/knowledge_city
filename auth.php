<?php

header('Access-Control-Allow-Origin:*');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "./src/config/database.php";
include_once "./src/services/Auth.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
  case "POST":
    postAuthRequest();
    break;
  case "DELETE":
    deleteAuthRequest();
    break;
}


function postAuthRequest()
{
  $inputData = json_decode(file_get_contents("php://input"));

  if (!isset($inputData->username) || !isset($inputData->password)) {
    http_response_code(400);
    echo json_encode(array("message" => "missing credentials"));
  }

  $dbClass = new DBClass();
  $connection = $dbClass->getConnection();

  $auth = new Auth($connection);
  $auth->handleAuthToken($inputData->username, $inputData->password);

  if ($auth->userToken) {
    echo json_encode(['token' => $auth->userToken]);
  } else {
    http_response_code(400);
    echo json_encode(['error' => true, 'message' => 'These credentials do not match our records.']);
  }
}

function deleteAuthRequest()
{
  $inputData = json_decode(file_get_contents("php://input"));

  if ($inputData->token) {
    $dbClass = new DBClass();
    $connection = $dbClass->getConnection();

    $auth = new Auth($connection);
    $auth->removeToken($inputData->token);
    
    echo json_encode(['token' => ""]);
  }
}
