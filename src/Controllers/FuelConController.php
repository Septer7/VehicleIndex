<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Vanier\Api\Models\FuelConModel;
use Vanier\Api\Helpers\HelperFunctions;


class FuelConController
{
    public $FuelConModel = null;
    
    public function __construct(){

        $this->FuelConModel = new FuelConModel();
    }

    public function getCarId(Request $request, Response  $response, array $args)
    {
        

        $carId = $args['id'];

        $FuelConModel = new FuelConModel();
        $data = $FuelConModel->getCarById((int) $carId);

        $helperFunctions = new HelperFunctions();
        return $helperFunctions->checkData($data, $response, $request);
    }
    

    public function getAllFuels(Request $request, Response $response)
    {
        //--step 1) get the querry params from the request
        $fuels = $request->getQueryParams();
        
        //--step 3) Fetch the data from the Model
        //new instance of the model 
        $FuelConModel = new FuelConModel();

        //fetch a list of films
        $data= $FuelConModel->getAll($fuels);
        $json_data= json_encode($data);

        //-- We need to prepare the response 
        // $response->getBody()->write("List all the films");
        $response->getBody()->write($json_data);

        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    }
    public function addFuelConsumption(Request $request, Response $response)
    {
    // return $response;
    $params = $request->getParsedBody();

    $helperFunctions = new HelperFunctions();
    $validate = $helperFunctions->validateAddFuelConsumption($params);


    if ($validate === true) {
        $carId = $params['CarID'];
        $consumption_City = $params['Consumption_City'];
        $consumption_Hwy = $params['Consumption_Hwy'];


        $FuelConModel = new FuelConModel();
        $FuelConModel->insertFuelConsumption($carId,$consumption_City,$consumption_Hwy);

        $response->getBody()->write("New fuel consumption added successfully");
        return $response;

    } else {

        var_dump($validate); exit;
        $response->getBody()->write($validate);
        return $response;

    }
    }
    public function deleteFuelConsumption($request, $response, $args)
    {

        $carId = $args['CarID'];

         $helperFunctions = new HelperFunctions();
         $validate = $helperFunctions->validateDeleteFuelConsumption($carId);
        
         if ($validate === true) {
            $FuelConModel = new FuelConModel();
            $deletedRows = $FuelConModel->deleteFuel($carId);
        
            if ($deletedRows === 0) {
                $message = 'Car not found';
                $statusCode = 404;
            } else {
                $message = 'Car deleted successfully';
                $statusCode = 200;
            }
        
            $response->getBody()->write(json_encode(['message' => $message]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
         } else {
             $response->getBody()->write($validate);
             return $response;
         }
    }
    public function updateFuelConsumption(Request $request, Response $response, array $args)
    {
        // Get the ID of the car to update

         $carId = $args['CarID'];
         $helperFunctions = new HelperFunctions();
         $validate = $helperFunctions->validateUpdateFuel($carId);
  
         if ($validate === true) {
        // Get the new car data from the request body
         $carData = $request->getParsedBody();

        // Get an instance of the CarModel
        $FuelConModel = new FuelConModel();

        // Call the updateCar method in the CarModel, passing in the car ID and data
         $result = $FuelConModel->updateFuel($carId, $carData);

        // Check if the update was successful
         if ($result === 0) {
        // No rows were updated, so the car was not found
         $message = "Car not found";
         $statusCode = 404;
         } else {
        // The car was updated successfully
         $message = "Car updated successfully";
         $statusCode = 200;
         }

        // Return a JSON response with the status code and message
         $response->getBody()->write(json_encode(['message' => $message]));
         return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);

        } else {
            $response->getBody()->write($validate);
            return $response;
        }
    }

    
        
    }


