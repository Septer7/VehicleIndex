<?php
namespace Vanier\Api\Helpers;
/**
 * Function to handle response code 200 
 */
class ResponseCodes
{


    public function __construct()
    {
    }
    public function httpOK() {
        $response_data = array(
            "status code:" => HTTP_OK,
            "message:" => "OK"
        );
        return json_encode($response_data);
    }

    /**
     * Function to handle response code 201 
     */
    public function httpCreated() {
        $response_data = array(
            "status code:" => HTTP_CREATED,
            "message:" => "The request has been fulfilled and has resulted in one or more new resources being created."
        );
        return json_encode($response_data);
    }

    /**
     * Function to handle response code 204
     */
    public function httpNoContent() {
        $response_data = array(
            "status code:" => HTTP_NO_CONTENT,
            "message:" => "OK. No content to return."
        );
        return json_encode($response_data);
    }

    /**
     * Function to handle error 405 (Unsupported Operation)
     */
    public function httpMethodNotAllowed() {
        $response_data = array(
            "status code:" => HTTP_METHOD_NOT_ALLOWED,
            "message:" => "The method specified in the Request-Line is not allowed for the resource identified by the Request-URI."
        );
        return json_encode($response_data);
    }

    /**
     * Function to handle error 415 (Unsupported Media Type)
     */
    public function httpUnsupportedMediaType() {
        $response_data = array(
            "status code:" => HTTP_UNSUPPORTED_MEDIA_TYPE,
            "message:" => "Cannot process the request because the media type is not supported. Only JSON, XML and YAML are supported."
        );
        return json_encode($response_data);
    }

    /**
     * Function to handle error 404 (Not Found)
     */
    public function httpNotFound() {
        $response_data = array(
            "status code:" => HTTP_NOT_FOUND,
            "message:" => "Requested resource not found"
        );
        return json_encode($response_data);
    }


    /**
     * Returns a custom error using the passed error code and message
     */
    public function makeCustomJSONError($error_code, $error_message) {
        $error_data = array(
            "status code:" => $error_code,
            "message:" => $error_message
        );
        return json_encode($error_data);
    }

    /**
     * Returns an error name and message if the the request was successfully deleted 
     */
    public function getSuccessDeleteMessage() {
        $error_data = array(
            "status code:" => HTTP_OK,
            "message:" => "The request was successfully deleted"
        );
        return json_encode($error_data);
    }
}