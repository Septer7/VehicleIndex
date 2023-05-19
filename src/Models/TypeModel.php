<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class TypeModel extends BaseModel{
    
    private $table_name="Type";
    private $id;
    private $name;
    private $description;
    
    public function __construct($id, $name, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

}