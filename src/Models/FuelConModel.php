<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class FuelConModel extends BaseModel{
    


private $table_name="Fuel_Consumption";

public function __construct()
{
    
        parent::__construct();
}


    private $id;
    private $vehicle_id;
    private $fuel_type_id;
    private $fuel_quantity;
    private $distance_travelled;
    private $created_at;
  
    public function __construct($id, $vehicle_id, $fuel_type_id, $fuel_quantity, $distance_travelled, $created_at) {
      $this->id = $id;
      $this->vehicle_id = $vehicle_id;
      $this->fuel_type_id = $fuel_type_id;
      $this->fuel_quantity = $fuel_quantity;
      $this->distance_travelled = $distance_travelled;
      $this->created_at = $created_at;
    }
  
    // getters and setters for all properties
    public function getId() {
      return $this->id;
    }
  
    public function setId($id) {
      $this->id = $id;
    }
  
    public function getVehicleId() {
      return $this->vehicle_id;
    }
  
    public function setVehicleId($vehicle_id) {
      $this->vehicle_id = $vehicle_id;
    }
  
    public function getFuelTypeId() {
      return $this->fuel_type_id;
    }
  
    public function setFuelTypeId($fuel_type_id) {
      $this->fuel_type_id = $fuel_type_id;
    }
  
    public function getFuelQuantity() {
      return $this->fuel_quantity;
    }
  
    public function setFuelQuantity($fuel_quantity) {
      $this->fuel_quantity = $fuel_quantity;
    }
  
    public function getDistanceTravelled() {
      return $this->distance_travelled;
    }
  
    public function setDistanceTravelled($distance_travelled) {
      $this->distance_travelled = $distance_travelled;
    }
  
    public function getCreatedAt() {
      return $this->created_at;
    }
  
    public function setCreatedAt($created_at) {
      $this->created_at = $created_at;
    }    






}