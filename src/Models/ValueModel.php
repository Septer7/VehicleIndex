<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class ValueModel extends BaseModel{
    

private $table_name="car";

public function __construct()
    {
    
        parent::__construct();
    }


    public function getCarById(int $CarId){
        $sql = "Select * from $this->table_name WHERE CarId = :CarId";
        return $this->run($sql,[":CarId" => $CarId])->fetchAll();
    }


    public function getAll(array $filters = [])
    {
        $filters_value = [];

        $sql = "SELECT * FROM $this->table_name where 1";

        if(isset ($filters["Make"])){
            $sql.="AND Price like :Price";
            $filters_value[":Price"]= $filters["Price"]."%";
        }

        if(isset ($filters["Tax"])){
            $sql.="AND Tax like :Tax";
            $filters_value[":Tax"]= $filters["Tax"]."%";
        }
       
        if(isset ($filters["Incentives"])){
            $sql.="AND Incentives like :Incentives";
            $filters_value[":Incentives"]= $filters["Incentives"]."%";
        }
       



        return $this->run($sql, $filters_value)->fetchAll();
    }


    public function insertValue(string $price, string $tax, string $incentives)
    {
        $data = [
            'Price' => $price,
            'Tax' => $tax,
            'Incentives' => $incentives
        ];
        return $this->insert($this->table_name, $data);
    }


    public function deleteValue(int $CarId)
    {
    return $this->delete($this->table_name, ['CarId' => $CarId]);
    }

    public function updateValue($carId, $carData)
     {
         
        $where = array('CarId' => $carId); 
        $result = $this->update($this->table_name, $carData, $where); 
    
        return $result; 
    }




}
