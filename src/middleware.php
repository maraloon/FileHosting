<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(function ($request, $response, $next) {
    //$response->getBody()->write('BEFORE');
    
    //$args['bootstrapCss']=$this->settings['bootstrapCss'];
    //$args['userCss']=$this->settings['userCss'];

    //$response->args['bootstrapCss']=$this->settings['bootstrapCss'];
    //$response->args['userCss']=$this->settings['userCss'];

    $response = $next($request, $response);
    //$response->getBody()->write('AFTER');
    return $response;
});