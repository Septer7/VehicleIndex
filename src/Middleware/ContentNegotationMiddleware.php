<?php

namespace Vanier\Api\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


class ContentNegotationMiddleware implements MiddlewareInterface {




    public function process(Request $request,  RequestHandler $handler): ResponseInterface{

        //Step 1) Inspect the request header.
        //Get the accept header
        $accept = $request->getHeaderLine("Accept");
        
        //Step 2) compare the requested resource representation format with what the service can provide
        if(!str_contains($accept, APP_MEDIA_TYPE_JSON)){

            
            //if it does not match refuse the processing request AND notify the client application; Raise an exception
           // throw new HttpNotAcceptableException($request);
 
       

        


        $response = new \Slim\Psr7\Response();

      

        
        $error_data = [
        "code"=> 305 ,
        "error" => "not acceptable",
        "description" => "The server cannot produce a response matching the list of acceptables values"
        ];

       
        $response ->getBody()->write(json_encode($error_data));


        return $response->withStatus(200)
            ->withAddedHeader("Content-type", APP_MEDIA_TYPE_JSON);
        

       
    }
        $response = $handler->handle($request);
        return $response;


        
    }






}