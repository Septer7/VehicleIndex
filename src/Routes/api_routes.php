<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Vanier\Api\Controllers\AboutController;
use Vanier\Api\Controllers\CarController;
use Vanier\Api\Controllers\EngineController;
use Vanier\Api\Models\CarModel;

// Import the app instance into this file's scope.
global $app;

// NOTE: Add your app routes here.
// The callbacks must be implemented in a controller class.
// The Vanier\Api must be used as namespace prefix. 

// ROUTE: /
$app-> get('/', [AboutController::class, 'handleAboutApi']); 

// ROUTE: /hello
$app->get('/hello', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Reporting! Hello there!");    
    return $response;
});

$app->get('/cars', [CarController::class, 'getAllCars']);
$app->get('/car/{id}', [CarController::class, 'getCar']);  

$app->get('/engines', [EngineController::class, 'getAllEngine']);
$app->get('/engine/{id}', [EngineController::class, 'getEngine']);   

$app->get('/vin/{vinNumber}', [CarController::class, 'getCarByVin']);
$app->get('/vin/getManufacturer/{vinNumber}', [CarController::class, 'getCarByVin']);

$app->get('/recall/{recallID}', [RecallController::class, 'getRecall']);

//Can't add another engine because I also need to assign it's FK to a car
//$app->post('/addengine', [EngineController::class, 'addEngine']);


$app->post('/addcar', [CarController::class, 'addCar']);


$app->delete('/deleteCar/{CarId}',[CarController::class, 'deleteCar']);
$app->delete('/deleteEngine/{CarId}',[EngineController::class, 'deleteEngine']);

$app->put('/updateCar/{CarId}',[CarController::class, 'updateCar']);
$app->put('/updateEngine/{CarId}',[EngineController::class, 'updateEngine']);


