<?php

namespace Vanier\Api\Middleware;
use Slim\Factory\AppFactory;
use LogicException;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;

use UnexpectedValueException;

use Vanier\Api\Helpers\JWTManager;


class JWTAuthMiddleware implements MiddlewareInterface
{
    
    public function __construct(array $options = [])
    {
        global $app;
    }
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        global $app;
        $uri = $request->getUri();
        $method = $request->getMethod();
        //var_dump( $uri);
        //-- 1) Routes to ignore. 
        // We need to ignore the routes that enables client applications
        // to create an account and request a JWT token.
        if (strpos($uri, 'account') !== false || strpos($uri, 'token') !== false) {
            return $handler->handle($request);
        }
        //-- 2) Retrieve the token from the request Authorization's header. 
        $token = $request->getHeader('Authorization')[0] ?? '';
        //var_dump($token);exit;
        // Parse the token: remove the "Bearer " word.
        $parsed_token = explode(' ', $token)[1] ?? '';

        try {
            //-- 3) Try to decode the JWT token
            $decoded_token = JWTManager::DecodeToken($parsed_token, JWTManager::SIGNATURE_ALGO);
            //var_dump($decoded_token);exit;
        } catch (LogicException $e) {
            // Errors having to do with environmental setup or malformed JWT Keys
            throw new HttpUnauthorizedException($request, $e->getMessage(), $e);
        } catch (UnexpectedValueException $e) {
            // Errors having to do with JWT signature and claims
            throw new HttpUnauthorizedException($request, $e->getMessage(), $e);
        }
        
        // --4) Access to POST, PUT and DELETE operations must be restricted.
        //     Only admin accounts can be authorized.
        //var_dump($decoded_token);
        if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
            $role = $decoded_token['role'] ?? 'oye';
            $path = $uri->getPath();
            // var_dump( $path == '/vehicle-api/reviews');
            if($role != 'admin'){
                if(!( ($role == 'user') && ($path == '/vehicle-api/reviews') )) {
                    throw new HttpForbiddenException($request, 'Insufficient permission!');
                }
            }
        }
        //-- 5) The client application has been authorized:
        // Now we can store the token payload in the request object.
        // This will allow the target resource's callback to access the token payload for various purposes (such as logging, etc.)        
        $request = $request->withAttribute(APP_JWT_TOKEN_KEY, $decoded_token);
        //var_dump($decoded_token);exit;
        //-- 6) Don't remove the following lines: we need to pass the request to the next
        //      middleware in the middleware stack. 
        return $handler->handle($request);
    }
}
