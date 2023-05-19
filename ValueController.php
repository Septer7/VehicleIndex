<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\ValueModel;

class ValueController
{
    
    public $valueModel = null;
    
    public function __construct(){

        $this->valueModel = new ValueModel();
    }



    public function getModel(Request $request, Response  $response, array $args)
    {
        $valueId = $args['id'];
        $valueModel = new EngineModel();  
        $data = $valueModel->getEngineById((int)$valueId); 
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    }



    public function getAllModel(Request $request, Response $response)
    {
        $value_model = $request->getQueryParams();
        
        $value_model = new ValueModel();

        $data= $value_model->getAll($engine);
        $json_data= json_encode($data);

        $response->getBody()->write($json_data);

        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    }

    public function addValue(Request $request, Response $response)
    {
       
        $params = $request->getParsedBody();
        $engine_size = $params['engine_size'];
        $hp = $params['HP'];
        $transmission = $params['Transmission'];
    
        $value_model = new ValueModel();
        $value_model->insertEngine($engine_size, $hp, $transmission);
    
        $response->getBody()->write("New car value added successfully");
        return $response;
    }

    
     
    public function deleteModel($request, $response, $args)
    {
        $CarId = $args['CarId'];
        
        $valueModel = new ValueModel();
        
            // Call the deleteCar method to delete the car
        $deletedRows = $valueModel->deleteValue($CarId);
        
        if ($deletedRows === 0) {
                // No car value was deleted
            $message = 'Car value missing, not found';
            $statusCode = 404;
        } else {
                // Car value was deleted successfully
            $message = 'Car Value deleted successfully';
            $statusCode = 200;
        }
        
            // Return a JSON response with the status code and message
        $response->getBody()->write(json_encode(['message' => $message]));
         return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }


    public function updateValue(Request $request, Response $response, array $args)
    {
         $carId = $args['CarId'];

         $carData = $request->getParsedBody();

         $valueModel = new ValueModel();

         $result = $valueModel->updateValue($carId, $carData);

         if ($result === 0) {
         $message = "Value missing, not found";
         $statusCode = 404;
         } else {
         $message = "Car value updated successfully";
         $statusCode = 200;
         }

        // Return a JSON response with the status code and message
         $response->getBody()->write(json_encode(['message' => $message]));
         return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }

}