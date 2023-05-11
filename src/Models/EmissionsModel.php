<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class EmissionsModel extends BaseModel{
    
    private $table_name="Emissions";
    private $id;
    private $typeId;
    private $valueId;
    private $year;
    private $value;
    
    public function __construct($id, $typeId, $valueId, $year, $value) {
        $this->id = $id;
        $this->typeId = $typeId;
        $this->valueId = $valueId;
        $this->year = $year;
        $this->value = $value;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getTypeId() {
        return $this->typeId;
    }
    
    public function getValueId() {
        return $this->valueId;
    }
    
    public function getYear() {
        return $this->year;
    }
    
    public function getValue() {
        return $this->value;
    }

}