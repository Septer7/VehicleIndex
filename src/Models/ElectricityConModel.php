<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class ElectrictyConModel extends BaseModel{
    


private $table_name="electricity_consumption";

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
        if(isset ($filters["Econsumption_city"])){
            $sql.="AND Econsumption_city like :Econsumption_city";
            $filters_value[":Econsumption_city"]= $filters["Econsumption_city"]."%";
        }

        //--Filter the cars by the consumption_hwy
        if(isset ($filters["Econsumption_hwy"])){
            $sql.="AND Econsumption_hwy like :Econsumption_hwy";
            $filters_value[":Econsumption_hwy"]= $filters["Econsumption_hwy"]."%";
        }
       
      

        return $this->run($sql, $filters_value)->fetchAll();
    }
    public function insertElectricConsumption(int $carId, int $consumption_City,int $consumption_Hwy,int $VehicleRange )
    {
        $data = [
            'CarID' => $carId,
            'EConsumption_City' => $consumption_City,
            'EConsumption_Hwy' => $consumption_Hwy,
            'VehicleRange' => $VehicleRange
        ];
        return $this->insert($this->table_name, $data);
    }


    function getCarID($year,$make,$consumption_hwy){
        $sql = "SELECT car_id FROM $this->table_name WHERE year LIKE :year AND make LIKE :make AND consumption_hwy LIKE :consumption_hwy ";
        $result = $this->run($sql, [":year" => $year ,":make"=> "%" .$make. "%", ":consumption_hwy" => "%" . $consumption_hwy . "%"])->fetch();
        return $result;
    }
    
    public function deleteElectricity(int $CarId)
    {
    return $this->delete($this->table_name, ['CarID' => $CarId]);
    }

    public function updateElectricity($carId, $carData)
     {
         
        $where = array('CarID' => $carId); 
        $result = $this->update($this->table_name, $carData, $where); 
    
        return $result; 
    }
}