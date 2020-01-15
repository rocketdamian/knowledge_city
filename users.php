<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers:Origin, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Headers:Origin, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
  header("HTTP/1.1 200 OK");
  die();
}


include_once "./src/config/database.php";
include_once "./src/entities/Student.php";
include_once "./src/services/PrivateResource.php";

$dbClass = new DBClass();
$connection = $dbClass->getConnection();

$privateResourceService = new PrivateResource($connection);
$privateResourceService->doAuth();

$input = $_GET;

$page = (isset($input['page']) && $input['page'] > 0) ? $input['page'] : 1;
$limit = isset($input['limit']) ? $input['limit'] : 5;

$student = new Student($connection);
$students = $student->getStudents($page, $limit);

echo json_encode($students);
