<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\EngineModel;
use Vanier\Api\Helpers\HelperFunctions;

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
        
        $engine = $request->getQueryParams();
        
      
        $engine_model = new EngineModel();

        
        $data= $engine_model->getAll($engine);
        $json_data= json_encode($data);

        
        $response->getBody()->write($json_data);

        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    }

    public function addEngine(Request $request, Response $response)
    {
       
        $params = $request->getParsedBody();

        $helperFunctions = new HelperFunctions();




    $validate = $helperFunctions->validateAddEngine($params);
    if ($validate === true) {
        $engine_size = $params['engine_size'];
        $hp = $params['HP'];
        $transmission = $params['Transmission'];
    
        $engine_model = new EngineModel();
        $engine_model->insertEngine($engine_size, $hp, $transmission);
    
        $response->getBody()->write("New engine added successfully");
        return $response;

        
    } else {

        var_dump($validate); exit;
        $response->getBody()->write($validate);
        return $response;

    }
    }

    
     
    public function deleteEngine($request, $response, $args)
    {
        $CarId = $args['CarId'];
        
        $engineModel = new EngineModel();
        
        $deletedRows = $engineModel->deleteEngine($CarId);
        
        if ($deletedRows === 0) {
                
            $message = 'Engine not found';
            $statusCode = 404;
        } else {
               
            $message = 'Engine deleted successfully';
            $statusCode = 200;
        }
        
            
        $response->getBody()->write(json_encode(['message' => $message]));
         return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }
    

    public function updateEngine(Request $request, Response $response, array $args)
    {
        
         $carId = $args['CarId'];

         $carData = $request->getParsedBody();

      
         $engineModel = new EngineModel();

         $result = $engineModel->updateEngine($carId, $carData);

         if ($result === 0) {
       
         $message = "Engine not found";
         $statusCode = 404;
         } else {
       
         $message = "Enigne updated successfully";
         $statusCode = 200;
         }

         $response->getBody()->write(json_encode(['message' => $message]));
         return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }

}