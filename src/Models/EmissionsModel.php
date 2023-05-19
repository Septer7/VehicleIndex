<?php

namespace Vanier\Api\Models;
use Vanier\Api\Models\BaseModel;


class EmissionsModel extends BaseModel{
    
    private $table_name="emissions";
    
    /**
     * A model class for the `emissions` database table.
     * It exposes operations for reading, creating, updating and deleting emissions statistics.
     */

    function __construct() {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Returns a full list of emissions statistics.
     * Requires no parameters.
     */
    public function getAllEmissions() {
        $sql = "SELECT * FROM $this->table_name WHERE 1=1";
        $result = $this->paginate($sql);
        return $result;
    }
    
    /**
     * Returns an emissions statistic by a specific car id.
     * Requires an id
     * @param int $CarID
     */
    public function getEmissionsById($CarID) {
        $sql = "SELECT * FROM $this->table_name WHERE CarID=:CarID";
        $result = $this->run($sql, [":CarID" => $CarID])->fetch();
        return $result;
    }

    /**
     * Returns emissions statistics by a specific gas emission amount.
     * Requires an gas emission value
     * @param int $gas_emissions
     */
    public function getEmissionsByGas($gas_emissions) {
        $sql = "SELECT * FROM $this->table_name WHERE gas_emissions = :gas_emissions";
        $result = $this->paginate($sql, [":gas_emissions" => $gas_emissions]);
        return $result;
    }

    /**
     * Returns emissions statistics by a CO2 index.
     * Requires a CO2 index.
     * @param int $CO2_Index
     */
    public function getEmissionsByCO2($CO2_Index) {
        $sql = "SELECT * FROM $this->table_name WHERE CO2_Index = :CO2_index";
        $result = $this->paginate($sql, [":CO2_index" => $CO2_Index]);
        return $result;
    }

    /**
     * Returns emissions statistics by a smog index.
     * Requires a smog index.
     * @param int $Smog_Index
     */
    public function getEmissionsBySmog($Smog_Index) {
        $sql = "SELECT * FROM $this->table_name WHERE Smog_Index = :Smog_Index";
        $result = $this->paginate($sql, [":Smog_Index" => $Smog_Index]);
        return $result;
    }

    /**
     * Create one or more emissions statistics
     */
    function createEmissions($emissions) {
        $data = $this->insert($this->table_name, $emissions) ;
        return $data;
    }

    /**
     * Update an emissions statistic record
     */
    public function updateEmissions($emissions, $CarID) {
        $review = $this->update($this->table_name, $emissions, array(':CarID' => $CarID));
        return $review;
    }
    
    /**
     * Delete one or more emissions statistic records
     * Requires an id
     * @param int $CarID
     */
    function deleteEmission($CarID){
        $data = $this->deleteByIds($this->table_name, ":CarID", $CarID);
        return $data;
    }
}