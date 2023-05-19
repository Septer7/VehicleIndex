<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request; 

class ValueController {
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  public function getAllValues() {
    $query = "SELECT * FROM my_table";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getValueById($id) {
    $query = "SELECT * FROM my_table WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function createValue($type_id, $name, $description) {
    $query = "INSERT INTO my_table (type_id, name, description) VALUES (:type_id, :name, :description)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":type_id", $type_id);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":description", $description);
    $stmt->execute();
    return $this->getValueById($this->db->lastInsertId());
  }

  public function updateValue($id, $type_id, $name, $description) {
    $query = "UPDATE my_table SET type_id = :type_id, name = :name, description = :description WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":type_id", $type_id);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $this->getValueById($id);
  }

  public function deleteValue($id) {
    $query = "DELETE FROM my_table WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return true;
  }
}

?>
