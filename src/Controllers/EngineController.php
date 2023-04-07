<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\EngineModel;

class EngineController
{
    
    public $engineModel = null;
    
    public function __construct(){

        $this->engineModel = new EngineModel();
    }



    public function getEngine(Request $request, Response  $response, array $args)
    {
        $engineId = $args['id'];
        $engineModel = new EngineModel();  
        $data = $engineModel->getEngineById((int)$engineId); 
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    }



    public function getAllEngine(Request $request, Response $response)
    {
        //--step 1) get the querry params from the request
        $engine = $request->getQueryParams();
        
        //--step 3) Fetch the data from the Model
        //new instance of the model 
        $engine_model = new EngineModel();

        //fetch a list of films
        $data= $engine_model->getAll($engine);
        $json_data= json_encode($data);

        //-- We need to prepare the response 
        // $response->getBody()->write("List all the films");
        $response->getBody()->write($json_data);

        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    }

    public function addEngine(Request $request, Response $response)
    {
       
        $params = $request->getParsedBody();
        $engine_size = $params['engine_size'];
        $hp = $params['HP'];
        $transmission = $params['Transmission'];
    
        $engine_model = new EngineModel();
        $engine_model->insertEngine($engine_size, $hp, $transmission);
    
        $response->getBody()->write("New car added successfully");
        return $response;
    }

    
     
    public function deleteEngine($request, $response, $args)
    {
        $CarId = $args['CarId'];
        
            // Get the CarModel instance
        $engineModel = new EngineModel();
        
            // Call the deleteCar method to delete the car
        $deletedRows = $engineModel->deleteEngine($CarId);
        
        if ($deletedRows === 0) {
                // No car was deleted
            $message = 'Engine not found';
            $statusCode = 404;
        } else {
                // Car was deleted successfully
            $message = 'Engine deleted successfully';
            $statusCode = 200;
        }
        
            // Return a JSON response with the status code and message
        $response->getBody()->write(json_encode(['message' => $message]));
         return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }
    // public function update($id, $name) {
    //     $sql = "UPDATE car SET name = :name WHERE id = :id";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':id', $id);
    //     $stmt->bindParam(':name', $name);
    //     return $stmt->execute();
    // }

    // public function delete($id) {
    //     $sql = "DELETE FROM type WHERE id = :id";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':id', $id);
    //     return $stmt->execute();
    //   }

    public function updateEngine(Request $request, Response $response, array $args)
    {
        // Get the ID of the car to update
         $carId = $args['CarId'];

        // Get the new car data from the request body
         $carData = $request->getParsedBody();

        // Get an instance of the CarModel
         $engineModel = new EngineModel();

        // Call the updateCar method in the CarModel, passing in the car ID and data
         $result = $engineModel->updateEngine($carId, $carData);

        // Check if the update was successful
         if ($result === 0) {
        // No rows were updated, so the car was not found
         $message = "Engine not found";
         $statusCode = 404;
         } else {
        // The car was updated successfully
         $message = "Enigne updated successfully";
         $statusCode = 200;
         }

        // Return a JSON response with the status code and message
         $response->getBody()->write(json_encode(['message' => $message]));
         return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }

}