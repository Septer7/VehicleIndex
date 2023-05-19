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
       
        $cars = $request->getQueryParams();
        
       
        $car_model = new CarModel();

        $data= $car_model->getAll($cars);
        $json_data= json_encode($data);

        $response->getBody()->write($json_data);

        return $response->withStatus(201)->withHeader("Content-Type", "application/json");
    }
    public function addCar(Request $request, Response $response)
    {
    
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



         $carId = $args['CarId'];
         $helperFunctions = new HelperFunctions();
         $validate = $helperFunctions->validateUpdateCar($carId);


         
         if ($validate === true) {
        
         $carData = $request->getParsedBody();

         $carModel = new CarModel();

      
         $result = $carModel->updateCar($carId, $carData);

      
         if ($result === 0) {
       
         $message = "Car not found";
         $statusCode = 404;
         } else {
     
         $message = "Car updated successfully";
         $statusCode = 200;
         }

       
         $response->getBody()->write(json_encode(['message' => $message]));
         return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);

        } else {
            $response->getBody()->write($validate);
            return $response;
        }
    }







    
}
