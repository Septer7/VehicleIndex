<?php
namespace Vanier\Api\Helpers;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SimpleXMLElement;
use Slim\Factory\AppFactory;
use Symfony\Component\Yaml\Yaml;
use Vanier\Api\Helpers\ResponseCodes;

class HelperFunctions
{


    public function __construct()
    {
        
    }

/**
 * Verify requested resource representation.
 */
public function checkRepresentation(Request $request, Response $response, $data) {
    $responseCodes= new ResponseCodes();
    $requested_format = $request->getHeader('Accept');
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($data, JSON_INVALID_UTF8_SUBSTITUTE);
        $response_code = HTTP_OK;
    }
    else if ($requested_format[0] === APP_MEDIA_TYPE_XML) {
        $xml = new SimpleXMLElement('<xmlresponse/>');
        $this->array2XML($xml, $data);
        $response_data = $xml->asXML();
        $response_code = HTTP_OK;
    }
    else if ($requested_format[0] === APP_MEDIA_TYPE_YAML) {
        $response_data = Yaml::dump($data);
        $response_code = HTTP_OK;
    }
    else {
        $response_data = $responseCodes -> httpUnsupportedMediaType();
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

/**
 * Convert an array to XML.
 */
public function array2XML($obj, $array)
{
    foreach ($array as $key => $value)
    {
        if(is_numeric($key))
            $key = 'item' . $key;

        if (is_array($value))
        {
            $node = $obj->addChild($key);
            $this->array2XML($node, $value);
        }
        else
        {
            $obj->addChild($key, htmlspecialchars($value));
        }
    }
}

/**
 * Function to handle error 404 (Not Found)
 */
public function checkData($data, Response $response, Request $request) {
    $responseCodes= new ResponseCodes();
    if (empty($data['data'])) {
        $response_data = $responseCodes->httpNotFound();
        $response->getBody()->write($response_data);
        return $response->withStatus(HTTP_NOT_FOUND);
    }
    else {
        return $this->checkRepresentation($request, $response, $data);
    }
}

/**
 * Function to handle responses with status code and data.
 */
public function response($response_data, $response_code, Response $response) {
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

/**
 * Validates date format.
 */
public function validateDate($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

}