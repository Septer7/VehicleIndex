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
use Vanier\Api\Models\CarsModel;

$api_recall_DECODE = "https://vrdb-tc-apicast-production.api.canada.ca/eng/vehicle-recall-database/v1/recall-summary/recall-number/";
$clientDECODE = new GuzzleClient(['base_uri' => $api_recall_DECODE]);



class RecallController extends BaseController
{
    //for composite resources
    //retrieves car record from nhtsa api based on the vin number provided
    public function getRecallByID(Request $request, Response  $response, array $args )
    {
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        //
        
        $api_recall_DECODE = "https://vrdb-tc-apicast-production.api.canada.ca/eng/vehicle-recall-database/v1/recall-summary/recall-number/";
        // $api_recall_key = ;
        // var_dump($api_recall_key);
        $clientDECODE = new GuzzleClient(['base_uri' => $api_recall_DECODE]);
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
        $vinNumber = $args["recall_id"].'?format=json';
        $data = searchForRecall($request, $clientDECODE, $vinNumber);
        
        if (!empty($data)) {
            $json_data = json_encode($data);
            $response->getBody()->write($json_data);

            return $response->withStatus(HTTP_OK)->withHeader("Content_type", APP_MEDIA_TYPE_JSON);
        }else{
             return $response->withStatus(400);
        }
        
    }

    public function getRecallByMake(Request $request, Response  $response, array $args )
    {
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        //
        
        $api_recall_WMI = "https://vrdb-tc-apicast-production.api.canada.ca/eng/vehicle-recall-database/v1/recall/make-name/";
        $clientWMI = new GuzzleClient(['base_uri' => $api_recall_WMI]);
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
        $makeName = $args["make_name"];
       
      
        // ccount total recalls
        $api_recall_WMIcount = "https://vrdb-tc-apicast-production.api.canada.ca/eng/vehicle-recall-database/v1/recall/manufacturer-name/";
        $clientWMIcount = new GuzzleClient(['base_uri' => $api_recall_WMIcount]);
        $countName = $args["make_name"].'/count';
        $data =  (object)[];
        $data->total_recalls = (searchForRecall($request, $clientWMIcount, $countName)->ResultSet)[0][0]->Value->Literal;
        $data->data = (searchForRecall($request, $clientWMI, $makeName)->ResultSet);
        //var_dump($data->count[0][0]->Value->Literal);
        if (!empty($data)) {
            $json_data = json_encode($data);
            $response->getBody()->write($json_data);

            return $response->withStatus(HTTP_OK)->withHeader("Content_type", APP_MEDIA_TYPE_JSON);
        }else{
             return $response->withStatus(400);
        }
        
    } 


    public function getRecallByModel(Request $request, Response  $response, array $args )
    {
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        //
        
        $api_recall_WMI = "https://vrdb-tc-apicast-production.api.canada.ca/eng/vehicle-recall-database/v1/recall/model-name/";
        $clientWMI = new GuzzleClient(['base_uri' => $api_recall_WMI]);
        $api_recall_WMIcount = "https://vrdb-tc-apicast-production.api.canada.ca/eng/vehicle-recall-database/v1/recall/model-name/";
        $clientWMIcount = new GuzzleClient(['base_uri' => $api_recall_WMIcount]);


        //pagination
        $input_page_number = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $input_per_page = filter_input(INPUT_GET, "per_page", FILTER_VALIDATE_INT);
        if ($input_page_number == null) 
            $input_page_number = 1;
        if ($input_per_page == null)
            $input_per_page = 10;

    
      
        // ccount total recalls
  
        $modelName = $args["model_name"];
        $countName = $args["model_name"].'/count';
        $data =  (object)[];
        $data->total_recalls = (searchForRecall($request, $clientWMIcount, $countName)->ResultSet)[0][0]->Value->Literal;
        $data->data = (searchForRecall($request, $clientWMI, $modelName)->ResultSet);
        //var_dump($data->count[0][0]->Value->Literal);
        if (!empty($data)) {
            $json_data = json_encode($data);
            $response->getBody()->write($json_data);

            return $response->withStatus(HTTP_OK)->withHeader("Content_type", APP_MEDIA_TYPE_JSON);
        }else{
             return $response->withStatus(400);
        }
        
    } 

    // composite 
    public function getRecallByCarID(Request $request, Response  $response, array $args ) {
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        //
        $cars_model = new CarsModel();
        $api_recall_WMI = "https://vrdb-tc-apicast-production.api.canada.ca/eng/vehicle-recall-database/v1/recall/model-name/";
        $clientWMI = new GuzzleClient(['base_uri' => $api_recall_WMI]);

        //pagination
        $input_page_number = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $input_per_page = filter_input(INPUT_GET, "per_page", FILTER_VALIDATE_INT);
        if ($input_page_number == null) 
            $input_page_number = 1;
        if ($input_per_page == null)
            $input_per_page = 10;

        
        $car_id = intval($args["car_id"]);
        $car_name = (object)[];
        $car_name = ($cars_model->getCarById($car_id))[0];

       
        $data =  (object)[];
        $data->car_data = $car_name;
        $data->recall_data = (searchForRecall($request, $clientWMI,$car_name['model'] )->ResultSet);
        //var_dump($data->count[0][0]->Value->Literal);
        if (!empty($data)) {
            $json_data = json_encode($data);
            $response->getBody()->write($json_data);

            return $response->withStatus(HTTP_OK)->withHeader("Content_type", APP_MEDIA_TYPE_JSON);
        }else{
             return $response->withStatus(400);
        }
        

        }
}
function searchForRecall(Request $request, GuzzleClient $client, $vinNumber) {
    $response = $client->request('GET', $vinNumber,[
        'headers' => [
            'Accept'     => 'application/json',
            'user-key'      => '528c10be4ab9c135cf12829dd38fd5c7'
        ]
        ]);
    $data = json_decode($response->getBody()->getContents());
    return $data;
}

