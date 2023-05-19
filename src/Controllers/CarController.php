<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Vanier\Api\Models\CarModel;
use Vanier\Api\Helpers\HelperFunctions;


class CarController
{
    public $carModel = null;
    
    public function __construct(){

        $this->carModel = new CarModel();
    }

    public function getCar(Request $request, Response  $response, array $args)
    {
        

        $carId = $args['id'];

        $carModel = new CarModel();
        $data = $carModel->getCarById((int) $carId);

        $helperFunctions = new HelperFunctions();
        return $helperFunctions->checkData($data, $response, $request);
    }
    

    public function getAllCars(Request $request, Response $response)
    {
        //--step 1) get the querry params from the request
        $cars = $request->getQueryParams();
        
        //--step 3) Fetch the data from the Model
        //new instance of the model 
        $car_model = new CarModel();

        //fetch a list of films
        $data= $car_model->getAll($cars);
        $json_data= json_encode($data);

        //-- We need to prepare the response 
        // $response->getBody()->write("List all the films");
        $response->getBody()->write($json_data);

        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    }
    public function addCar(Request $request, Response $response)
    {
    // $params = $request->getParsedBody();
    // $year = $params['year'];
    // $make = $params['make'];
    // $model = $params['model'];
    
    // $car_model = new CarModel();
    // $car_model->insertCar($year, $make, $model);
    
    // $response->getBody()->write("New car added successfully");
    // return $response;
    $params = $request->getParsedBody();

    $helperFunctions = new HelperFunctions();




    $validate = $helperFunctions->validateAddCar($params);

    if ($validate === true) {
        $year = $params['year'];
        $make = $params['make'];
        $model = $params['model'];


        $car_model = new CarModel();
        $car_model->insertCar($make, $model, $year);

        $response->getBody()->write("New car added successfully");
        return $response;

    } else {

        var_dump($validate); exit;
        $response->getBody()->write($validate);
        return $response;

    }


    }

    
     
    public function deleteCar($request, $response, $args)
    {
       


        $carId = $args['CarId'];

         $helperFunctions = new HelperFunctions();
         $validate = $helperFunctions->validateDeleteCar($carId);
        
         if ($validate === true) {
            $carModel = new CarModel();
            $deletedRows = $carModel->deleteCar($carId);
        
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
 

    public function updateCar(Request $request, Response $response, array $args)
    {
        // Get the ID of the car to update



         $carId = $args['CarId'];
         $helperFunctions = new HelperFunctions();
         $validate = $helperFunctions->validateUpdateCar($carId);


         
         if ($validate === true) {
        // Get the new car data from the request body
         $carData = $request->getParsedBody();

        // Get an instance of the CarModel
         $carModel = new CarModel();

        // Call the updateCar method in the CarModel, passing in the car ID and data
         $result = $carModel->updateCar($carId, $carData);

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