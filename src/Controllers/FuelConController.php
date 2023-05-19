<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FuelConController
{
    private $db;

    public function __construct($db) {
      $this->db = $db;
    }
  
    // get a fuel consumption record by ID
    public function getById($id) {
      $sql = "SELECT * FROM fuel_consumption WHERE id = ?";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([$id]);
      $row = $stmt->fetch();
  
      if (!$row) {
        return null;
      }
  
      return new FuelConsumption(
        $row['id'],
        $row['vehicle_id'],
        $row['fuel_type_id'],
        $row['fuel_quantity'],
        $row['distance_travelled'],
        $row['created_at']
      );
    }
  
    // create a new fuel consumption record
    public function create($vehicle_id, $fuel_type_id, $fuel_quantity, $distance_travelled) {
      $sql = "INSERT INTO fuel_consumption (vehicle_id, fuel_type_id, fuel_quantity, distance_travelled) VALUES (?, ?, ?, ?)";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([$vehicle_id, $fuel_type_id, $fuel_quantity, $distance_travelled]);
  
      // return the ID of the newly created record
      return $this->db->lastInsertId();
    }
  
    // update an existing fuel consumption record
    public function update($id, $vehicle_id, $fuel_type_id, $fuel_quantity, $distance_travelled) {

    }
}