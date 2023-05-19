<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Vanier\Api\Controllers\AboutController;
use Vanier\Api\Controllers\CarController;
use Vanier\Api\Controllers\EngineController;
use Vanier\Api\Controllers\FuelConController;
use Vanier\Api\Controllers\VinController;
use Vanier\Api\Controllers\AuthenticationController;
use Vanier\Api\Controllers\ReviewController;
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

$app->get('/vin/{vinNumber}', [VinController::class, 'getCarByVin']);
$app->get('/vin/getManufacturer/{vinNumber}', [VinController::class, 'getCarByVin']);

$app->get('/recall/{recallID}', [RecallController::class, 'getRecall']);

$app->get('/emissions', [EmissionsController::class, 'getAllEmissions']);
$app->get('/emission/{CarID}', [EmissionsController::class, 'getEmissionsById']);

$app->get('/values', [ValueController::class, 'getAllValue']);
$app->get('/value/{CarID}', [ValueController::class, 'getValueById']);

$app->get('/types', [TypeController::class, 'getAllTypes']);
$app->get('/type/{CarID}', [TypeController::class, 'getTypesById']);


$app->get('/reviews', [ReviewController::class,'getAllReviews']);
$app->get('/reviews/{review_id:[0-9]+}', [ReviewController::class,'getReviewById']);
$app->get('/reviews/make/{make_name}', [ReviewController::class,'getReviewByMake']);
$app->get('/reviews/model/{model_name}', [ReviewController::class,'getReviewByModel']);

$app->get('/fuel_consumption', [FuelConController::class,'getAllFuels']);
$app->get('/fuel_consumption/{id}', [FuelConController::class,'getCarId']);
//Can't add another engine because I also need to assign it's FK to a car
//$app->post('/addengine', [EngineController::class, 'addEngine']);


$app->post('/addcar', [CarController::class, 'addCar']);
$app->post('/addFuelConsumption', [FuelConController::class, 'addFuelConsumption']);

$app->delete('/deleteCar/{CarId}',[CarController::class, 'deleteCar']);
$app->delete('/deleteEngine/{CarId}',[EngineController::class, 'deleteEngine']);
$app->delete('/reviews', [ReviewController::class, 'handleDeleteReview']);
$app->delete('/emissions', [ReviewController::class, 'handleDeleteEmissions']);
$app->delete('/deletefuelConsumption/{CarID}', [FuelConController::class, 'deleteFuelConsumption']);

$app->put('/updateCar/{CarId}',[CarController::class, 'updateCar']);
$app->put('/updateFuel/{CarId}',[FuelConController::class, 'updateFuelConsumption']);
$app->put('/updateEngine/{CarId}',[EngineController::class, 'updateEngine']);
$app->put('/reviews', [ReviewController::class, 'handleUpdateReview']);
$app->put('/emissions', [ReviewController::class, 'handleUpdateEmissions']);

$app->post('/account', [AuthenticationController::class, 'handleCreateUserAccount']);
$app->post('/token', [AuthenticationController::class, 'handleGetToken']);
$app->post('/reviews', [ReviewController::class, 'handleCreateReview']);
$app->post('/emissions', [CarController::class, 'handleCreateEmission']);
