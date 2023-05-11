<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TypeController {
    // Database connection
    private $db;
  
    // Constructor with DB
    function __construct($db) {
      $this->db = $db;
    }
  
    // Get all types
    public function getAll() {
      $sql = "SELECT * FROM type";
      $result = $this->db->query($sql);
      return $result->fetchAll(PDO::FETCH_ASSOC);
    }
  
    // Get type by id
    public function getById($id) {
      $sql = "SELECT * FROM type WHERE id = :id";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':id', $id);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }
  
    // Add new type
    public function add($name) {
      $sql = "INSERT INTO type(name) VALUES(:name)";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':name', $name);
      return $stmt->execute();
    }
  
    // Update type
    public function update($id, $name) {
      $sql = "UPDATE type SET name = :name WHERE id = :id";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':id', $id);
      $stmt->bindParam(':name', $name);
      return $stmt->execute();
    }
  
    // Delete type by id
    public function delete($id) {
      $sql = "DELETE FROM type WHERE id = :id";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':id', $id);
      return $stmt->execute();
    }
  }
  