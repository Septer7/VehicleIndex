<?php

    namespace Vanier\Api\Middleware;

    /**
     * These are the use statements called for the messages, middleware, interfaces, and our custom exception
     */

    use Fig\Http\Message\StatusCodeInterface as Http_code;
    use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Factory\ResponseFactory;
    use Vanier\Api\Exceptions\HttpNotAcceptableException;

    
    

class ContentNegotiationMiddleware implements MiddlewareInterface
{
        /**
         * This is an array for the 
         */
        private $supported_types = [APP_MEDIA_TYPE_JSON];

        public function __construct(array $extra_types = [])
        {
            $this->supported_types = array_merge($extra_types, $this->supported_types);
        }

        public function process(Request $request, RequestHandler $handler): ResponseInterface{
            //step 1 inspect the request header 
            //1a) get accept header
            $accept = $request->getHeaderLine("Accept");
            $str_supported_types = implode("|", $this->supported_types);
           //echo $str_supported_types; exit;
            if(!str_contains($str_supported_types, $accept )){
                // Step a)
                // throw new HttpNotAcceptableException($request, 'The requested resource is not accepted by the server. JSON media type is supported by the server');

                // Step b)
                // We create an instance of ResponseInterface
                $response = new \Slim\Psr7\Response();
                $error_messages = array("code"=>Http_code::STATUS_NOT_ACCEPTABLE/*406*/,"error"=>"Not Acceptable","description"=>"The server cannot produce a response because the resource type is not acceptable. List of accepted type: JSON, XML, YAML");
                $response->getBody()->write(json_encode($error_messages));
                $response->withStatus(201)->withAddedHeader("Content-type", APP_MEDIA_TYPE_JSON/* $accepted */);
                return $response;


            }
            $response = $handler->handle($request);

            return $response;
        }
}
    
?>