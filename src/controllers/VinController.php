<?php
namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Helpers\Validator;
use Vanier\Api\Models\WSLoggingModel;
//FOR COMPOSITE RESOURCES
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;    
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Exception\RequestException;
use Vanier\Api\Helpers\HelperFunctions;
use Vanier\Api\Helpers\ResponseCodes;

$api_vin_WMI = "https://vpic.nhtsa.dot.gov/api/vehicles/decodewmi/";
$api_vin_DECODE = "https://vpic.nhtsa.dot.gov/api/vehicles/decodevinvalues/";

$clientWMI = new GuzzleClient(['base_uri' => $api_vin_WMI]);
$clientDECODE = new GuzzleClient(['base_uri' => $api_vin_DECODE]);


class VinController extends BaseController
{
    //for composite resources
    //retrieves car record from nhtsa api based on the vin number provided
    public function getCarByVin(Request $request, Response  $response, array $args )
    {
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
            
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        //
        $api_vin_DECODE = "https://vpic.nhtsa.dot.gov/api/vehicles/decodevinvalues/";
        $clientDECODE = new GuzzleClient(['base_uri' => $api_vin_DECODE]);
        $data = array();
        $response_data = array();

        //pagination
        $input_page_number = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $input_per_page = filter_input(INPUT_GET, "per_page", FILTER_VALIDATE_INT);
        if ($input_page_number == null) 
            $input_page_number = 1;
        if ($input_per_page == null)
            $input_per_page = 10;

         // Retrieve the query string parameter from the request's URI.
        
        //var_dump($args["vinNumber"]);
        $vinNumber = $args["vinNumber"].'?format=json';
        $data = searchForVin($clientDECODE, $vinNumber);
        
        if (!empty($data)) {
            $json_data = json_encode($data);
            $response->getBody()->write($json_data);

            return $response->withStatus(HTTP_OK)->withHeader("Content_type", APP_MEDIA_TYPE_JSON);
        }else{
             return $response->withStatus(400);
        }
        
    }

    public function getManufacturerByVin(Request $request, Response  $response, array $args )
    {
        $responseCodes = new ResponseCodes();
        $helperFunctions = new HelperFunctions();
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
            
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        //
        $api_vin_WMI = "https://vpic.nhtsa.dot.gov/api/vehicles/decodewmi/";
        $clientWMI = new GuzzleClient(['base_uri' => $api_vin_WMI]);
        $data = array();
        $response_data = array();

        //pagination
        $input_page_number = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $input_per_page = filter_input(INPUT_GET, "per_page", FILTER_VALIDATE_INT);
        if ($input_page_number == null) 
            $input_page_number = 1;
        if ($input_per_page == null)
            $input_per_page = 10;

         // Retrieve the query string parameter from the request's URI.
        
        //var_dump($args["vinNumber"]);
        $vinNumber = substr($args["vinNumber"], 0, 3).'?format=json';

        if(!isset($vinNumber)){
            $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " Vin number incorrect..");
            return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
        }

        $preFormat = searchForVin($clientWMI, $vinNumber);
        if (!empty($preFormat->Results)) {
            $data =  $preFormat->Results;
            $json_data = json_encode($data);
            $response->getBody()->write($json_data);

            return $response->withStatus(HTTP_OK)->withHeader("Content_type", APP_MEDIA_TYPE_JSON);
        }else{
             $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " Vin number incorrect or too Short to find the Manufacturer..");
             return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
        }
        
    }

    
}
function searchForVin(GuzzleClient $client, $vinNumber) {
    $response = $client->request('GET', $vinNumber);
    $data = json_decode($response->getBody()->getContents());
    
    return $data;
}

