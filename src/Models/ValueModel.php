<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class ValueModel extends BaseModel{
    
    private $table_name="Value";
    private $id;
    private $typeId;
    private $value;
    
    public function __construct($id, $typeId, $value) {
        $this->id = $id;
        $this->typeId = $typeId;
        $this->value = $value;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getTypeId() {
        return $this->typeId;
    }
    
    public function getValue() {
        return $this->value;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setTypeId($typeId) {
        $this->typeId = $typeId;
    }
    
    public function setValue($value) {
        $this->value = $value;
    }







}