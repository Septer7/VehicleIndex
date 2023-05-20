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
use Vanier\Api\Models\CarsModel;
use Vanier\Api\Models\ReviewModel;
use Dflydev\FigCookies\FigRequestCookies;

// $api_vin_WMI = "https://vpic.nhtsa.dot.gov/api/vehicles/decodewmi/";
// $api_vin_DECODE = "https://vpic.nhtsa.dot.gov/api/vehicles/decodevinvalues/";

// $clientWMI = new GuzzleClient(['base_uri' => $api_vin_WMI]);
// $clientDECODE = new GuzzleClient(['base_uri' => $api_vin_DECODE]);


class ReviewController extends BaseController
{
    public function getAllReviews(Request $request, Response $response, array $args) {
         // 1) Retrieve the parsed JWT form request object.
         $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
            
         // 2) Log request info into th ws_logtable.
         $logging_model = new WSLoggingModel();
         $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
         $logging_model->logUserAction($token_payload, $request_info);
 
         //
        $responseCodes = new ResponseCodes();
        $helperFunctions = new HelperFunctions();
        $reviews = array();
        $response_data = array();
        $review_model = new ReviewModel();
    
        $input_page_number = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $input_per_page = filter_input(INPUT_GET, "per_page", FILTER_VALIDATE_INT);
        if ($input_page_number == null) 
            $input_page_number = 1;
        if ($input_per_page == null)
            $input_per_page = 10;
        $review_model->setPaginationOptions($input_page_number, $input_per_page);
    
    
        $filter_params = $request->getQueryParams();
        if(isset($filter_params['star_rating']))
            $reviews = $review_model->getReviewsByRate($filter_params['star_rating']);
        else if(isset($filter_params['year_to'])&&isset($filter_params['year_from'])){
            if(!$helperFunctions->validateYear($filter_params['year_to'])&&!$helperFunctions->validateYear($filter_params['year_from'])){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, "Incorrect Date format. Should be 'YYYY' ");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }
            $reviews = $review_model->getReviewsBetweenYears($filter_params['year_from'],$filter_params['year_to']);
        }else if(isset($filter_params['year_from'])){
            if(!$helperFunctions->validateYear($filter_params['year_from'])){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, "Incorrect Date format. Should be 'YYYY' ");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }
            $reviews = $review_model->getReviewsAfterYear($filter_params['year_from']);
        } else if(isset($filter_params['year_to'])){
            if(!$helperFunctions->validateYear($filter_params['year_to'])){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, "Incorrect Date format. Should be 'YYYY' ");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }
            $reviews = $review_model->getReviewsBeforeYear($filter_params['year_to']);
        } else if (isset($filter_params['year'])){
            if(!$helperFunctions->validateYear($filter_params['year'])){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, "Incorrect Date format. Should be 'YYYY' ");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }
            $reviews = $review_model->getReviewsOfYear($filter_params['year']);
        }   
        else
            $reviews = $review_model->getAll();
        return $helperFunctions->checkData($reviews, $response, $request);
    }
     /**
     * Gets all reviews made by the specified user (GET /cars/{car_id}/reviews)
     */
    public function getReviewByCarID(Request $request, Response $response, array $uri_args) {
        // 1) Retrieve the parsed JWT form request object.
        $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
        $filters = $request->getQueryParams();   
        // 2) Log request info into th ws_logtable.
        $logging_model = new WSLoggingModel();
        $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
        $logging_model->logUserAction($token_payload, $request_info);

        //
       $reviews = array();
       $response_data = array();
       $responseCodes = new ResponseCodes();
       $helperFunctions = new HelperFunctions();
       $review_model = new ReviewModel();
   
       $input_page_number = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
       $input_per_page = filter_input(INPUT_GET, "per_page", FILTER_VALIDATE_INT);
       if ($input_page_number == null) 
           $input_page_number = 1;
       if ($input_per_page == null)
           $input_per_page = 10;
       $review_model->setPaginationOptions($input_page_number, $input_per_page);
   
       // Retrieve the review if from the request's URI.
       $car_id= $uri_args["car_id"];
       if (isset($car_id)) {
           // Fetch the info about the specified review.
           $response_data = $review_model->getCarReviews($filters, $car_id);
           
           $data['data'] = $response_data;

           return $helperFunctions->checkData($data, $response, $request);
       }
       return $responseCodes->httpMethodNotAllowed();
   }
    /**
     * Gets all reviews made by the specified user (GET /users/{user_id}/reviews)
     */
    public function getUserReviews(Request $request, Response $response, array $args) {
         // 1) Retrieve the parsed JWT form request object.
         $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
            
         // 2) Log request info into th ws_logtable.
         $logging_model = new WSLoggingModel();
         $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
         $logging_model->logUserAction($token_payload, $request_info);
 
         //
        $reviews = array();
        $response_data = array();
        $responseCodes = new ResponseCodes();
        $helperFunctions = new HelperFunctions();
        $review_model = new ReviewModel();
    
        $input_page_number = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $input_per_page = filter_input(INPUT_GET, "per_page", FILTER_VALIDATE_INT);
        if ($input_page_number == null) 
            $input_page_number = 1;
        if ($input_per_page == null)
            $input_per_page = 10;
        $review_model->setPaginationOptions($input_page_number, $input_per_page);
    
        // Retrieve the review if from the request's URI.
        $review_id= $args["user_id"];
        if (isset($review_id)) {
            // Fetch the info about the specified review.
            $reviews = $review_model->getUserReviews($review_id);
            return $helperFunctions->checkData($reviews, $response, $request);
        }
        return $responseCodes->httpMethodNotAllowed();
    }

    public function handleCreateReview(Request $request, Response $response){
         // 1) Retrieve the parsed JWT form request object.
         $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
            
         // 2) Log request info into th ws_logtable.
         $logging_model = new WSLoggingModel();
         $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
         $logging_model->logUserAction($token_payload, $request_info);
 
         //
        $review = $request->getParsedBody();
        $responseCodes = new ResponseCodes();
        $helperFunctions = new HelperFunctions();
        $review_model = new ReviewModel();
        $car_model = new CarsModel();
        

        foreach($review as $property => $value){
           
            if(empty($value)){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, "$property property can not be null");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }
            
        }
       
            
            $car_id =  $car_model->getCarID($review['year'],$review['make'],$review['model']);
            $user_id = $helperFunctions->getCurrentUserID($request);
            if(!isset($car_id)){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " Car not found in our records.");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }
            if(!isset($user_id)){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " You are not authorized..");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }

            $new_review_record = array(
                "car_id" => $car_id['car_id'],
                "user_id" => $user_id,
                "title" => $review['title'],
                "star_rating" => $review['star_rating'],
                "content" => $review['content'],
            );
            
            $review_model->createReview($new_review_record); 
            $response->getBody()->write(json_encode($new_review_record));
            return $response->withStatus(HTTP_CREATED);
    }
   
    public function handleDeleteReview(Request $request, Response $response){
         // 1) Retrieve the parsed JWT form request object.
         $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
            
         // 2) Log request info into th ws_logtable.
         $logging_model = new WSLoggingModel();
         $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
         $logging_model->logUserAction($token_payload, $request_info);
 
         //
        $review = $request->getParsedBody();
        $responseCodes = new ResponseCodes();
        $helperFunctions = new HelperFunctions();
        $review_model = new ReviewModel();
        foreach($review as $property => $value){    
            if(empty($value)){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, "$property property can not be null");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }
            
        }
        $user_id = $helperFunctions->getCurrentUserID($request);
        if(!isset($user_id)){
            $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " You are not authorized..");
            return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
        }
        $db_user = $review_model->getReviewById($review['review_id']);
        if(!isset($db_user['user_id'])){
            $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " Review not Found..");
            return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
        }
        if(($user_id!=$db_user['user_id'])){
            $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " You are not authorized to delete someone elses review.");
            return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
        }
        $review_model->deleteReview($review['review_id']); 
            $response->getBody()->write(json_encode("Review ".$review['review_id']." Successfully Deleted."));
            return $response->withStatus(HTTP_OK);
    }

    public function handleUpdateReview(Request $request, Response $response){
         // 1) Retrieve the parsed JWT form request object.
         $token_payload = $request->getAttribute(APP_JWT_TOKEN_KEY);
            
         // 2) Log request info into th ws_logtable.
         $logging_model = new WSLoggingModel();
         $request_info = $_SERVER["REMOTE_ADDR"].' '.$request->getUri()->getPath();
         $logging_model->logUserAction($token_payload, $request_info);
 
         //
        $review = $request->getParsedBody();
        $responseCodes = new ResponseCodes();
        $helperFunctions = new HelperFunctions();
        $review_model = new ReviewModel();
        foreach($review as $property => $value){    
            if(empty($value)){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, "$property property can not be null");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }
            
        }
        $user_id = $helperFunctions->getCurrentUserID($request);
        if(!isset($user_id)){
            $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " You are not authorized..");
            return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
        }
        $db_user = $review_model->getReviewById($review['review_id']);
        if(!isset($db_user['user_id'])){
            $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " Review not Found..");
            return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
        }
        if(($user_id!=$db_user['user_id'])){
            $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, " You are not authorized to edit someone elses review.");
            return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
        }

        $update_review_record = array(
            "title" => $review['title'],
            "star_rating" => $review['star_rating'],
            "content" => $review['content'],
        );

        $review_model->updateReview($update_review_record,$review['review_id']); 
            $response->getBody()->write(json_encode("Review ".$review['review_id']." Successfully Updated."));
            return $response->withStatus(HTTP_OK);
    }
    
    
}


