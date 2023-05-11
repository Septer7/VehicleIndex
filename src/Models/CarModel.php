<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class CarModel extends BaseModel{
    


private $table_name="Car_emmissions";

public function __construct()
    {
    
        parent::__construct();
    }


    public function getCarById(int $Car_id){
        $sql = "Select * from $this->table_name WHERE Car_id = :Car_id";
        return $this->run($sql,[":Car_id" => $Car_id])->fetchAll();
    }


    //Returns all cars 
    public function getAll(array $filters = [])
    {
        $filters_value = [];

        //--Querries the DB and return the list of all films.
        $sql = "SELECT * FROM $this->table_name where 1";

        //--Filter the cars by the Make
        if(isset ($filters["make"])){
            $sql.="AND make like :make";
            $filters_value[":make"]= $filters["make"]."%";
        }

        //--Filter the cars by the model
        if(isset ($filters["model"])){
            $sql.="AND model like :model";
            $filters_value[":model"]= $filters["model"]."%";
        }
        //year is yellow no clue why can't test yet without database
        //--Filter the cars by the year
        if(isset ($filters["year"])){
            $sql.="AND year like :year";
            $filters_value[":year"]= $filters["year"]."%";
        }
       



        return $this->run($sql, $filters_value)->fetchAll();
    }





}