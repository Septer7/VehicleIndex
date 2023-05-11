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
        $exposed_resources['GET | Test route'] = '/hello';
        $exposed_resources['GET | Vin Decode'] = '/vin/{vinNumber}';
        $exposed_resources['GET | Manufacturer by Vin'] = '/vin/getManufacturer/{vinNumber}';
                    
        return $this->prepareOkResponse($response, $exposed_resources);            
        
    }
}
