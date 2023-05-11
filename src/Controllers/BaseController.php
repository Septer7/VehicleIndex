<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Helpers\Validator;

class BaseController
{
    protected function prepareOkResponse(Response $response, array $data, int $status_code = 200)
    {
        // var_dump($data);
        $json_data = json_encode($data);
        //-- Write data into the response's body.        
        $response->getBody()->write($json_data);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }
    protected function isValidPageParams($data)
    {

        // // The array containing the data to be validated.
        // $data = array(
        //     "page" => '1',
        //     "page_size" => '46',
        // );
        // An array element can be associated with one or more validation rules. 
        // Validation rules must be wrapped in an associative array where:
        // NOTE: 
        //     key => must be an existing key  in the data array to be validated. 
        //     value => array of one or more rules.    
        $rules = array(
            'page' => [
                'required',
                'numeric',
                ['min', 1]
            ],
            'page_size' => [
                'required',
                'integer',
                ['min', 5],
                ['max', 50]
            ]
        );

        // Create a validator and override the default language used in expressing the error messages.
        //    $validator = new Validator($data, [], 'fr');
        $validator = new Validator($data);

        // Important: map the validation rules before calling validate()
        $validator->mapFieldsRules($rules);
        return $validator->validate();      
    }

    
    protected function prepareResponse(Response $response, $in_payload, $status_code) {
        $payload = json_encode($in_payload);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', APP_MEDIA_TYPE_JSON)
                        ->withStatus($status_code);
    }
}
