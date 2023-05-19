<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class TypeModel extends BaseModel{

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

        if(isset ($filters["IsElectric"])){
            $sql.="AND IsElectric like :IsElectric";
            $filters_value[":IsElectric"]= $filters["IsElectric"]."%";
        }

        if(isset ($filters["IsGas"])){
            $sql.="AND IsGas like :IsGas";
            $filters_value[":IsGas"]= $filters["IsGas"]."%";
        }
       
        if(isset ($filters["Size"])){
            $sql.="AND Size like :Size";
            $filters_value[":Size"]= $filters["Size"]."%";
        }


        return $this->run($sql, $filters_value)->fetchAll();
    }


    public function insertType(string $isElectric, string $isGas, string $size)
    {
        $data = [
            'IsElectric' => $isElectric,
            'IsGas' => $isGas,
            'Size' => $size
        ];
        return $this->insert($this->table_name, $data);
    }


    public function deleteType(int $CarId)
    {
    return $this->delete($this->table_name, ['CarId' => $CarId]);
    }

    public function updateType($carId, $carData)
     {
         
        $where = array('CarId' => $carId); 
        $result = $this->update($this->table_name, $carData, $where); 
    
        return $result; 
    }
}
