<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ElectricityConsumptionController {
    private $db;
 
    public function __construct($db) {
       $this->db = $db;
    }
 
    public function getAll() {
      
       $query = "SELECT * FROM electricity_consumption";
       $result = $this->db->query($query);
 
       $electricityConsumptions = array();
       while ($row = $result->fetch_assoc()) {
          $electricityConsumption = new ElectricityConsumption($row["id"], $row["datetime"], $row["consumption"]);
          array_push($electricityConsumptions, $electricityConsumption);
       }
 
       return $electricityConsumptions;
    }
 
    public function getById($id) {
       

       $query = "SELECT * FROM electricity_consumption WHERE id = $id";
       $result = $this->db->query($query);
 
       if ($result->num_rows == 0) {
          return null;
       } else {
          $row = $result->fetch_assoc();
          $electricityConsumption = new ElectricityConsumption($row["id"], $row["datetime"], $row["consumption"]);
          return $electricityConsumption;
       }
    }
 
    public function add($datetime, $consumption) {
       $query = "INSERT INTO electricity_consumption (datetime, consumption) VALUES ('$datetime', '$consumption')";
       $result = $this->db->query($query);
 
       if ($result) {
          $id = $this->db->insert_id;
          $electricityConsumption = new ElectricityConsumption($id, $datetime, $consumption);
          return $electricityConsumption;
       } else {
          return null;
       }
    }
 
    public function update($id, $datetime, $consumption) {
       $query = "UPDATE electricity_consumption SET datetime = '$datetime', consumption = '$consumption' WHERE id = $id";
       $result = $this->db->query($query);
 
       if ($result) {
          $electricityConsumption = new ElectricityConsumption($id, $datetime, $consumption);
          return $electricityConsumption;
       } else {
          return null;
       }
    }
 
    public function delete($id) {
       $query = "DELETE FROM electricity_consumption WHERE id = $id";
       $result = $this->db->query($query);
 
       if ($result) {
          return true;
       } else {
          return false;
       }
    }
 }
 