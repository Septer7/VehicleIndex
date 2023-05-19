<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class FuelConModel extends BaseModel{
    


private $table_name="fuel_consumption";

public function __construct()
    {
    
        parent::__construct();
    }


    public function getCarById(int $carID){
        $sql = "Select * from $this->table_name WHERE   CarID = :carID";
        return $this->run($sql,[":carID" => $carID])->fetchAll();
    }


    //Returns all fuel consumption 
    public function getAll(array $filters = [])
    {
        $filters_value = [];

        //--Querries the DB and return the list of all films.
        $sql = "SELECT * FROM $this->table_name where 1";

        //--Filter the cars by the Make
        if(isset ($filters["consumption_city"])){
            $sql.="AND consumption_city like :consumption_city";
            $filters_value[":consumption_city"]= $filters["consumption_city"]."%";
        }

        //--Filter the cars by the consumption_hwy
        if(isset ($filters["consumption_hwy"])){
            $sql.="AND consumption_hwy like :consumption_hwy";
            $filters_value[":consumption_hwy"]= $filters["consumption_hwy"]."%";
        }
       
      

        return $this->run($sql, $filters_value)->fetchAll();
    }
    public function insertFuelConsumption(int $carId, int $consumption_City,int $consumption_Hwy )
    {
        $data = [
            'CarID' => $carId,
            'Consumption_City' => $consumption_City,
            'Consumption_Hwy' => $consumption_Hwy
        ];
        return $this->insert($this->table_name, $data);
    }


    function getCarID($year,$make,$consumption_hwy){
        $sql = "SELECT car_id FROM $this->table_name WHERE year LIKE :year AND make LIKE :make AND consumption_hwy LIKE :consumption_hwy ";
        $result = $this->run($sql, [":year" => $year ,":make"=> "%" .$make. "%", ":consumption_hwy" => "%" . $consumption_hwy . "%"])->fetch();
        return $result;
    }
    
    public function deleteFuel(int $CarId)
    {
    return $this->delete($this->table_name, ['CarID' => $CarId]);
    }

    public function updateFuel($carId, $carData)
     {
         
        $where = array('CarID' => $carId); 
        $result = $this->update($this->table_name, $carData, $where); 
    
        return $result; 
    }
}