<?php

class Student
{
  private $connection;

  private $tableName = "students";

  public function __construct(PDO $connection)
  {
    $this->connection = $connection;
  }

  public function getStudents(Int $page, Int $limit)
  {
    $countSql = "SELECT COUNT(*) as COUNT FROM $this->tableName";
    $dataSql = "SELECT * FROM $this->tableName LIMIT :limit OFFSET :offset";

    $offset = ($page - 1) * $limit; //calculate what data you want

    $countQuery = $this->connection->prepare($countSql);
    $dataQuery = $this->connection->prepare($dataSql);
    $dataQuery->bindParam(':limit', $limit, \PDO::PARAM_INT);
    $dataQuery->bindParam(':offset', $offset, \PDO::PARAM_INT);

    $dataQuery->execute();
    $countQuery->execute();
    $count = $countQuery->fetch(PDO::FETCH_ASSOC);
    $num = $count['COUNT'];

    $students = array();
    $students["records"] = array();
    $students["pagination"] = array();

    if ($num > 0) {
      $countData = array(
        "count" => $num,
        "page" => $page,
        "limit" => $limit,
        "totalpages" => ceil($num / $limit)
      );

      $students["records"] = $dataQuery->fetchAll(PDO::FETCH_ASSOC);
      $students["pagination"] = $countData;
    }

    return ($students);
  }
}
