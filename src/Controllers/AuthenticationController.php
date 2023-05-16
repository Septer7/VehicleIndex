<?php
namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Models\UserModel;
use Vanier\Api\Helpers\JWTManager;
use Vanier\Api\Helpers\Validator;
use Vanier\Api\Helpers\ResponseCodes;
use Vanier\Api\Helpers\HelperFunctions;


/**
 * Base controller that handled redundant code here
 */
class AuthenticationController extends BaseController
{
    public function handleGetToken(Request $request, Response $response, array $args) {
        $user_data = $request->getParsedBody();
        //var_dump($user_data);exit;
        $user_model = new UserModel();
        $jwtManager = new JWTManager();
    
        if (empty($user_data)) {
            return $this->prepareResponse($response,
                    ['error' => true, 'message' => 'No data was provided in the request.'], 400);
        }
        // The received user credentials.
        $email = $user_data["email"];
        $role = $user_data["role"];
        $password = $user_data["password"];
        // Verify if the provided email address is already stored in the DB.
        $db_user = $user_model->verifyEmail($email);
        if (!$db_user) {
            return $this->prepareResponse($response,
                    ['error' => true, 'message' => 'The provided email does not match our records.'], 400);
        }

        $db_user = $user_model->verifyRole($role);
        if (!$db_user) {
            return $this->prepareResponse($response,
                    ['error' => true, 'message' => 'The provided role does not match our records.'], 400);
        }
        // Now we verify if the provided passowrd.
        $db_user = $user_model->verifyPassword($email, $password);
        if (!$db_user) {
            return $this->prepareResponse($response,
                    ['error' => true, 'message' => 'The provided password was invalid.'], 400);
        }
    
        // Valid user detected => Now, we generate and return a JWT.
        // Current time stamp * 60 minutes * 60 seconds
        $jwt_user_info = ["id" => $db_user["user_id"], "role" => $db_user["role"] , "email" => $db_user["email"]];
        $expires_in = time() + 60 * 60; // Expires in 1 hour.
        // $expires_in = time() + 60; // Expires in 1 minute.
        $user_jwt = JWTManager::generateToken($jwt_user_info, $expires_in);
        //--
        $response_data = json_encode([
            'status' => 1,
            'token' => $user_jwt,
            'message' => 'User logged in successfully!',
            'token_expiration(hh:mm:ss)' => gmdate("H:i:s", $expires_in-time()),
        ]);
        $response->getBody()->write($response_data);
        return $response->withStatus(HTTP_OK);
    }
    
    // HTTP POST: URI /account 
    // Creates a new user account.
    public function handleCreateUserAccount(Request $request, Response $response, array $args) {
        
        $data = $request->getParsedBody();
        $user_model = new UserModel();
        $responseCodes = new ResponseCodes();
        $helperFunctions = new HelperFunctions();
        // Verify if information about the new user to be created was included in the 
        // request.
        if (empty($data)) {
            return $this->prepareResponse($response,
                    ['error' => true, 'message' => 'No data was provided in the request.'], 400);
        }
        
        // Data was provided, we attempt to create an account for the user.        
        
        //check if the user exist already 
        for ($index =0; $index < count($data); $index++){
            $single_user = $data[$index];
            //check if the user exist already 
            $checkExistEmail = $user_model->getUserByEmail($single_user["email"]);
            if($checkExistEmail['data']){
                $response_data = $responseCodes->makeCustomJSONError(HTTP_METHOD_NOT_ALLOWED, "Email needs to be Unique");
                return $helperFunctions->response($response_data, HTTP_METHOD_NOT_ALLOWED, $response);
            }

            $new_users_record = array(
                "first_name" => $single_user["first_name"],
                "last_name" => $single_user["last_name"],
                "email" => $single_user["email"],
                "password" => $single_user["password"],
                "role" => $single_user["role"]
            );   
    
            $new_user = $user_model->createUser($new_users_record);
            //--
            if (!$new_user) {
                // Failed to create the new user.
                return $this->prepareResponse($response,
                        ['error' => true, 'message' => 'Failed to create the new user.'], 400);
            }
            // The user account has been created successfully.  
            return $this->prepareResponse($response,
                    ['error' => false, 'message' => 'The new user account has been created successfully!'], 200);
        }

        return $helperFunctions->response($responseCodes->httpCreated(), HTTP_CREATED, $response);
    }
    
}

