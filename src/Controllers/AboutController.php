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
        // $exposed_resources['GET | Car Manufacturer By VIN'] = '/vin/getManufacturer/{vinNumber}';
       
        $exposed_resources['GET  | Get All Reviews'] = '/reviews';
        $exposed_resources['POST | Create a Review'] = '/reviews';
        $exposed_resources['PUT  | Update a Review'] = '/reviews';
        $exposed_resources['DELETE  | Delete a Review'] = '/reviews';
        
        
        $exposed_resources['GET  | Get All Cars'] = '/cars';
        $exposed_resources['GET  | Get A Car'] = '/car/{CarId}';
        $exposed_resources['POST | Add a Car'] = '/addcar';
        $exposed_resources['PUT  | Update a Car'] = '/updateCar/{CarId}';
        $exposed_resources['DELETE  | Delete a Car'] = '/deleteCar/{CarId}';


        $exposed_resources['GET  | Get All Engines'] = '/engines';
        $exposed_resources['GET  | Get an Engine'] = '/enigne/{CarId}';
        $exposed_resources['PUT  | Update an Engine'] = '/updateEngine/{CarId}';
        $exposed_resources['DELETE  | Delete an Engine'] = '/deleteEngine/{CarId}';
        
        
        
        
        // $exposed_resources['GET | All Cars'] = '/cars';
        // $exposed_resources['GET | All Cars Makes'] = '/cars/makes';
        // $exposed_resources['GET | All Models by Make'] = '/cars/make/{make_name}';
        // $exposed_resources['GET | All Car Types by Make'] = '/cars/make/{make_name}/types';
        // $exposed_resources['GET | Car Details by Car_ID'] = '/cars/{car_ID}';
        // $exposed_resources['GET | Car Recalls by Car_ID'] = '/cars/{car_ID}/recalls';
        // $exposed_resources['GET | Car Reviews by Car_ID'] = '/cars/{car_ID}/reviews';
        // $exposed_resources['GET | Car Emission by Car_ID'] = '/cars/{car_ID}/emission';
        // $exposed_resources['GET | Car Consumption by Car_ID'] = '/cars/{car_ID}/consumption';
        // $exposed_resources['GET | Everithing Found by Car_ID'] = '/cars/{car_ID}/all';

        // $exposed_resources['GET | Recall Details By Recall ID'] = '/recall/{recall_id}';
        // $exposed_resources['GET | Recalls by Make'] = '/recall/make/{make_name}';
        // $exposed_resources['GET | Recalls by Model'] = '/recall/model/{model_name}';

        // $exposed_resources['GET | All Users'] = '/users';
        // $exposed_resources['GET | All Reviews by User ID'] = '/users/{user_id:[0-9]+}/reviews';
        // $exposed_resources['GET | All Logs by User ID'] = '/users/{user_id:[0-9]+}/logs ';
        // $exposed_resources['GET | All Cars In Garage by User ID'] = '/users/{user_id:[0-9]+}/garage';
        // $exposed_resources['GET | All Recalls for Cars in a  User Garage'] = '/users/{user_id:[0-9]+}/garage/recalls';

        // $exposed_resources['POST | Create User Car'] = '/garage';
        // $exposed_resources['PUT | Update User Car'] = '/garage';
        // $exposed_resources['DELETE | Delete Car By Id'] = '/garage/{car_id}';

        $exposed_resources['POST | Auth a User'] = '/token';
        $exposed_resources['POST | Create an Account'] = '/account';
                    
        return $this->prepareOkResponse($response, $exposed_resources);            
        
    }
}
