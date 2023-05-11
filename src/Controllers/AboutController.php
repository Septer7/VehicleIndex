<?php
namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Helpers\Validator;

class AboutController extends BaseController
{
    public function handleAboutApi(Request $request, Response $response, array $uri_args)
    {
        $exposed_resources = array();
        $exposed_resources['about'] = 'Welcome, this is a Web services that provides this and that...';
        $exposed_resources['GET | Test route'] = '/hello';
        $exposed_resources['GET | Vin Decode'] = '/vin/{vinNumber}';
        $exposed_resources['GET | Manufacturer by Vin'] = '/vin/getManufacturer/{vinNumber}';
                    
        return $this->prepareOkResponse($response, $exposed_resources);            
        
    }
}
