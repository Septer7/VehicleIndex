<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Vanier\Api\Models\WSLoggingModel;

// Callback for HTTP GET /films
function handleGetAllFilms(Request $request, Response $response, array $args) {       
    
    // $films_model = new FilmsModel();
    //----------------------------------------    
    $logging_model = new WSLoggingModel();
    //-- Get the decode JWT payload section. 
    $decoded_jwt = $request->getAttribute('decoded_token_data');
    $logging_model->logUserAction($decoded_jwt, "getListOfArtists");
    //--------------------------------------       
}


