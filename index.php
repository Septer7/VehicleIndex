<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Vanier\Api\Helpers\JWTManager;
use Vanier\Api\Middleware\ContentNegotiationMiddleware;
use Vanier\Api\Middleware\JWTAuthMiddleware;

require __DIR__ . '/vendor/autoload.php';
 // Include the file that contains the application's global configuration settings,
 // database credentials, etc.
require_once __DIR__ . '/src/config/app_config.php';
define('APP_BASE_DIR', __DIR__);
// IMPORTANT: This file must be added to your .ignore file. 
define('APP_ENV_CONFIG', 'config.env');
define('APP_JWT_TOKEN_KEY', 'APP_JWT_TOKEN');
//--Step 1) Instantiate a Slim app.

$app = AppFactory::create();
$app->setBasePath("/vehicle-api");
$api_base_path = "/vehicle-api";

$app->add(new ContentNegotiationMiddleware([APP_MEDIA_TYPE_XML, APP_MEDIA_TYPE_YAML]));

//-- Step 2) Add routing middleware.
$app->addRoutingMiddleware();

$jwt_secret = JWTManager::getSecretKey();
$app->add(new JWTAuthMiddleware());

$app->add(new Tuupola\Middleware\JwtAuthentication([
        'secret' => $jwt_secret,
        'algorithm' => 'HS256',
        'secure' => false, // only for localhost for prod and test env set true            
        "path" => $api_base_path, // the base path of the API
        "attribute" => "decoded_token_data",
        "ignore" => ["$api_base_path/token", "$api_base_path/account"],
        "error" => function ($response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];
            $response->getBody()->write(
                json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            );
            return $response->withHeader("Content-Type", "application/json;charset=utf-8");
        }
]));

// body parsing middleware
$app->addBodyParsingMiddleware();
//-- Step 3) Add error handling middleware.
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware = $errorMiddleware->getDefaultErrorHandler();
$errorMiddleware->forceContentType(APP_MEDIA_TYPE_JSON);
//-- Step 4)
// TODO: change the name of the subdirectory here.
// You also need to change it in .htaccess


//-- Step 5)
// Here we include the file that contains the application routes. 
require_once __DIR__ . './src/routes/api_routes.php';

// NOTE: Add your app routes here.
// The callbacks must be implemented in a controller class.
// The Vanier\Api must be used as namespace prefix. 

// Root
// $app->get('/', [AboutController::class, 'handleAboutApi']); 

// $app->get('/cars', [CarController::class, 'getAllCars']);
// $app->get('/car/{id}', [CarController::class, 'getCar']);  

// $app->get('/engines', [EngineController::class, 'getAllEngine']);
// $app->get('/engine/{id}', [EngineController::class, 'getEngine']);   

// $app->get('/vin/{vinNumber}', [VinController::class, 'getCarByVin']);
// $app->get('/vin/getManufacturer/{vinNumber}', [VinController::class, 'getManufacturerByVin']);

// $app->get('/recall/{recallID}', [RecallController::class, 'getRecallByID']);

// //Can't add another engine because I also need to assign it's FK to a car
// //$app->post('/addengine', [EngineController::class, 'addEngine']);


// $app->post('/addcar', [CarController::class, 'addCar']);


// $app->delete('/deleteCar/{CarId}',[CarController::class, 'deleteCar']);
// $app->delete('/deleteEngine/{CarId}',[EngineController::class, 'deleteEngine']);

// $app->put('/updateCar/{CarId}',[CarController::class, 'updateCar']);
// $app->put('/updateEngine/{CarId}',[EngineController::class, 'updateEngine']);

// // Test route
// $app->get('/hello', function (Request $request, Response $response, $args) {
//     $response->getBody()->write("Hello! API is Ready..");    
//     return $response->withStatus(HTTP_OK);
// });

// This is a middleware that should be disabled/enabled later. 
//$app->add($beforeMiddleware);
// Run the app.
$app->run();
