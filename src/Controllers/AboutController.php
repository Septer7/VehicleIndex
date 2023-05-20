<?php
namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Helpers\Validator;
use Vanier\Api\Models\WSLoggingModel;

class AboutController extends BaseController
{
    public function handleAboutApi(Request $request, Response $response, array $uri_args)
    {
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        // Exposed resources displayed by '/' route
        $exposed_resources = array();
        $exposed_resources['about'] = 'Welcome, this is a Web services that provides this and that...';
        $exposed_resources['GET  | Test route'] = '/hello';
        $exposed_resources['GET  | Vin Decode'] = '/vin/{vinNumber}';
        $exposed_resources['GET | Car Manufacturer By VIN'] = '/vin/getManufacturer/{vinNumber}';
       
        $exposed_resources['GET  | Get All Reviews'] = '/reviews';
        $exposed_resources['GET | Car Reviews by Car_ID (for test = 6403)'] = '/cars/{car_ID}/reviews';
        $exposed_resources['POST | Create a Review'] = '/reviews';
        $exposed_resources['PUT  | Update a Review'] = '/reviews';
        $exposed_resources['DELETE  | Delete a Review'] = '/reviews';
        
        $exposed_resources['GET  | GET ALL CARS'] = '/cars';
        $exposed_resources['GET  | GET car by id'] = '/car/{id}|';

        $exposed_resources['GET  | GET ALL CARS'] = '/cars';
        $exposed_resources['GET  | GET car by id'] = '/car/{id}|';
        $exposed_resources['POST  | Insert car'] = '/addcar';
        $exposed_resources['DELETE  | delete car'] = 'deleteCar/{CarId}';
        $exposed_resources['UPDATE  | update car'] = 'updateCar/{CarId}';

        $exposed_resources['GET  | GET ALL ENGINES'] = '/engines';
        $exposed_resources['GET  | GET engine by id'] = '/engine/{id}|';
        $exposed_resources['DELETE  | delete engine'] = 'deleteEngine/{EngineId}';
        $exposed_resources['UPDATE  | update engine'] = 'updateEngine/{EngineId}';
        // $exposed_resources['GET | Car Recalls by Car_ID'] = '/cars/{car_ID}/recalls';
        // $exposed_resources['GET | Everithing Found by Car_ID'] = '/cars/{car_ID}/all';

        $exposed_resources['GET | Recall Details By Recall ID'] = '/recall/{recall_id}';
        // $exposed_resources['GET | Recalls by Make'] = '/recall/make/{make_name}';
        // $exposed_resources['GET | Recalls by Model'] = '/recall/model/{model_name}';

    

        $exposed_resources['POST | Auth a User'] = '/token';
        $exposed_resources['POST | Create an Account'] = '/account';
                    
        return $this->prepareOkResponse($response, $exposed_resources);            
        
    }
}
