<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\CarModel;


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
    $params = $request->getParsedBody();
    $year = $params['year'];
    $make = $params['make'];
    $model = $params['model'];
    
    $car_model = new CarModel();
    $car_model->insertCar($year, $make, $model);
    
    $response->getBody()->write("New car added successfully");
    return $response;
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
    }







    
}