<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class EngineModel extends BaseModel{
    


private $table_name="engine";

public function __construct()
    {
    
        parent::__construct();
    }

    //GET AN ENGINE WITH CAR_ID
    public function getEngineById(int $CarId){
        $sql = "Select * from $this->table_name WHERE CarId = :CarId";
        return $this->run($sql,[":CarId" => $CarId])->fetchAll();
    }

    public function getAll(array $filters = [])
    {
        $filters_value = [];

        //--Querries the DB and return the list of all engines.
        $sql = "SELECT * FROM $this->table_name where 1";

        //--Filter the cars by the Make
        if(isset ($filters["engine_size"])){
            $sql.="AND engine_size like :engine_size";
            $filters_value[":engine_size"]= $filters["engine_size"]."%";
        }

        //--Filter the cars by the model
        if(isset ($filters["HP"])){
            $sql.="AND HP like :HP";
            $filters_value[":HP"]= $filters["HP"]."%";
        }
        //year is yellow no clue why can't test yet without database
        //--Filter the cars by the year
        if(isset ($filters["Transmission"])){
            $sql.="AND Transmission like :Transmission";
            $filters_value[":Transmission"]= $filters["Transmission"]."%";
        }
       



        return $this->run($sql, $filters_value)->fetchAll();
    }

    public function insertEngine(string $engine_size, string $hp, string $transmission)
    {

        $data = [
            'Engine_size' => $engine_size,
            'HP' => $hp,
            'Transmission' => $transmission
        ];
        return $this->insert($this->table_name, $data);
    }


    public function deleteEngine(int $CarId)
    {
    return $this->delete($this->table_name, ['CarId' => $CarId]);
    }

    public function updateEngine($carId, $carData)
     {
         // Replace with the name of your table
        $where = array('CarId' => $carId); // Specify the condition for the update
        $result = $this->update($this->table_name, $carData, $where); // Call the base model update function
    
        return $result; // Return the result of the update operation
    }
   






}