<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class CarModel extends BaseModel{
    


private $table_name="car";

public function __construct()
    {
    
        parent::__construct();
    }


    public function getCarById(int $CarId){
        $sql = "Select * from $this->table_name WHERE CarId = :CarId";
        return $this->run($sql,[":CarId" => $CarId])->fetchAll();
    }


    //Returns all cars 
    public function getAll(array $filters = [])
    {
        $filters_value = [];

        //--Querries the DB and return the list of all cars.
        $sql = "SELECT * FROM $this->table_name where 1";

        //--Filter the cars by the Make
        if(isset ($filters["Make"])){
            $sql.="AND Make like :Make";
            $filters_value[":Make"]= $filters["Make"]."%";
        }

        //--Filter the cars by the model
        if(isset ($filters["Model"])){
            $sql.="AND Model like :Model";
            $filters_value[":Model"]= $filters["Model"]."%";
        }
       
        //--Filter the cars by the year
        if(isset ($filters["Year"])){
            $sql.="AND year like :Year";
            $filters_value[":Year"]= $filters["Year"]."%";
        }
       



        return $this->run($sql, $filters_value)->fetchAll();
    }


    public function insertCar(string $make, string $model, string $year)
    {
        $data = [
            'Make' => $make,
            'Model' => $model,
            'Year' => $year
        ];
        return $this->insert($this->table_name, $data);
    }


    public function deleteCar(int $CarId)
    {
    return $this->delete($this->table_name, ['CarId' => $CarId]);
    }

    public function updateCar($carId, $carData)
     {
         
        $where = array('CarId' => $carId); 
        $result = $this->update($this->table_name, $carData, $where); 
    
        return $result; 
    }




}