<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\CarModel;
use  Vanier\Api\Helpers\Validator;


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
        $data = $carModel->getCarById((int)$carId); 
        $json_data = json_encode($data);
        $response->getBody()->write($json_data);
        return $response->withStatus(201)->withHeader("Content-Type", "application/json");

        // $carId = $args['id'];
        
        // try {
        //     $data = $this->carModel->getCarById((int)$carId);
        //     if ($data === false) {
        //         throw new \Exception('Car not found');
        //     }
            
        //     $json_data = json_encode($data);
        //     $response->getBody()->write($json_data);
        //     return $response->withStatus(200)->withHeader("Content-Type", "application/json");
        // } catch (\Exception $ex) {
        //     $errorResponse = [
        //         'error' => 'Car not found',
        //         'message' => $ex->getMessage()
        //     ];
            
        //     $response->getBody()->write(json_encode($errorResponse));
        //     return $response->withStatus(404)->withHeader("Content-Type", "application/json");
        // }



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
        $response->getBody()->write($json_data);

        return $response->withStatus(201)->withHeader("Content-Type", "application/json");

















        // $cars = $request->getQueryParams();
        
        // try {
        //     $carModel = new CarModel();
        //     $data = $carModel->getAll($cars);
        //     $json_data = json_encode($data);
            
        //     $response->getBody()->write($json_data);
        //     return $response->withStatus(200)->withHeader("Content-Type", "application/json");
        // } catch (\Exception $e) {
        //     $errorResponse = [
        //         'error' => 'Internal Server Error',
        //         'message' => $e->getMessage()
        //     ];
            
        //     $response->getBody()->write(json_encode($errorResponse));
        //     return $response->withStatus(500)->withHeader("Content-Type", "application/json");
        // }














    }
    public function addCar(Request $request, Response $response)
    {
    $params = $request->getParsedBody();
    
    $make = $params['make'];
    $model = $params['model'];
    $year = $params['year'];
    
    $car_model = new CarModel();
    $car_model->insertCar($make, $model,$year);
    
    $response->getBody()->write("New car added successfully");
    return $response;




    
    // $params = $request->getParsedBody();
        
    // // Validate the input data using the Validator
    // $validator = new Validator();
    // $validationResult = $validator->validate($params);
    
    // if (!$validationResult['valid']) {
    //     // Return a response with validation errors
    //     $errorResponse = [
    //         'error' => 'Invalid input',
    //         'validation_errors' => $validationResult['errors']
    //     ];
        
    //     $response->getBody()->write(json_encode($errorResponse));
    //     return $response->withStatus(400)->withHeader("Content-Type", "application/json");
    // }
    
    // // Proceed with adding the car to the database
    // $year = $params['year'];
    // $make = $params['make'];
    // $model = $params['model'];
    
    // try {
    //     $this->carModel->insertCar($year, $make, $model);
    //     $response->getBody()->write("New car added successfully");
    //     return $response->withStatus(201);
    // } catch (\Exception $e) {
    //     $errorResponse = [
    //         'error' => 'Internal Server Error',
    //         'message' => $e->getMessage()
    //     ];
        
    //     $response->getBody()->write(json_encode($errorResponse));
    //     return $response->withStatus(500)->withHeader("Content-Type", "application/json");
    //  }
    }

    
     
    public function deleteCar($request, $response, $args)
    {
        $CarId = $args['CarId'];
        
            // Get the CarModel instance
        $carModel = new CarModel();
        
            // Call the deleteCar method to delete the car
        $deletedRows = $carModel->deleteCar($CarId);
        
        if ($deletedRows === 0) {
                // No car was deleted
            $message = 'Car not found';
            $statusCode = 404;
        } else {
                // Car was deleted successfully
            $message = 'Car deleted successfully';
            $statusCode = 200;
        }
        
            // Return a JSON response with the status code and message
        $response->getBody()->write(json_encode(['message' => $message]));
         return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);




        //  $carId = $args['CarId'];
        
        //  try {
        //      $deletedRows = $this->carModel->deleteCar((int)$carId);
             
        //      if ($deletedRows === 0) {
        //          $errorResponse = [
        //              'error' => 'Car not found',
        //              'message' => 'Car not found or already deleted'
        //          ];
                 
        //          $response->getBody()->write(json_encode($errorResponse));
        //          return $response->withStatus(404)->withHeader("Content-Type", "application/json");
        //      }
             
        //      $response->getBody()->write("Car deleted successfully");
        //      return $response->withStatus(200);
        //  } catch (\Exception $e) {
        //      $errorResponse = [
        //          'error' => 'Internal Server Error',
        //          'message' => $e->getMessage()
        //      ];
             
        //      $response->getBody()->write(json_encode($errorResponse));
        //      return $response->withStatus(500)->withHeader("Content-Type", "application/json");
        //  }




















    }
    

    public function updateCar(Request $request, Response $response, array $args)
    {
        // Get the ID of the car to update
         $carId = $args['CarId'];

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













        //  $carId = $args['CarId'];
        // $carData = $request->getParsedBody();
        
        // try {
        //     $result = $this->carModel->updateCar($carId, $carData);
            
        //     if ($result === 0) {
        //         $errorResponse = [
        //             'error' => 'Car not found',
        //             'message' => 'Car not found or update failed'
        //         ];
                
        //         $response->getBody()->write(json_encode($errorResponse));
        //         return $response->withStatus(404)->withHeader("Content-Type", "application/json");
        //     }
            
        //     $response->getBody()->write("Car updated successfully");
        //     return $response->withStatus(200);
        // } catch (\Exception $e) {
        //     $errorResponse = [
        //         'error' => 'Internal Server Error',
        //         'message' => $e->getMessage()
        //     ];
            
        //     $response->getBody()->write(json_encode($errorResponse));
        //     return $response->withStatus(500)->withHeader("Content-Type", "application/json");
        // }












    }







    
}