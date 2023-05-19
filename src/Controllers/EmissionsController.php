<?php

namespace Vanier\Api\Controllers;
use Vanier\Api\Helpers\Validator;
use Vanier\Api\Helpers\ResponseCodes;
use Vanier\Api\Models\EmissionsModel;
use Vanier\Api\Models\WSLoggingModel;
use Vanier\Api\Helpers\HelperFunctions;
use Vanier\Api\Controllers\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmissionsController extends BaseController {

    /**
     * Gets all emissions
     */
    public function getAllEmissions(Request $request, Response $response, array $args) {
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
           
        // 2) Log request info into the ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        // 3) Instantiate parameters
        $helperFunctions = new HelperFunctions();
        $emissions = array();
        $emissions_model = new EmissionsModel();
        
        // 4) Set pagination options
        $input_page_number = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $input_per_page = filter_input(INPUT_GET, "per_page", FILTER_VALIDATE_INT);
        if ($input_page_number == null) 
            $input_page_number = 1;
        if ($input_per_page == null)
            $input_per_page = 10;
        $emissions_model->setPaginationOptions($input_page_number, $input_per_page);
   
        // 5) Handle parameters
        $filter_params = $request->getQueryParams();
        if(isset($filter_params['gas_emissions']))
            $emissions = $emissions_model->getEmissionsByGas($filter_params['gas_emissions']);
        else if(isset($filter_params['CO2_Index'])){
            $emissions = $emissions_model->getEmissionsByCO2($filter_params['CO2_Index']);
        }else if(isset($filter_params['Smog_Index'])){
            $emissions = $emissions_model->getEmissionsBySmog($filter_params['Smog_Index']);
        }else
            $emissions = $emissions_model->getAllEmissions();
        return $helperFunctions->checkData($emissions, $response, $request);
    }

    /**
     * Gets emissions by CarID
     */
    public function getEmissionsById(Request $request, Response $response, array $args) {
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
           
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        // 3) Instantiate parameters
        $responseCodes = new ResponseCodes();
        $helperFunctions = new HelperFunctions();
        $emissions = array();
        $emissions_model = new EmissionsModel();
   
        // 4) Retrieve the emissions if from the request's URI.
        $car_id= $args["user_id"];
        if (isset($car_id)) {
            $emissions = $emissions_model->getEmissionsById($car_id);
            return $helperFunctions->checkData($emissions, $response, $request);
        }
        return $responseCodes->httpMethodNotAllowed();
    }

    public function handleCreateEmission(Request $request, Response $response){
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
           
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        // 3) Instantiate parameters
        $responseCodes = new ResponseCodes();
        $helperFunctions = new HelperFunctions();
        $response_data = array();
        $emissions_model = new EmissionsModel();
        $emissions_params = $request->getParsedBody();
       
        // 4) Check for empty array
        foreach($emissions_params as $property => $value){
            if(empty($value)){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, "$property property can not be null");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }   
        }

        // 5) Check for null CarID
        if(!isset($emissions_params['CarID'])){
            $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " Car not found in our records.");
            return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
        }

        // 6) Create array of records
        $new_emissions_record = array(
            "CarID" => $emissions_params['CarID'],
            "gas_emissions" => $emissions_params['gas_emissions'],
            "CO2_Index" => $emissions_params['CO2_Index'],
            "Smog_Index" => $emissions_params['Smog_Index'],
        );
        $emissions_model->createEmissions($new_emissions_record); 
        $response->getBody()->write(json_encode($new_emissions_record));
        return $response->withStatus(HTTP_CREATED);
   }

    /**
     * Handles updating emissions statistics
     */
    public function handleUpdateEmissions(Request $request, Response $response){
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
       
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        // 3) Instantiate parameters
        $responseCodes = new ResponseCodes();
        $helperFunctions = new HelperFunctions();
        $response_data = array();
        $emissions_params = $request->getParsedBody();
        
        
        // 4) Check for empty array
        foreach($emissions_params as $property => $value){
            if(empty($value)){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, "$property property can not be null");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }   
        }

        // 5) Check for null CarID
        if(!isset($emissions_params['CarID'])){
            $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " Car not found in our records.");
            return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
        }

        // 6) Create updated record
        $emissions_model = new EmissionsModel();
        $CarID = $emissions_params['CarID'];
        $update_emissions_record = array(
            "gas_emissions" => $emissions_params['gas_emissions'],
            "CO2_Index" => $emissions_params['CO2_Index'],
            "Smog_Index" => $emissions_params['Smog_Index'],
        );
        $emissions_model->updateEmissions($update_emissions_record,$CarID); 
        $response->getBody()->write(json_encode("Emissions ".$CarID." Successfully Updated."));
        return $response->withStatus(HTTP_OK);
    }

    /**
     * Handles deleting emissions statistics
     */
    public function handleDeleteEmissions(Request $request, Response $response){
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
           
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        // 3) Instantiate parameters
        $responseCodes = new ResponseCodes();
        $helperFunctions = new HelperFunctions();
        $emissions_model = new EmissionsModel();
        $emissions_params = $request->getParsedBody();
        
        // 4) Check for empty array
        foreach($emissions_params as $property => $value){    
            if(empty($value)){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, "$property property can not be null");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }
        }

        // 5) Check for null CarID
        $emissions_params = $request->getParsedBody();
        if(!isset($emissions_params['CarID'])){
            $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " Car not found in our records.");
            return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
        }
      
        // 6) Delete Record
        $emissions = $emissions_model->getEmissionsById($emissions_params['CarID']);   
        $CarID = $emissions_params['CarID'];
        $emissions_model->deleteEmission($CarID); 
        $response->getBody()->write(json_encode("Emissions ".$CarID." Successfully Deleted."));
        return $response->withStatus(HTTP_OK);
    }
}
