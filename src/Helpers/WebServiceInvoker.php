<?php


namespace Vanier\Api\Helpers;

use GuzzleHttp\Client;
use Exception;

class WebServiceInvoker
{
    private $request_options = [];

    public function __construct(array $options = [])
    {
        $this->request_options = $options;
    }

    public function invokeUri(string $resourceUri)
    {
        //step1: sending a request: instantiate a new client object
    $client = new Client(['base_uri' => 'http://']);
    $response = $client->request('GET', $resourceUri, $this->request_options);

    //process the response
    //1) did we get the right status code (200 ok)
    if ($response->getStatusCode() !== 200) {
        throw new Exception('Something went wrong... '.$response->getReasonPhrase());
    }
    //2) verify the requested ressource  representation
    // var_dump($response->getHeaderLine('Content-Type'));
    if (!str_contains($response->getHeaderLine('Content-Type'), 'application/json')){
        throw new Exception('Something went wrong... '.$response->getReasonPhrase());
    }
    //3) we can retrieve a data from the body 
    $data = $response->getBody()->getContents();
    return $data;
    }
}