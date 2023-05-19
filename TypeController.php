<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\TypeModel;

class TypeController
{
    
    public $engineModel = null;
    
    public function __construct(){

        $this->TypeModel = new TypeModel();
    }


    public function getType(Request $request, Response  $response, array $args)
    {
        $typeId = $args['id'];
        $typeModel = new EngineModel();  
        $data = $typeModel->getEngineById((int)$engineId); 
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    }



    public function getAllType(Request $request, Response $response)
    {
        $type = $request->getQueryParams();
        
        $type_model = new TypeModel();

        $data= $type_model->getAll($type);
        $json_data= json_encode($data);

        $response->getBody()->write($json_data);

        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    }

    public function addType(Request $request, Response $response)
    {
       
        $params = $request->getParsedBody();
        $engine_size = $params['engine_size'];
        $hp = $params['HP'];
        $transmission = $params['Transmission'];
    
        $type_model = new TypeModel();
        $type_model->insertEngine($engine_size, $hp, $transmission);
    
        $response->getBody()->write("New car type, added successfully");
        return $response;
    }

    
     
    public function deleteType($request, $response, $args)
    {
        $TypeId = $args['CarId'];
        
        $typeModel = new TypeModel();
        
        $deletedRows = $typeModel->deleteEngine($CarId);
        
        if ($deletedRows === 0) {
                // No car type was deleted
            $message = 'Car Type missing, not found';
            $statusCode = 404;
        } else {
                // Car type was deleted successfully
            $message = 'Type deleted successfully';
            $statusCode = 200;
        }
        
            // Return a JSON response with the status code and message
        $response->getBody()->write(json_encode(['message' => $message]));
         return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }

    public function updateType(Request $request, Response $response, array $args)
    {
         $carId = $args['CarId'];

         $carData = $request->getParsedBody();

         $typeModel = new TypeModel();

         $result = $typeModel->updateType($carId, $carData);

         if ($result === 0) {

         $message = "Car type missing, not found";
         $statusCode = 404;
         } else {

         $message = "Type updated successfully";
         $statusCode = 200;
         }

        // Return a JSON response with the status code and message
         $response->getBody()->write(json_encode(['message' => $message]));
         return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }

}